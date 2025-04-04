<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\AggregatorForm;
use App\Models\InternalUser;
use App\Models\LeadAssign;


class LeadController extends Controller
{
    public function index()
    {
        $user = Auth::guard('api')->user();

        if (!$user) {
            return response()->json([
                'response code' => 401,
                'message' => 'Unauthorized. Please log in.'
            ]);
        }

        $userID = $user->id;
        $role  = $user->role->role_name;

        $query = AggregatorForm::with(['category', 'subcategory', 'material', 'shade.shade', 'latestAssignment']);

        if ($role === 'Superuser') {
            $query->whereHas('latestAssignment', function ($q) use ($userID) {
                $q->where('internal_user_id', $userID);
            });
        }
        $leads = $query->orderBy('id', 'desc')->get();

        // Change the response to include only required fields
        $filteredLeads = $leads->map(function ($lead) {
            return [
                'id' => $lead->id,
                'category_name' => $lead->category?->category,
                'subcategory_name' => $lead->subcategory?->sub_category,
                'material_name' => $lead->material?->material_name,
                'location' => $lead->location,
                'mobile_no' => $lead->mobile_no,
                'shade_name' => $lead->shade->first()?->shade?->shade_name,
                'status' => $lead->status
            ];
        });
        return response()->json([
            'response_code' => 200,
            'message' => 'Lead Fetched Successfully!.',
            'data' => $filteredLeads
        ]);
    }

    public function show($id)
    {
        $lead = AggregatorForm::with(['category', 'subcategory', 'material', 'shade.shade'])->findOrFail($id);

        // Fetch the latest assignment for this lead_id
        $assign_admin_superuser = LeadAssign::where('lead_id', $id)
            ->latest('created_at')
            ->first();

        // Default values
        $assignEnabled = true;
        $reassignEnabled = false;
        $assignedUserName = 'Not Assigned Yet';

        // If an assignment exists, update values
        if ($assign_admin_superuser) {
            $assignEnabled = false;
            $reassignEnabled = true;

            // Fetch the name of the assigned user
            $assignedUser = InternalUser::find($assign_admin_superuser->internal_user_id);
            if ($assignedUser) {
                $assignedUserName = $assignedUser->name;
            }
        }

        // Once Assigned User lead details see then status change Inprogress
        $loggedInUserId = auth('admin')->id();

        if ($assign_admin_superuser && $assign_admin_superuser->internal_user_id == $loggedInUserId) {
            if (in_array($lead->status, ['Assigned', 'Re-Assigned'])) {
                $lead->update([
                    'status' => 'Inprogress'
                ]);

                $assign_admin_superuser->update([
                    'status' => 'Inprogress'
                ]);
            }
        }

        $data = [
            'id' => $lead->id,
            'category' => optional($lead->category)->category,
            'sub_category' => optional($lead->subcategory)->sub_category,
            'material_name' => optional($lead->material)->material_name,
            'material_main_img' => optional($lead->material)->main_img,
            'sub_img1' => optional($lead->material)->sub_img1,
            'sub_img2' => optional($lead->material)->sub_img2,
            'sub_img3' => optional($lead->material)->sub_img3,
            'sub_img4' => optional($lead->material)->sub_img4,
            'shade' => $lead->shade->map(function ($shade) {
                return [
                    'selected_img' => $shade->selected_img,
                    'shade_name' => optional($shade->shade)->shade_name,
                ];
            }),
            'width' => $lead->width,
            'height' => $lead->height,
            'unit' => $lead->unit,
            'location' => $lead->location,
            'quantity' => $lead->quantity,
            'design_service_need' => $lead->design_service_need,
            'email_id' => $lead->email_id,
            'mobile_no' => $lead->mobile_no,
            'site_image' => $lead->site_image,
            'design_attachment' => $lead->design_attachment,
            'reference_image' => $lead->reference_image,
            'status' => $lead->status,
            'created_at' => $lead->created_at,
            'assignEnabled' => $assignEnabled,
            'reassignEnabled' =>  $reassignEnabled,
            'assignedUserName' => $assignedUserName,
        ];

        return response()->json([
            'response code' => 200,
            'message' => 'Lead Detail Fetched Successfully!.',
            'data' => $data
        ]);
    }

    // Admin lead assign to admin or superuser
    public function leadAssign(Request $request)
    {
        //dd($request->all());
        $validate = Validator::make($request->all(), [
            'assign_user_id'     => 'nullable|exists:internal_users,id',
            'reassign_user_id'   => 'nullable|exists:internal_users,id',
            'lead_id'            => 'required|exists:aggregator_forms,id',
        ]);
        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        }

        $status = 'Assigned';
        $internalUserId = $request->assign_user_id;

        if ($request->reassign_user_id) {
            $status = 'Re-Assigned';
            $internalUserId = $request->reassign_user_id;
        }

        LeadAssign::create([
            'internal_user_id' => $internalUserId,
            'lead_id' => $request->lead_id,
            'status' => $status
        ]);

        AggregatorForm::where('id', $request->lead_id)->update([
            'status' => $status
        ]);

        return response()->json([
            'response code' => 200,
            'message' => 'Lead Assinged or Re-assigned successfully!'
        ]);
    }
}

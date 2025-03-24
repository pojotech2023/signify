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

        // Transform the response to include only required fields
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
            'data' => $filteredLeads
        ]);
    }

    public function show($id)
    {
        $lead = AggregatorForm::with(['category', 'subcategory', 'material', 'shade.shade'])->findOrFail($id);

        //Admin & Superuser list
        $admin_super_user = InternalUser::with('role')->whereHas('role', function ($query) {
            $query->whereIn('role_name', ['Admin', 'SuperUser']);
        })->get();

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
            'lead' => $lead,
            'admin_superuser_list' => $admin_super_user->map(function($user){
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'role' => $user->role->role_name
                ];
            }),
            'assignEnabled' => $assignEnabled,
            'reassignEnabled' =>  $reassignEnabled,
            'assignedUserName' => $assignedUserName,
        ];

        return response()->json([
            'response code' => 200,
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
        'message' => 'Admin or SuperUser successfully!'
       ]);
   }
}

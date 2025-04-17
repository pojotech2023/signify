<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AggregatorForm;
use App\Models\InternalUser;
use App\Models\LeadAssign;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class LeadsController extends Controller
{
    //leads list - minimal view
    public function index()
    {
        $role = session('role_name');
        $user = Auth::guard('admin')->user();

        if ($user) {
            $userID = $user->id;

            $query = AggregatorForm::with(['category', 'subcategory', 'material', 'shade.shade', 'latestAssignment'])
                ->whereDate('created_at', Carbon::today());

            if ($role === 'Superuser') {
                $query->whereHas('latestAssignment', function ($q) use ($userID) {
                    $q->where('internal_user_id', $userID);
                });
            }
            $leads = $query->orderBy('id', 'desc')->get();

            return view('admin.leads_list', compact('leads'));
        } else {
            return redirect()->route('admin.login')->withErrors(['message' => 'Please login first']);
        }
    }

    //lead detailed view
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

        return view('admin.leads_details', compact('lead', 'admin_super_user', 'assignEnabled', 'reassignEnabled', 'assignedUserName'));
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

        return redirect()->route('leads-list')->with('Success', 'Admin or SuperUser' .
            ($status == 'Assigned' ? 'Assigned' : 'Re-Assigned') . ' successfully!');
    }

    //date and status filter wise lead list
    public function filterLeadsList(Request $request)
    {
        $status = $request->input('status');
        $date = $request->input('date');

        if (!$status || !$date) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['Both status and date are required.']);
        }

        $role = session('role_name');
        $user = Auth::guard('admin')->user();

        if (!$user) {
            return redirect()->route('admin.login')->withErrors(['message' => 'Please login first']);
        }

        $userID = $user->id;

        $query = AggregatorForm::with(['category', 'subcategory', 'material', 'shade.shade', 'latestAssignment'])
            ->whereDate('created_at', $date);
        if (!empty($status) && $status !== 'All') {
            $query->where('status', $status);
        }

        if ($role === 'Superuser') {
            $query->whereHas('latestAssignment', function ($q) use ($userID) {
                $q->where('internal_user_id', $userID);
            });
        }

        $leads = $query->orderBy('id', 'desc')->get();

        return view('admin.leads_list', compact('leads'));
    }
}

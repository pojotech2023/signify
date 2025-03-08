<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AggregatorForm;
use App\Models\AssignAdminSuperuser;
use App\Models\InternalUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LeadsController extends Controller
{
    public function index()
    {
        $leads = AggregatorForm::with(['category', 'subcategory', 'material', 'shade.shade', 'latestAssignment'])->get();
        return view('admin.leads_list', compact('leads'));
    }

    public function show($id)
    {
        $lead = AggregatorForm::with(['category', 'subcategory', 'material', 'shade.shade'])->findOrFail($id);

        //Admin & Superuser list
        $admin_super_user = InternalUser::with('role')->whereHas('role', function ($query) {
            $query->whereIn('role_name', ['Admin', 'SuperUser']);
        })->get();

        // Fetch the latest assignment for this user_form_id
        $assign_admin_superuser = AssignAdminSuperuser::where('user_form_id', $id)
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

        return view('admin.leads_details', compact('lead', 'admin_super_user', 'assignEnabled', 'reassignEnabled', 'assignedUserName'));
    }

    public function assignAdminSuperuser(Request $request)
    {
        // dd($request->all());
        $validate = Validator::make($request->all(), [
            'assign_user_id' => 'nullable|exists:internal_users,id',
            'reassign_user_id' => 'nullable|exists:internal_users,id',
            'user_form_id' => 'required|exists:aggregator_forms,id',
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

        AssignAdminSuperuser::create([
            'internal_user_id' => $internalUserId,
            'user_form_id' => $request->user_form_id,
            'status' => $status
        ]);

        return redirect()->back()->with('Success', 'Admin or SuperUser' .
            ($status == 'Assigned' ? 'Assigned' : 'Re-Assigned') . ' successfully!');
    }
}

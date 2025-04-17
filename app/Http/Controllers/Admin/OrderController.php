<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Order;
use App\Models\InternalUser;
use App\Models\OrderAssign;
use App\Models\AggregatorForm;
use App\Models\LeadAssign;
use App\Models\LeadTask;
use App\Models\LeadTaskAssign;
use App\Models\OrderTask;
use App\Models\OrderTaskAssign;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    //Confirm Order - Store

    public function store(Request $request)
    {
        //dd($request->all());
        $validate = Validator::make($request->all(), [
            'lead_id' => 'required|exists:aggregator_forms,id'
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        }

        $order = Order::create([
            'lead_id' => $request->lead_id,
        ]);

        // Update status in aggregator_forms table
        AggregatorForm::where('id', $request->lead_id)->update([
            'status' => 'Completed'
        ]);

        // Update status in lead assign table
        LeadAssign::where('lead_id', $request->lead_id)->update([
            'status' => 'Completed'
        ]);

        // Update status in lead task table
        $taskIds = LeadTask::where('lead_id', $request->lead_id)->pluck('id');

        LeadTask::whereIn('id', $taskIds)->update(['status' => 'Completed']);

        // Update status in lead task assign table
        LeadTaskAssign::whereIn('task_id', $taskIds)->update(['status' => 'Completed']);

        return redirect()->back()->with('Success', 'Order Created Successfully');
    }

    //Orders List
    public function index(Request $request)
    {
        $role = session('role_name');
        $user = Auth::guard('admin')->user();

        if ($user) {
            $userID = $user->id;

            $query = Order::with('lead.category', 'lead.material', 'lead.subcategory', 'lead.shade.shade', 'orderAssign')
                ->whereDate('created_at', Carbon::today());

            if ($role === 'Superuser') {
                $query->whereHas('orderAssign', function ($q) use ($userID) {
                    $q->where('internal_user_id', $userID);
                });
            }
            $orders = $query->orderBy('id', 'desc')->get();

            return view('admin.order.orders_list', compact('orders'));
        } else {
            return redirect()->route('admin.login')->withErrors(['message' => 'Please login first']);
        }
    }

    //Order Details

    public function show($id)
    {

        $order = Order::with('lead.category', 'lead.material', 'lead.subcategory', 'lead.shade.shade')->findOrFail($id);

        //Admin & Superuser list
        $admin_super_user = InternalUser::with('role')->whereHas('role', function ($query) {
            $query->whereIn('role_name', ['Admin', 'SuperUser']);
        })->get();

        // Fetch the latest assignment for this order_id
        $assign_admin_superuser = OrderAssign::where('order_id', $id)
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
            if (in_array($order->status, ['Assigned', 'Re-Assigned'])) {
                $order->update([
                    'status' => 'Inprogress'
                ]);

                $assign_admin_superuser->update([
                    'status' => 'Inprogress'
                ]);
            }
        }
        return view('admin.order.orders_details', compact('order', 'admin_super_user', 'assignEnabled', 'reassignEnabled', 'assignedUserName'));
    }

    //Order Assign to admin or superuser

    public function orderAssign(Request $request)
    {
        // dd($request->all());
        $validate = Validator::make($request->all(), [
            'assign_user_id' => 'nullable|exists:internal_users,id',
            'reassign_user_id' => 'nullable|exists:internal_users,id',
            'order_id' => 'required|exists:orders,id',
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

        OrderAssign::create([
            'internal_user_id' => $internalUserId,
            'order_id' => $request->order_id,
            'status' => $status
        ]);

        Order::where('id', $request->order_id)->update([
            'status' => $status
        ]);

        return redirect()->back()->with('Success', 'Admin or SuperUser' .
            ($status == 'Assigned' ? 'Assigned' : 'Re-Assigned') . ' successfully!');
    }

    //Order Complete
    public function orderComplete(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id'
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        }

        //Update status in orders table
        Order::where('id', $request->order_id)->update([
            'status' => 'Completed'
        ]);

        //Update status in orderassign table
        OrderAssign::where('order_id', $request->order_id)->update([
            'status' => 'Completed'
        ]);

        // Update status in order task table
        $taskIds = OrderTask::where('order_id', $request->order_id)->pluck('id');

        OrderTask::whereIn('id', $taskIds)->update(['status' => 'Completed']);

        // Update status in order task assign table
        OrderTaskAssign::whereIn('order_task_id', $taskIds)->update(['status' => 'Completed']);

        return redirect()->back()->with('success', 'Order Completed Successfully');
    }


    //date and status filter wise order list
    public function filterOrdersList(Request $request)
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

        if ($user) {
            $userID = $user->id;

            $query = Order::with('lead.category', 'lead.material', 'lead.subcategory', 'lead.shade.shade', 'orderAssign')
                ->whereDate('created_at', $date);
                if (!empty($status) && $status !== 'All') {
                    $query->where('status', $status);
                }
               

            if ($role === 'Superuser') {
                $query->whereHas('orderAssign', function ($q) use ($userID) {
                    $q->where('internal_user_id', $userID);
                });
            }
            $orders = $query->orderBy('id', 'desc')->get();

            return view('admin.order.orders_list', compact('orders'));
        } else {
            return redirect()->route('admin.login')->withErrors(['message' => 'Please login first']);
        }
    }
}

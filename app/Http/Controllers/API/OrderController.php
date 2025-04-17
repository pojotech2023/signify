<?php

namespace App\Http\Controllers\API;

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
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'lead_id' => 'required|exists:aggregator_forms,id'
        ]);

        if ($validate->fails()) {
            return response()->json(['errors' => $validate->errors()], 422);
        }

        $order = Order::create(['lead_id' => $request->lead_id]);

        AggregatorForm::where('id', $request->lead_id)->update([
            'status' => 'Completed'
        ]);

        LeadAssign::where('lead_id', $request->lead_id)->update([
            'status' => 'Completed'
        ]);

        // Update status in lead task table
        $taskIds = LeadTask::where('lead_id', $request->lead_id)->pluck('id');

        LeadTask::whereIn('id', $taskIds)->update(['status' => 'Completed']);

        // Update status in lead task assign table
        LeadTaskAssign::whereIn('task_id', $taskIds)->update(['status' => 'Completed']);

        return response()->json([
            'response code' => 200,
            'message' => 'Order Created Successfully!.'
        ]);
    }

    public function index(Request $request)
    {
        $user = Auth::guard('api')->user();

        if (!$user) {
            return response()->json([
                'response code' => 401,
                'message' => 'Unauthorized. Please log in.'
            ]);
        }

        $userID = $user->id;
        $role   = $user->role->role_name;

        $status = $request->input('status');
        $date = $request->input('date', Carbon::now()->format('d-m-Y'));

        try {
            $parsedDate = Carbon::createFromFormat('d-m-Y', $date)->startOfDay();
        } catch (\Exception $e) {
            return response()->json([
                'response_code' => 422,
                'message' => 'Invalid date format. Use dd-mm-yyyy.'
            ]);
        }

        $query = Order::with('lead.category', 'lead.material', 'lead.subcategory', 'lead.shade.shade', 'orderAssign')
            ->whereDate('created_at', $parsedDate);

        if (!empty($status) && $status !== 'All') {
            $query->where('status', $status);
        }

        if ($role === 'Superuser') {
            $query->whereHas('orderAssign', function ($q) use ($userID) {
                $q->where('internal_user_id', $userID);
            });
        }
        $orders = $query->orderBy('id', 'desc')->get();

        $filteredOrders = $orders->map(function ($order) {
            return [
                'lead_id' => $order->lead_id,
                'id' => $order->id,
                'category_name' => $order->lead->category->category,
                'subcategory_name' => $order->lead->subcategory->sub_category,
                'material_name' => $order->lead->material->material_name,
                'location' => $order->lead->location,
                'mobile_no' => $order->lead->mobile_no,
                'shade_name' => $order->lead->shade->first()->shade->shade_name,
                'status' => $order->status
            ];
        });
        return response()->json([
            'response_code' => 200,
            'message' => 'Order Fetched successfully',
            'data' => $filteredOrders
        ]);
    }

    //Order Details
    public function show($id)
    {
        $order = Order::with('lead.category', 'lead.material', 'lead.subcategory', 'lead.shade.shade')->findOrFail($id);

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
        $data = [
            'id' => $order->id,
            'category' => optional($order->lead->category)->category,
            'sub_category' => optional($order->lead->subcategory)->sub_category,
            'material_name' => optional($order->lead->material)->material_name,
            'material_main_img' => optional($order->lead->material)->main_img,
            'sub_img1' => optional($order->lead->material)->sub_img1,
            'sub_img2' => optional($order->lead->material)->sub_img2,
            'sub_img3' => optional($order->lead->material)->sub_img3,
            'sub_img4' => optional($order->lead->material)->sub_img4,
            'shade' => $order->lead->shade->map(function ($shade) {
                return [
                    'selected_img' => $shade->selected_img,
                    'shade_name' => optional($shade->shade)->shade_name,
                ];
            }),
            'width' => $order->lead->width,
            'height' => $order->lead->height,
            'unit' => $order->lead->unit,
            'location' => $order->lead->location,
            'quantity' => $order->lead->quantity,
            'design_service_need' => $order->lead->design_service_need,
            'email_id' => $order->lead->email_id,
            'mobile_no' => $order->lead->mobile_no,
            'site_image' => $order->lead->site_image,
            'design_attachment' => $order->lead->design_attachment,
            'reference_image' => $order->lead->reference_image,
            'status' => $order->status,
            'created_at' => $order->created_at,
            'assignEnabled' => $assignEnabled,
            'reassignEnabled' =>  $reassignEnabled,
            'assignedUserName' => $assignedUserName,
        ];

        return response()->json([
            'response code' => 200,
            'message' => 'Order Details Fetched successfully',
            'data' => $data
        ]);
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
            return response()->json(['errors' => $validate->errors()], 422);
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

        return response()->json([
            'response code' => 200,
            'message' => 'Order Assigned Or Re-assigned Successfully!.'
        ]);
    }

    //Order Complete
    public function orderComplete(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id'
        ]);
        if ($validate->fails()) {
            return response()->json(['errors' => $validate->errors()], 422);
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

        return response()->json([
            'response code' => 200,
            'message' => 'Order Completed Successfully!.',
        ]);
    }
}

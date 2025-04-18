<?php

use App\Http\Controllers\API\AggregatorFormController;
use App\Http\Controllers\API\AttendanceController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\LeadController;
use App\Http\Controllers\API\LeadTaskController;
use App\Http\Controllers\API\MaterialController;
use App\Http\Controllers\API\TaskListController;
use App\Http\Controllers\API\UserCreationController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\CustomerProfileController;
use App\Http\Controllers\API\DeviceTokenController;
use App\Http\Controllers\API\SubcategoryController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\OrderTaskController;
use App\Http\Controllers\API\JobController;
use App\Http\Controllers\API\JobTaskController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\VendorController;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use SebastianBergmann\CodeCoverage\Report\Html\CustomCssFile;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your aggregator. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
  return $request->user();
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::get('/get-categories', [AggregatorFormController::class, 'getCategories']);
Route::get('/get-subcategories/{category_id}', [AggregatorFormController::class, 'getSubcategories']);
Route::get('/get-materials/{subcategory_id}', [AggregatorFormController::class, 'getMaterials']);
Route::get('/get-shades/{material_id}', [AggregatorFormController::class, 'getShades']);

//test
Route::post('/send-notification', [NotificationController::class, 'send']);

Route::middleware('auth:sanctum')->group(function () {

  //Device Token Store
  Route::post('/save-device-token', [DeviceTokenController::class, 'store']);

  //UserForm
  Route::post('/aggregator_form', [AggregatorFormController::class, 'store']);

  //admin

  //Usercreation
  Route::get('/internal-user/list', [UserCreationController::class, 'index']);
  Route::get('/usertype-list', [UserCreationController::class, 'role']);
  Route::post('/internal-user/creation', [UserCreationController::class, 'store']);
  Route::patch('/internal-user/update/{id}', [UserCreationController::class, 'update']);
  Route::delete('/internal-user/delete/{id}', [UserCreationController::class, 'delete']);

  //Category
  Route::get('/category', [CategoryController::class, 'index']);
  Route::post('/category', [CategoryController::class, 'store']);
  Route::patch('/category-update/{id}', [CategoryController::class, 'update']);
  Route::delete('/category-delete/{id}', [CategoryController::class, 'delete']);

  //SubCategory
  Route::get('/subcategory', [SubCategoryController::class, 'index']);
  Route::post('/subcategory', [SubCategoryController::class, 'store']);
  Route::patch('/subcategory-update/{id}', [SubCategoryController::class, 'update']);
  Route::delete('/subcategory-delete/{id}', [SubCategoryController::class, 'delete']);

  //Material and Shades
  Route::get('/material', [MaterialController::class, 'index']);
  Route::post('/material-add', [MaterialController::class, 'store']);
  Route::post('/material-update/{id}', [MaterialController::class, 'update']);
  Route::delete('/material-delete/{id}', [MaterialController::class, 'delete']);
  Route::post('/material/delete-subimage', [MaterialController::class, 'deleteSubImage']);
  Route::post('/shade/delete-image', [MaterialController::class, 'deleteImage']);

  //All, Admin and Superuser List
  Route::get('/all-internaluser', [UserCreationController::class, 'internalUserAll']);
  Route::get('/admin-superuser', [UserCreationController::class, 'adminSuperuserList']);

  //Lead
  Route::post('/leads', [LeadController::class, 'index']);
  Route::get('/leads-details/{id}', [LeadController::class, 'show']);
  Route::post('/assign/admin-superuser', [LeadController::class, 'leadAssign']);

  //Lead Task
  Route::post('/task-create', [LeadTaskController::class, 'store']);
  Route::post('/leads/{lead_id}/tasks', [LeadTaskController::class, 'showLeadTasks']);
  Route::get('/task-details/{task_id}', [LeadTaskController::class, 'show']);
  Route::post('/task-update/{id}', [LeadTaskController::class, 'update']);
  Route::post('task-executive/create', [LeadTaskController::class, 'executiveStoreTask']);
  Route::post('/task-executive/update/{id}', [LeadTaskController::class, 'executiveUpdateTask']);

  //Orders
  Route::post('/order', [OrderController::class, 'store']);
  Route::post('/orders', [OrderController::class, 'index']);
  Route::get('/orders-details/{id}', [OrderController::class, 'show']);
  Route::post('/order/assign', [OrderController::class, 'orderAssign']);
  Route::post('/order-complete', [OrderController::class, 'orderComplete']);

  //Order Task
  Route::post('/order/task-create', [OrderTaskController::class, 'store']);
  Route::post('/orders/{order_id}/tasks', [OrderTaskController::class, 'showOrderTasks']);
  Route::get('/order/task-details/{task_id}', [OrderTaskController::class, 'show']);
  Route::post('/order/task-update/{id}', [OrderTaskController::class, 'update']);
  Route::post('order/task-executive/create', [OrderTaskController::class, 'executiveStoreTask']);
  Route::post('/order/task-executive/update/{id}', [OrderTaskController::class, 'executiveUpdateTask']);

  //Jobs
  Route::get('/department-list', [JobController::class, 'departmentList']);
  Route::post('/job', [JobController::class, 'store']);
  Route::post('/jobs', [JobController::class, 'index']);
  Route::get('/jobs-details/{id}', [JobController::class, 'show']);
  Route::patch('/job-update/{id}', [JobController::class, 'update']);

  //Job Task
  Route::post('job/task-create', [JobTaskController::class, 'store']);
  Route::post('/jobs/{job_id}/tasks', [JobTaskController::class, 'showJobTasks']);
  Route::get('/job/task-details/{task_id}', [JobTaskController::class, 'show']);
  Route::post('/job/task-update/{id}', [JobTaskController::class, 'update']);
  Route::post('job/task-executive/create', [JobTaskController::class, 'executiveStoreTask']);
  Route::post('/job/task-executive/update/{id}', [JobTaskController::class, 'executiveUpdateTask']);


  //Executive Task List
  Route::get('/tasks', [TaskListController::class, 'index']);

  //Vendors
  Route::get('/vendor', [VendorController::class, 'index']);
  Route::post('/vendor', [VendorController::class, 'store']);
  Route::patch('/vendor-update/{id}', [VendorController::class, 'update']);
  Route::delete('/vendor-delete/{id}', [VendorController::class, 'delete']);
  Route::post('/vendors/search', [VendorController::class, 'search']);

  //Attendances
  Route::post('/attendance', [AttendanceController::class, 'store']);
  Route::get('/attendance/details', [AttendanceController::class, 'show']);

  //Customer Profile
  Route::get('profile', [CustomerProfileController::class, 'index']);
  Route::patch('profile', [CustomerProfileController::class, 'update']);
  Route::get('logged/profile', [CustomerProfileController::class, 'show']);
});

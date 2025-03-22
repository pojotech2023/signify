<?php

use App\Http\Controllers\Admin\AdminAggregatorFormController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\JobController;
use App\Http\Controllers\Admin\JobTaskController;
use App\Http\Controllers\Admin\LeadsController;
use App\Http\Controllers\Admin\LeadTaskController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\MaterialController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\OrderTaskController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\Admin\UserCreationController;
use App\Http\Controllers\WEB\AggregatorFormController;
use App\Models\AggregatorForm;
use App\Models\Login;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\WEB\AuthController;

// ----------------------------------------Web----------------------------------------------
Route::get('/', function () {
    return view('index');
})->name('home');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/product', function () {
    return view('product');
})->name('product');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::get('/form', [AggregatorFormController::class, 'index'])->name('form');
Route::post('/form', [AggregatorFormController::class, 'store'])->name('aggregatorform');

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/otp_verification', function () {
    return view('otp_verification');
})->name('otp.page');

Route::post('/login', function () {
    return redirect()->route('otp.page');
})->name('login.submit');


//------------------------------------------ Admin-----------------------------------------------------
Route::prefix('admin')->group(function () {

    //Common Routes for All Roles

    // Login and Logout
    Route::get('/login', [LoginController::class, 'showLoginForm']);
    Route::post('/login', [LoginController::class, 'adminLogin'])->name('admin.login');
    Route::post('/logout', [LoginController::class, 'adminLogout'])->name('admin.logout');

    //Dashboard
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Common Routes for Admin & Superuser
    Route::middleware(['checkUserRole:Admin,Superuser'])->group(function () {

        //Aggregator Form

        Route::get('/aggregator-form/{id?}', [AdminAggregatorFormController::class, 'index'])->name('aggregator-form');
        Route::post('/aggregator-form', [AdminAggregatorFormController::class, 'store'])->name('aggregator-store');

        Route::get('/aggregator-list', function () {
            return view('admin.aggregator_list');
        })->name('aggregator-list');

        Route::get('/material', [MaterialController::class, 'index'])->name('material-list');
        Route::patch('/material-update/{id}', [MaterialController::class, 'update'])->name('material-update');
        Route::delete('/material-delete/{id}', [MaterialController::class, 'delete'])->name('material-delete');

        Route::post('/material/delete-image', [MaterialController::class, 'deleteMaterialsubImage'])->name('material.deleteImage');
        Route::post('/shade/delete-image', [MaterialController::class, 'deleteShadeImage'])->name('shade.deleteImage');

        //Category

        Route::get('/category', [CategoryController::class, 'index'])->name('category-list');
        Route::post('/category', [CategoryController::class, 'store'])->name('category-store');
        Route::post('/category-update', [CategoryController::class, 'update'])->name('category-update');
        Route::delete('/category-delete/{id}', [CategoryController::class, 'delete'])->name('category-delete');

        //SubCategory

        Route::get('/subcategory-list', [SubCategoryController::class, 'index'])->name('subcategory-list');
        Route::post('/subcategory', [SubCategoryController::class, 'store'])->name('subcategory-store');
        Route::post('/subcategory-update', [SubCategoryController::class, 'update'])->name('subcategory-update');
        Route::delete('/subcategory-delete/{id}', [SubCategoryController::class, 'delete'])->name('subcategory-delete');

        //Leads

        Route::get('/leads', [LeadsController::class, 'index'])->name('leads-list');
        Route::get('/leads-details/{id}', [LeadsController::class, 'show'])->name('lead-details');
        Route::post('/assign/admin-superuser', [LeadsController::class, 'leadAssign'])->name('lead-assign');
        Route::get('/leads-list/filter', [LeadsController::class, 'getFilteredLeads'])->name('filter-leads-list');

        //Orders

        Route::post('/order', [OrderController::class, 'store'])->name('order-store');
        Route::get('/orders', [OrderController::class, 'index'])->name('orders-list');
        Route::get('/orders-details/{id}', [OrderController::class, 'show'])->name('order-details');
        Route::post('/order/assign', [OrderController::class, 'orderAssign'])->name('order-assign');
        Route::post('/order-complete', [OrderController::class, 'orderComplete'])->name('order-complete');

        //Jobs
        Route::get('/job/form/{id?}', [JobController::class, 'getJobForm'])->name('jobcreation-form');
        Route::post('/job', [JobController::class, 'store'])->name('job-store');
        Route::get('/jobs', [JobController::class, 'index'])->name('jobs-list');
        Route::get('/jobs-details/{id}', [JobController::class, 'show'])->name('job-details');
        Route::patch('/job-update/{id}', [JobController::class, 'update'])->name('job-update');
    });


    // Admin-Only Routes
    Route::middleware(['checkUserRole:Admin'])->group(function () {

        //User Creation
        Route::get('/internal-user/form/{id?}', [UserCreationController::class, 'getUserForm'])->name('usercreation-form');
        Route::post('/internal-user/creation', [UserCreationController::class, 'store'])->name('usercreation-store');
        Route::get('/internal-user/list', [UserCreationController::class, 'index'])->name('usercreation-list');
        Route::patch('/internal-user/update/{id}', [UserCreationController::class, 'update'])->name('usercreation-update');
        Route::delete('/internal-user/delete/{id}', [UserCreationController::class, 'delete'])->name('usercreation-delete');
    });

    //Common Routes for All Roles

    // Lead Task
    Route::get('task-form/{id}', [LeadTaskController::class, 'getTaskForm'])->name('task-form');
    Route::post('task-create', [LeadTaskController::class, 'store'])->name('task-create');
    Route::get('/task-details/{task_id}', [LeadTaskController::class, 'show'])->name('task-details');
    Route::post('task-executive/create', [LeadTaskController::class, 'executiveStoreTask'])->name('task-executive');
    Route::get('/leads/{lead_id}/tasks', [LeadTaskController::class, 'showLeadTasks'])->name('leads-tasks');
    Route::post('/executive/change-status', [LeadTaskController::class, 'changeStatus'])->name('change-status');
    Route::patch('/task-update/{id}', [LeadTaskController::class, 'update'])->name('task-update');
    Route::patch('/task-executive/update/{id}', [LeadTaskController::class, 'executiveUpdateTask'])->name('task-executive.update');

    //Order Task
    Route::get('order/task-form/{id}', [OrderTaskController::class, 'getOrderTaskForm'])->name('order-task-form');
    Route::post('order/task-create', [OrderTaskController::class, 'store'])->name('order-task-create');
    Route::get('/order/task-details/{task_id}', [OrderTaskController::class, 'show'])->name('order-task-details');
    Route::post('order/task-executive/create', [OrderTaskController::class, 'executiveStoreTask'])->name('order-task-executive');
    Route::get('/orders/{order_id}/tasks', [OrderTaskController::class, 'showOrderTasks'])->name('orders-tasks');
    Route::post('/order/executive/update-status', [OrderTaskController::class, 'changeStatus'])->name('update-status');
    Route::patch('/order/task-update/{id}', [OrderTaskController::class, 'update'])->name('order-task-update');
    Route::patch('/order/task-executive/update/{id}', [OrderTaskController::class, 'executiveUpdateTask'])->name('order-task-executive.update');

    //Job Task
    Route::get('job/task-form/{id}', [JobTaskController::class, 'getJobTaskForm'])->name('job-task-form');
    Route::post('job/task-create', [JobTaskController::class, 'store'])->name('job-task-create');
    Route::get('/job/task-details/{task_id}', [JobTaskController::class, 'show'])->name('job-task-details');
    Route::post('job/task-executive/create', [JobTaskController::class, 'executiveStoreTask'])->name('job-task-executive');
    Route::get('/jobs/{job_id}/tasks', [JobTaskController::class, 'showJobTasks'])->name('jobs-tasks');
    Route::post('/job/executive/update-status', [JobTaskController::class, 'changeStatus'])->name('job-update-status');
    Route::patch('/job/task-update/{id}', [JobTaskController::class, 'update'])->name('job-task-update');
    Route::patch('/job/task-executive/update/{id}', [JobTaskController::class, 'executiveUpdateTask'])->name('job-task-executive.update');


    //Executive or Assigned User Lead and Order Task List
    Route::get('/tasks', [LeadTaskController::class, 'index'])->name('task-list');
});

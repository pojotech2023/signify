<?php

use App\Http\Controllers\Admin\AdminAggregatorFormController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\LeadsController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\MaterialController;
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

    // Login and Logout
    Route::get('/login', [LoginController::class, 'showLoginForm']);
    Route::post('/login', [LoginController::class, 'adminLogin'])->name('admin.login');
    Route::post('/logout', [LoginController::class, 'adminLogout'])->name('admin.logout');
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
        Route::post('/assign/admin-superuser', [LeadsController::class, 'assignAdminSuperuser'])->name('assign-admin-superuser');
        Route::get('/leads-list/filter', [LeadsController::class, 'getFilteredLeads'])->name('filter-leads-list');

    });

    // Admin-Only Routes
    Route::middleware(['checkUserRole:Admin'])->group(function () {

        //User Creation

        Route::get('/user-creation/form', [UserCreationController::class, 'index'])->name('usercreation-form');
        Route::post('/user-creation', [UserCreationController::class, 'store'])->name('user-creation');
    });

    // Executive Routes
    // Route::middleware(['checkUserRole:Accounts,HR,PR,R&D'])->group(function () {

        Route::get('task-form/{id}', [TaskController::class, 'getTaskForm'])->name('task-form');
        Route::post('task-create', [TaskController::class, 'store'])->name('task-create');
        Route::get('/tasks', [TaskController::class, 'index'])->name('task-list');
        Route::get('/task-details/{task_id}', [TaskController::class, 'show'])->name('task-details');
        Route::post('task-executive/create', [TaskController::class, 'executiveStoreTask'])->name('task-executive');
        Route::post('/reassgin/task', [TaskController::class, 'reassignExecutive'])->name('reassign-executive');
        Route::get('/leads/{lead_id}/tasks', [TaskController::class, 'showLeadTasks'])->name('leads-tasks');
    // });
});

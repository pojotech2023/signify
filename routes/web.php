<?php

use App\Http\Controllers\Admin\AdminAggregatorFormController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\MaterialController;
use App\Http\Controllers\Admin\SubCategoryController;
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
   
    Route::get('/dashboard', function () { return view('admin.dashboard');})->name('admin.dashboard');

    Route::get('/aggregator-form', [AdminAggregatorFormController::class, 'index'])->name('aggregator-form');
    Route::get('/aggregator-list', function(){ return view('admin.aggregator_list');})->name('aggregator-list');
    Route::post('/aggregator-form', [AdminAggregatorFormController::class, 'store'])->name('aggregator-store');
   
    Route::get('/category', [CategoryController::class, 'index'])->name('category-list');
    Route::post('/category', [CategoryController::class, 'store'])->name('category-store');
    Route::post('/category-update', [CategoryController::class, 'update'])->name('category-update');
    Route::delete('/category-delete/{id}', [CategoryController::class, 'delete'])->name('category-delete');

    Route::get('/subcategory-list', [SubCategoryController::class, 'index'])->name('subcategory-list');
    Route::post('/subcategory', [SubCategoryController::class, 'store'])->name('subcategory-store');
    Route::post('/subcategory-update', [SubCategoryController::class, 'update'])->name('subcategory-update');
    Route::delete('/subcategory-delete/{id}', [SubCategoryController::class, 'delete'])->name('subcategory-delete');

    Route::get('/material', [MaterialController::class, 'index'])->name('material-list');
    Route::delete('/material-delete/{id}', [MaterialController::class, 'delete'])->name('material-delete');
});

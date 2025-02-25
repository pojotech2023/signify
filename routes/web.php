<?php

use App\Http\Controllers\WEB\AggregatorFormController;
use App\Models\AggregatorForm;
use App\Models\Login;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

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

Route::get('/aggregator_form', function () {
    return view('aggregator_form');
})->name('aggregator');


Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/form', [AggregatorFormController::class, 'store'])->name('aggregator_form');
<?php

use App\Http\Controllers\API\AggregatorFormController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\LeadController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

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

Route::get('/get-categories', [AggregatorFormController::class, 'getCategories']);
Route::get('/get-subcategories/{category_id}', [AggregatorFormController::class, 'getSubcategories']);
Route::get('/get-materials/{subcategory_id}', [AggregatorFormController::class, 'getMaterials']);
Route::get('/get-shades/{material_id}', [AggregatorFormController::class, 'getShades']);
Route::post('/aggregator_form', [AggregatorFormController::class, 'store']);

//admin

//Lead
Route::middleware('auth:sanctum')->group(function() {

  Route::get('/leads', [LeadController::class, 'index']);
  Route::get('/leads-details/{id}', [LeadController::class, 'show']);
  Route::post('/assign/admin-superuser', [LeadController::class, 'leadAssign']);

});



<?php

use App\Http\Controllers\API\AggregatorFormController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\LeadController;
use App\Http\Controllers\API\LeadTaskController;
use App\Http\Controllers\API\MaterialController;
use App\Http\Controllers\API\UserCreationController;
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
Route::post('/logout', [AuthController::class, 'logout']);

Route::get('/get-categories', [AggregatorFormController::class, 'getCategories']);
Route::get('/get-subcategories/{category_id}', [AggregatorFormController::class, 'getSubcategories']);
Route::get('/get-materials/{subcategory_id}', [AggregatorFormController::class, 'getMaterials']);
Route::get('/get-shades/{material_id}', [AggregatorFormController::class, 'getShades']);
Route::post('/aggregator_form', [AggregatorFormController::class, 'store']);

//admin
Route::middleware('auth:sanctum')->group(function () {

  //Lead
  Route::get('/leads', [LeadController::class, 'index']);
  Route::get('/leads-details/{id}', [LeadController::class, 'show']);
  Route::post('/assign/admin-superuser', [LeadController::class, 'leadAssign']);

  //Lead Task
  Route::post('/task-create', [LeadTaskController::class, 'store']);
  Route::get('/leads/{lead_id}/tasks', [LeadTaskController::class, 'showLeadTasks']);
  Route::get('/task-details/{task_id}', [LeadTaskController::class, 'show']);

  //Usercreation
  Route::get('/internal-user/list', [UserCreationController::class, 'index']);
  Route::get('/usertype-list', [UserCreationController::class, 'role']);
  Route::post('/internal-user/creation', [UserCreationController::class, 'store']);
  Route::patch('/internal-user/update/{id}', [UserCreationController::class, 'update']);
  Route::delete('/internal-user/delete/{id}', [UserCreationController::class, 'delete']);

  //Material and Shades
  Route::get('/material', [MaterialController::class, 'index']);
  Route::post('/material-add', [MaterialController::class, 'store']);
  Route::post('/material-update/{id}', [MaterialController::class, 'update']);
  Route::delete('/material-delete/{id}', [MaterialController::class, 'delete']);
  Route::post('/material/delete-subimage', [MaterialController::class, 'deleteSubImage']);
  Route::post('/shade/delete-image', [MaterialController::class, 'deleteImage']);

});

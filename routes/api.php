<?php

use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/getDepartments',[DepartmentController::class, 'index']);
Route::post('/storeDepartment',[DepartmentController::class, 'store']);
Route::post('/updateDepartment/{id}',[DepartmentController::class, 'update']);
Route::post('/deleteDepartment/{id}',[DepartmentController::class, 'destroy']);


Route::get('/getPayments',[PaymentController::class, 'index']);
Route::get('/getPaids',[PaymentController::class, 'record']);
Route::post('/storePayment',[PaymentController::class, 'store']);
Route::post('/deletePayment/{id}',[PaymentController::class, 'destroy']);
Route::post('/updatePayment/{id}',[PaymentController::class, 'update']);
Route::post('/paymentForm/{id}',[PaymentController::class, 'form']);
Route::post('/paidPayment/{id}',[PaymentController::class, 'paid']);
Route::post('/reportPayment',[PaymentController::class, 'report']);
Route::post('/reportPaymentbydept',[PaymentController::class, 'reportbydept']);

Route::get('/getUsers',[UserController::class, 'index']);
Route::get('/getUsers/{id}',[UserController::class, 'show']);
Route::post('/storeUser',[UserController::class, 'store']);
Route::post('/storePhoto',[UserController::class, 'storephoto']);
Route::post('/updateUser/{id}',[UserController::class, 'update']);
Route::post('deleteUser/{id}',[UserController::class, 'destroy']);
Route::post('updatepassword',[UserController::class, 'updatepassword']);
Route::post('updatename',[UserController::class, 'updatename']);







<?php

use App\Http\Controllers\adsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\VerificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group([

    'middleware' => 'api',
    // 'prefix' => 'auth'

], function ($router) {

    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('signup', [AuthController::class, 'signup']);

    // Verify email
    Route::get('/email/verify/{id}/{hash}', [VerificationController::class, '__invoke'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

    // Resend link to verify email
    Route::post('/email/verify/resend', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
    })->middleware(['auth:api', 'throttle:6,1'])->name('verification.send');

    Route::post('insertAds', [adsController::class, 'insert']);
    Route::post('updateAds', [adsController::class, 'update']);
    Route::get('deleteAds', [adsController::class, 'delete']);

    Route::post('changeOrderStatus', [OrdersController::class, 'changeStatus']);

});

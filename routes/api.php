<?php

use App\Http\Controllers\adsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\UsersController;
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

    // Authentication
    Route::post('login',  [AuthController::class, 'login' ]);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('signup', [AuthController::class, 'signup']);

    // users
    Route::get('users',        [UsersController::class, 'index'   ]);
    Route::get('user/{id}',    [UsersController::class, 'show'    ]);
    Route::get('me',      [UsersController::class, 'personal'])->middleware(['auth:api']);
    Route::post('me/update', [UsersController::class, 'update'  ])->middleware(['auth:api']);

    // Verify email
    Route::get('/email/verify/{id}/{hash}', [VerificationController::class, '__invoke'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    // Resend link to verify email
    Route::post('/email/verify/resend', [VerificationController::class, 'resendVerification'])
        ->middleware(['auth:api', 'throttle:6,1'])
        ->name('verification.send');

    // Advertisements
    Route::post('ads/insert', [adsController::class, 'insert']);
    Route::post('ads/update', [adsController::class, 'update']);
    Route::get('ads/delete',  [adsController::class, 'delete']);
    Route::get('ads',         [adsController::class, 'index' ]);
    Route::get('ads/{id}',    [adsController::class, 'show'  ]);

    // Orders
    Route::post('order/changeStatus', [OrdersController::class, 'changeStatus']);
    Route::get('order',               [OrdersController::class, 'index'       ]);
    Route::get('order/{id}',          [OrdersController::class, 'show'        ]);

    // Categories
    Route::post('category/create', [CategoriesController::class, 'store'  ]);
    Route::post('category/update', [CategoriesController::class, 'update' ]);
    Route::get('category/delete',  [CategoriesController::class, 'destroy']);
    Route::get('category',         [CategoriesController::class, 'index'  ]);
    Route::get('category/{id}',    [CategoriesController::class, 'show'   ]);

});

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
    Route::get('users',     [UsersController::class, 'getAll'  ]);
    Route::get('user/{id}', [UsersController::class, 'get'     ]);
    Route::get('me',        [UsersController::class, 'personal'])->middleware(['auth:api']);
    Route::post('me/edit',  [UsersController::class, 'edit'    ])->middleware(['auth:api']);

    // Email verification
    Route::get('/email/verify/{id}/{token}', [VerificationController::class, 'verify']);
    Route::post('/email/verify/resend',      [VerificationController::class, 'resendVerification']);

    // Advertisements
    Route::get('ads',            [adsController::class, 'getAll']);
    Route::get('ads/{id}',       [adsController::class, 'get'   ]);
    Route::get('ads/delete/{id}',[adsController::class, 'delete']);
    Route::post('ads/add',       [adsController::class, 'add'   ]);
    Route::post('ads/edit',      [adsController::class, 'edit'  ]);
    // Route::post('ads/Image/edit',[ImagesController::class,'edit']);

    // Orders
    Route::post('order/edit', [OrdersController::class, 'edit'  ]);
    Route::get('order',       [OrdersController::class, 'getAll']);
    Route::get('order/{id}',  [OrdersController::class, 'get'   ]);

    // Categories
    Route::post('category/add',       [CategoriesController::class, 'add'   ]);
    Route::post('category/edit',      [CategoriesController::class, 'edit'  ]);
    Route::get('category/delete/{id}',[CategoriesController::class, 'delete']);
    Route::get('category',            [CategoriesController::class, 'getAll']);
    Route::get('category/{id}',       [CategoriesController::class, 'get'   ]);

});

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserLikesController;
use App\Http\Controllers\Api\UserOrdersController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\UserCommentsController;
use App\Http\Controllers\Api\ProductLikesController;
use App\Http\Controllers\Api\PaymentOrdersController;
use App\Http\Controllers\Api\ProductOrdersController;
use App\Http\Controllers\Api\ProductCommentsController;
use App\Http\Controllers\ProductController as ControllersProductController;

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

Route::post('/login', [AuthController::class, 'login'])->name('api.login');
Route::get('/events', [ProductController::class, 'event']);
Route::get('/destinations', [ProductController::class, 'destination']);
Route::get('/detail/{product}', [ProductController::class, 'show']);

Route::middleware('auth:sanctum')
    ->get('/user', function (Request $request) {
        return $request->user();
    })
    ->name('api.user');

Route::name('api.')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::apiResource('roles', RoleController::class);
        Route::apiResource('permissions', PermissionController::class);

        Route::apiResource('comments', CommentController::class);

        Route::apiResource('likes', LikeController::class);

        Route::apiResource('payments', PaymentController::class);

        // Payment Orders
        Route::get('/payments/{payment}/orders', [
            PaymentOrdersController::class,
            'index',
        ])->name('payments.orders.index');
        Route::post('/payments/{payment}/orders', [
            PaymentOrdersController::class,
            'store',
        ])->name('payments.orders.store');

        Route::apiResource('users', UserController::class);

        // User Comments
        Route::get('/users/{user}/comments', [
            UserCommentsController::class,
            'index',
        ])->name('users.comments.index');
        Route::post('/users/{user}/comments', [
            UserCommentsController::class,
            'store',
        ])->name('users.comments.store');

        // User Likes
        Route::get('/users/{user}/likes', [
            UserLikesController::class,
            'index',
        ])->name('users.likes.index');
        Route::post('/users/{user}/likes', [
            UserLikesController::class,
            'store',
        ])->name('users.likes.store');

        // User Orders
        Route::get('/users/{user}/orders', [
            UserOrdersController::class,
            'index',
        ])->name('users.orders.index');
        Route::post('/users/{user}/orders', [
            UserOrdersController::class,
            'store',
        ])->name('users.orders.store');

        Route::apiResource('orders', OrderController::class);

        Route::apiResource('products', ProductController::class);

        // Product Comments
        Route::get('/products/{product}/comments', [
            ProductCommentsController::class,
            'index',
        ])->name('products.comments.index');
        Route::post('/products/{product}/comments', [
            ProductCommentsController::class,
            'store',
        ])->name('products.comments.store');

        // Product Likes
        Route::get('/products/{product}/likes', [
            ProductLikesController::class,
            'index',
        ])->name('products.likes.index');
        Route::post('/products/{product}/likes', [
            ProductLikesController::class,
            'store',
        ])->name('products.likes.store');

        // Product Orders
        Route::get('/products/{product}/orders', [
            ProductOrdersController::class,
            'index',
        ])->name('products.orders.index');
        Route::post('/products/{product}/orders', [
            ProductOrdersController::class,
            'store',
        ])->name('products.orders.store');
    });

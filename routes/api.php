<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\ApiCartController;
use App\Http\Controllers\Api\ApiAuthController;

use App\Http\Controllers\Admin\RoleController;

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
Auth::routes();
Route::get('home', [ApiController::class, 'index'])->name('api.home');
Route::get('product/{code}', [ApiController::class, 'productDetail'])->name('api.product.detail');
Route::get('product/same_category/{cat_id}', [ApiController::class, 'sameCategory'])->name('api.product.same_category');
Route::get('cat_menu', [ApiController::class, 'getCatMenu'])->name('api.getCatMenu');
Route::get('product', [ApiController::class, 'listProduct'])->name('api.product.productAll');


Route::post('register', [ApiAuthController::class, 'register'])->name('api.register');
Route::post('login', [ApiAuthController::class, 'login'])->name('api.login');

Route::middleware('token.verify')->group(function () {
    Route::get('logout', [ApiAuthController::class, 'logout'])->name('api.logout');
    Route::get('user-info', [ApiAuthController::class, 'getUserInfo'])->name('api.getUserInfo');
    Route::post('cart/add', [ApiCartController::class, 'addToCart'])->name('api.addToCart');
    Route::get('cart', [ApiCartController::class, 'getCart'])->name('api.getCart');
});
Route::get('listRoute', [RoleController::class, 'listRoute'])->name('.listRoute');


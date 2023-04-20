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
Route::get('schools', [ApiController::class, 'getAllSchool'])->name('api.schools');
Route::get('school/{school_code}', [ApiController::class, 'getSchool'])->name('api.school');
Route::get('sector/{sector_id}', [ApiController::class, 'getSector'])->name('api.sector');
Route::get('page/{slug}', [ApiController::class, 'getPage'])->name('api.page.getPage');
Route::get('comment/{code}', [ApiController::class, 'getAllComment'])->name('api.comment.getAllComment');
Route::get('areas', [ApiController::class, 'getAllArea'])->name('api.area.getAllArea');


Route::post('register', [ApiAuthController::class, 'register'])->name('api.register');
Route::post('login', [ApiAuthController::class, 'login'])->name('api.login');

Route::middleware('token.verify')->group(function () {
    Route::get('logout', [ApiAuthController::class, 'logout'])->name('api.logout');
    Route::get('user_info', [ApiAuthController::class, 'getUserInfo'])->name('api.getUserInfo');
    Route::post('comment/add', [ApiController::class, 'addComment'])->name('api.addComment');
});
Route::get('listRoute', [RoleController::class, 'listRoute'])->name('.listRoute');


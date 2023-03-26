<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SchoolController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\SchoolTypeController;
use App\Http\Controllers\Admin\SectorController;
use App\Http\Controllers\Admin\MajorController;

use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminBrandController;
use App\Http\Controllers\Admin\AdminFeedbackController;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//Nhóm route xử lý trên addmin
Route::middleware('auth')->group(function () {
    Route::group(['prefix'=>'admin','as'=>'admin'],function () {
        Route::get('/', [DashboardController::class, 'show'])->name('.dashboard');
        Route::get('dashboard', [DashboardController::class,'show'])->name('.dashboard');
        Route::group(['prefix'=>'role', 'as'=> '.role'],function () {
            Route::get('/', [RoleController::class, 'index'])->name('.index');
            Route::get('create', [RoleController::class, 'create'])->name('.create');
            Route::post('store', [RoleController::class, 'store'])->name('.store');
        });
        Route::group(['prefix'=>'school', 'as'=> '.school'],function () {
            Route::get('/', [SchoolController::class, 'index'])->name('.index');
            Route::get('list', [SchoolController::class, 'index'])->name('.index');
            Route::get('list/{status}', [SchoolController::class, 'index'])->name('.status');
            Route::get('create', [SchoolController::class, 'create'])->name('.create');
            Route::post('store', [SchoolController::class, 'store'])->name('.store');
            Route::post('import', [SchoolController::class, 'import'])->name('.import');
            Route::post('export', [SchoolController::class, 'excelExport'])->name('.export');
            Route::get('action', [SchoolController::class, 'action'])->name('.action');
            Route::get('edit/{id}', [SchoolController::class, 'edit'])->name('.edit');
            Route::post('update/{id}', [SchoolController::class, 'update'])->name('.update');
            Route::get('remove/{id}', [SchoolController::class, 'remove'])->name('.remove');
            Route::get('restore/{id}', [SchoolController::class, 'restore'])->name('.restore');
            Route::get('delete/{id}', [SchoolController::class, 'delete'])->name('.delete');
        });
        Route::group(['prefix'=>'school_type', 'as'=> '.type'],function () {
            Route::get('/', [SchoolTypeController::class, 'index'])->name('.index');
            Route::post('store', [SchoolTypeController::class, 'store'])->name('.store');
            Route::get('delete/{id}', [SchoolTypeController::class, 'delete'])->name('.delete');
            Route::get('edit', [SchoolTypeController::class, 'edit'])->name('.edit');
            Route::get('remove/{id}', [SchoolTypeController::class, 'remove'])->name('.remove');
            Route::get('restore/{id}', [SchoolTypeController::class, 'restore'])->name('.restore');
            Route::post('update', [SchoolTypeController::class, 'update'])->name('.update');
        });

        Route::group(['prefix'=>'sector', 'as'=> '.sector'],function () {
            Route::get('/', [SectorController::class, 'index'])->name('.index');
            Route::post('create', [SectorController::class, 'create'])->name('.create');
            Route::post('store', [SectorController::class, 'store'])->name('.store');
            Route::get('delete/{id}', [SectorController::class, 'delete'])->name('.delete');
            Route::get('edit', [SectorController::class, 'edit'])->name('.edit');
            Route::get('remove/{id}', [SectorController::class, 'remove'])->name('.remove');
            Route::get('restore/{id}', [SectorController::class, 'restore'])->name('.restore');
            Route::post('update', [SectorController::class, 'update'])->name('.update');
        });
        Route::group(['prefix'=>'major', 'as'=> '.major'],function () {
            Route::get('/', [MajorController::class, 'index'])->name('.index');
            Route::get('{status}', [MajorController::class, 'index'])->name('.status');
            Route::post('create', [MajorController::class, 'create'])->name('.create');
            Route::post('store', [MajorController::class, 'store'])->name('.store');
            Route::post('import', [MajorController::class, 'importExcel'])->name('.import');
            Route::post('export', [MajorController::class, 'excelExport'])->name('.export');
            Route::get('delete/{id}', [MajorController::class, 'delete'])->name('.delete');
            Route::get('edit', [MajorController::class, 'edit'])->name('.edit');
            Route::get('remove/{id}', [MajorController::class, 'remove'])->name('.remove');
            Route::get('restore/{id}', [MajorController::class, 'restore'])->name('.restore');
            Route::post('update', [MajorController::class, 'update'])->name('.update');
            Route::get('action', [MajorController::class, 'action'])->name('.action');
        });
        Route::group(['prefix'=>'product', 'as'=> '.product'],function () {
            //Các route categoryproductController
            Route::group(['prefix'=>'cat', 'as'=> '.cat'],function () {
                Route::get('/', [AdminCategoryProductController::class, 'index'])->name('.index');
                Route::get('list', [AdminCategoryProductController::class, 'index'])->name('.index');
                Route::get('list/{status}', [AdminCategoryProductController::class, 'index'])->name('.list.status');
                Route::get('create', [AdminCategoryProductController::class, 'create'])->name('.create');
                Route::post('store', [AdminCategoryProductController::class, 'store'])->name('.store');
                Route::get('remove/{id}', [AdminCategoryProductController::class, 'remove'])->name('.remove');
                Route::get('restore/{id}', [AdminCategoryProductController::class, 'restore'])->name('.restore');
                Route::get('delete/{id}', [AdminCategoryProductController::class, 'delete'])->name('.delete');
                Route::get('edit/{id}', [AdminCategoryProductController::class, 'edit'])->name('.edit');
                Route::post('update/{id}', [AdminCategoryProductController::class, 'update'])->name('.update');
            });
            Route::get('/', [AdminProductController::class, 'index'])->name('.index');
            Route::get('list', [AdminProductController::class, 'index'])->name('.index');
            Route::get('list/{status}', [AdminProductController::class, 'index'])->name('.list.status');
            Route::get('create', [AdminProductController::class, 'create'])->name('.create');
            Route::post('store', [AdminProductController::class, 'store'])->name('.store');
            Route::get('remove/{id}', [AdminProductController::class, 'remove'])->name('.remove');
            Route::get('restore/{id}', [AdminProductController::class, 'restore'])->name('.restore');
            Route::get('delete/{id}', [AdminProductController::class, 'delete'])->name('.delete');
            Route::get('action', [AdminProductController::class, 'action'])->name('.action');
            Route::get('edit/{id}', [AdminProductController::class, 'edit'])->name('.edit');
            Route::post('update/{id}', [AdminProductController::class, 'update'])->name('.update');
        });
        Route::group(['prefix'=>'brand', 'as'=> '.brand'],function () {
            Route::get('/', [AdminBrandController::class, 'index'])->name('.index');
            Route::get('list', [AdminBrandController::class, 'index'])->name('.index');
            Route::post('store', [AdminBrandController::class, 'store'])->name('.store');
            Route::get('edit', [AdminBrandController::class, 'edit'])->name('.edit');
            Route::get('delete/{id}', [AdminBrandController::class, 'delete'])->name('.delete');
        });
        Route::group(['prefix'=>'feedback', 'as'=> '.feedback'],function () {
            Route::get('/', [AdminFeedbackController::class, 'index'])->name('.index');
            Route::get('list', [AdminFeedbackController::class, 'index'])->name('.index');
        });
        Route::group(['prefix'=>'slider', 'as'=> '.slider'],function () {
            Route::get('', [SliderController::class, 'index'])->name('.index');
            Route::get('show', [SliderController::class, 'index'])->name('.index');
            Route::post('store', [SliderController::class, 'store'])->name('.store');
            Route::get('remove/{id}', [SliderController::class, 'remove'])->name('.remove');
            Route::get('delete/{id}', [SliderController::class, 'delete'])->name('.delete');
            Route::get('restore/{id}', [SliderController::class, 'restore'])->name('.restore');
        });
    });
    Route::redirect('/', 'admin');
});

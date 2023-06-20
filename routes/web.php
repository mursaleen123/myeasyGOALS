<?php

use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

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

Route::get('users/delete-without-roles',  function () {
    $users = User::whereDoesntHave('roles')->get();
    foreach ($users as $user) {
        $user->forceDelete();
    }
    echo 'User with role deleted';
});

Route::get('users/demo-user', function () {
    Artisan::call('db:seed --class=CreateDemoUserSeeder');
    echo ("Demo User Created");
});









// Admin Routes
Route::group(['middleware' => ['auth']], function () {
    Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home');
    Route::get('/', 'App\Http\Controllers\HomeController@index')->name('index');
    Route::get('/admin/dashboard', 'App\Http\Controllers\HomeController@index')->name('dashboard');
    Route::get('/admin/profile/{id}', 'App\Http\Controllers\UserController@profile')->name('edit-profile');
    Route::post('users/profileUpdate', 'App\Http\Controllers\UserController@profileUpdate')->name('update-profile');
    Route::resource('roles', 'App\Http\Controllers\RoleController');
    Route::resource('users', 'App\Http\Controllers\UserController');

    Route::get('/get-faq-index', [App\Http\Controllers\FaqsController::class, 'index'])->name('get.faq.index');
    Route::get('/get-faq-form', [App\Http\Controllers\FaqsController::class, 'getFaqForm'])->name('get.faq.form');
    Route::post('/store-faq', [App\Http\Controllers\FaqsController::class, 'storeFaq'])->name('store.faq');
    Route::get('/delete-faq/{id}', [App\Http\Controllers\FaqsController::class, 'destroy'])->name('destroy.faq');
    Route::get('/edit-faq/{id}', [App\Http\Controllers\FaqsController::class, 'edit'])->name('edit.faq');
    Route::post('/edit-faq', [App\Http\Controllers\FaqsController::class, 'update'])->name('update.faq');

    Route::get('/admin/user-guide}', [App\Http\Controllers\UserGuideController::class,'index'])->name('userguide.index');
    Route::post('/change-and-update/user-guide}', [App\Http\Controllers\UserGuideController::class,'storeAndUpdate'])->name('userguide.storeAndUpdate');


    Route::get('/admin/disclaimer}', [App\Http\Controllers\DisclaimerController::class,'index'])->name('disclaimer.index');
    Route::post('/change-and-update/disclaimer}', [App\Http\Controllers\DisclaimerController::class,'storeAndUpdate'])->name('disclaimer.storeAndUpdate');

    Route::get('/admin/banners}', [App\Http\Controllers\BannersController::class,'index'])->name('banners.index');
    Route::get('/admin/create-banners}', [App\Http\Controllers\BannersController::class,'create'])->name('banners.create');
    Route::post('/admin/store-banners}', [App\Http\Controllers\BannersController::class,'store'])->name('banners.store');

});

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\ForgetPasswordController;
use App\Http\Controllers\Api\UserGuideController;
use App\Http\Controllers\Api\UserSubscriptionController;


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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group([
    // 'middleware' => 'auth:api',
    'namespace' => 'Api',
    'prefix' => 'v1'

], function ($router) {
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);

    Route::post('/password/email', [App\Http\Controllers\Api\ForgotPasswordController::class, 'sendResetLinkEmail']);
    Route::get('password/reset/{token}/{email}', [App\Http\Controllers\Api\ResetPasswordController::class, 'showResetForm'])->name('api.reset');
    Route::post('password/store/', [App\Http\Controllers\Api\ResetPasswordController::class, 'reset'])->name('api.password.store');

    // Route::group(['middleware' => 'auth'], function () {
    Route::get('/get-Faqs', [FaqController::class, 'FetchFaqs']);
    Route::get('/get-user-guide', [UserGuideController::class, 'FetchUserGuide']);
    // });

});



Route::group([
    // 'middleware' => 'auth:api',
    // 'middleware' => 'auth:sanctum',
    'namespace' => 'App\Http\Controllers',
    'prefix' => 'v1'

], function ($router) {
    Route::group(['namespace' => 'Api'], function ($router) {
        // Route::get('/me', function (Request $request) {
        //     return auth()->user();
        // });

        // Route::post('/auth/logout', [AuthController::class, 'logout']);

        Route::get('/getGroupPermissions/{mode}', 'RoleContoller@getModePermissions');
    });
});


// Auth APIs
Route::group(['prefix' => 'v1/auth/scans'], function () {

    //     Route::post('coach-signup', [AuthController::class, 'coachRegister'])->name('coach.signup');
    //     Route::post('coach-login', [AuthController::class, 'authenticateCoach'])->name('coach.login');

    Route::post('otp-send', [AuthController::class, 'sendOtpVerification']);
    Route::post('otp-verify', [AuthController::class, 'verifyOtpVerification']);
    Route::post('otp-resend', [AuthController::class, 'resendOtpVerification']);

    Route::group(['middleware' => 'auth:api'], function () {
        // New API

        Route::get('coach-logout', [AuthController::class, 'logoutCoach'])->name('coach.logout');
        Route::get('coach-data', [AuthController::class, 'authenticatedCoachData'])->name('coach.data');
        Route::post('coach-update', [AuthController::class, 'updateCoachData'])->name('coach.update');
        Route::post('coach-profile-image', [AuthController::class, 'updateCoachImageData'])->name('coach.profile-image');




        Route::post('member-add', [AuthController::class, 'verifyTeamMember'])->name('members.member-add');
        Route::post('member-verify', [AuthController::class, 'addTeamMember'])->name('members.member-verify');
        Route::post('member-verify-email', [AuthController::class, 'addTeamMemberEmail'])->name('members.member-verify-email');
        Route::get('member-fetch', [AuthController::class, 'fetchTeamMembers'])->name('members.member-fetch');
        Route::get('member-delete/{id}', [AuthController::class, 'deleteTeamMembers'])->name('members.member-delete');

        Route::get('account-delete', [AuthController::class, 'deleteUserAccount'])->name('coach.profile-delete');
    });
});

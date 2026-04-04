<?php

use App\Http\Controllers\Api\SitePasswordApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes (تطبيقات الجوال — مجموعة middleware "api")
|--------------------------------------------------------------------------
|
| مسارات كلمة مرور الموقع للهاتف هنا فقط. شجرة العائلة المشتركة تبقى في web.php.
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('site-password')->name('api.site-password.')->group(function () {
    Route::get('status', [SitePasswordApiController::class, 'status'])->name('status');
    Route::post('verify', [SitePasswordApiController::class, 'verify'])
        ->middleware('throttle:20,1')
        ->name('verify');
    Route::post('revoke', [SitePasswordApiController::class, 'revoke'])
        ->middleware('throttle:30,1')
        ->name('revoke');
});

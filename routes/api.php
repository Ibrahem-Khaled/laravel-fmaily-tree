<?php

use App\Http\Controllers\Api\BreastfeedingPublicApiController;
use App\Http\Controllers\Api\ReportsPublicApiController;
use App\Http\Controllers\Api\SitePasswordApiController;
use App\Http\Controllers\FamilyTreeController;
use App\Http\Controllers\GalleryApiController;
use App\Http\Controllers\HomeApiController;
use App\Http\Controllers\PersonsBadgesApiController;
use App\Http\Controllers\ProgramShowApiController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\StoriesPublicController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes (مجموعة middleware "api" من RouteServiceProvider + prefix api)
|--------------------------------------------------------------------------
|
| مسارات الجوال الخالية من الجلسة (site-password، sanctum) خارج مجموعة web.
| مسارات JSON العامة (شجرة العائلة، التقارير، الصفحة الرئيسية API، …)
| تُلف بـ middleware "web" داخل المجموعة أدناه ليبقى StartSession و
| SitePasswordProtection كما عندما كانت في web.php (مهم لصفحة /sila).
| صفحة HTML لصلة تبقى في routes/web.php.
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

Route::middleware('web')->group(function () {
    Route::get('/reports', [ReportsPublicApiController::class, 'index']);
    Route::get('/reports/person/{personId}/statistics', [ReportsController::class, 'getPersonStatistics'])->name('reports.person.statistics');
    Route::get('/reports/location/{locationId}/persons', [ReportsController::class, 'getLocationPersons'])->name('reports.location.persons');
    Route::get('/reports/name/{name}/persons', [ReportsController::class, 'getPersonsByName'])->name('reports.name.persons');

    Route::get('/home', HomeApiController::class);
    Route::get('/breastfeeding', [BreastfeedingPublicApiController::class, 'index']);
    Route::get('/gallery/categories', [GalleryApiController::class, 'categories']);
    Route::get('/gallery/images', [GalleryApiController::class, 'images']);
    Route::get('/persons/badges', PersonsBadgesApiController::class);
    Route::get('/programs/{program}', [ProgramShowApiController::class, 'show']);
    Route::get('/family-tree', [FamilyTreeController::class, 'getFamilyTree']);
    Route::get('/person/{id}', [FamilyTreeController::class, 'getPersonDetails']);
    Route::get('/person/{id}/children', [FamilyTreeController::class, 'getChildren']);
    Route::get('/person/{id}/children-details', [FamilyTreeController::class, 'getChildrenForDetails']);
    Route::get('/person/{father}/wives', [FamilyTreeController::class, 'getWives']);
    Route::get('/person/{id}/stories/count', [StoriesPublicController::class, 'countForPerson']);
    Route::get('/person/{id}/friendships/count', [FamilyTreeController::class, 'getFriendshipsCount']);

    Route::get('/generations', [FamilyTreeController::class, 'getGenerations']);
    Route::get('/generation/{level}/whatsapp', [FamilyTreeController::class, 'getGenerationWithWhatsApp']);
    Route::get('/persons/search', [FamilyTreeController::class, 'searchPersons']);
    Route::get('/persons/search/whatsapp', [FamilyTreeController::class, 'searchPersonsWithWhatsApp']);
    Route::get('/person/{id}/whatsapp', [FamilyTreeController::class, 'getPersonWithWhatsApp']);
    Route::get('/person/{id}/children/whatsapp', [FamilyTreeController::class, 'getChildrenWithWhatsApp']);
    Route::get('/person/{id}/descendants/whatsapp', [FamilyTreeController::class, 'getDescendantsWithWhatsApp']);
});

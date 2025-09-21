<?php

use App\Http\Controllers\admin\ArticleController;
use App\Http\Controllers\admin\BreastfeedingController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\ImageController;
use App\Http\Controllers\admin\MarriageController;
use App\Http\Controllers\admin\OutsideFamilyPersonController;
use App\Http\Controllers\admin\PadgeController;
use App\Http\Controllers\admin\PadgePeopleController;
use App\Http\Controllers\admin\PersonController;
use App\Http\Controllers\admin\RoleController;
use App\Http\Controllers\BreastfeedingPublicController;
use App\Http\Controllers\FamilyTreeController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\HomePersonController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [FamilyTreeController::class, 'index'])->name('old.family-tree');
Route::get('/family-tree', [FamilyTreeController::class, 'newIndex'])->name('family-tree');
Route::get('/add-self', [FamilyTreeController::class, 'addSelf'])->name('add.self');

Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');
Route::get('/article/{id}', [GalleryController::class, 'show'])->name('article.show');
Route::get('/gallery/articles', [GalleryController::class, 'articles'])->name('gallery.articles');

Route::prefix('api')->group(function () {
    Route::get('/family-tree', [FamilyTreeController::class, 'getFamilyTree']);
    Route::get('/person/{id}', [FamilyTreeController::class, 'getPersonDetails']);
    Route::get('/person/{id}/children', [FamilyTreeController::class, 'getChildren']);
});

Route::get('persons/badges', [HomePersonController::class, 'personsWhereHasBadges'])->name('persons.badges');
Route::get('/people/profile/{person}', [HomePersonController::class, 'show'])->name('people.profile.show');

// Public Breastfeeding Routes
Route::get('/breastfeeding', [BreastfeedingPublicController::class, 'index'])->name('breastfeeding.public.index');
Route::get('/breastfeeding/{person}', [BreastfeedingPublicController::class, 'show'])->name('breastfeeding.public.show');


Route::group(['middleware' => ['auth'], 'prefix' => 'dashboard'], function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('people', PersonController::class);
    Route::delete('/people/{person}/photo', [PersonController::class, 'removePhoto'])->name('people.removePhoto');
    Route::post('/people/reorder', [PersonController::class, 'reorder'])->name('people.reorder');
    Route::get('/people/search', [PersonController::class, 'search'])->name('people.search');
    Route::get('/people/{father}/wives', [PersonController::class, 'getWives'])->name('people.getWives');

    Route::post('/persons/store-outside', [OutsideFamilyPersonController::class, 'store'])->name('persons.store.outside');

    Route::resource('marriages', MarriageController::class)->except(['show']);

    Route::resource('articles', ArticleController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::delete('/attachments/{attachment}', [ArticleController::class, 'destroyAttachment'])
        ->name('attachments.destroy');
    Route::get('/attachments/{attachment}/download', [ArticleController::class, 'downloadAttachment'])
        ->name('attachments.download');

    // معرض الصور
    Route::get('images/index',        [ImageController::class, 'index'])->name('dashboard.images.index');
    Route::post('gallery/upload', [ImageController::class, 'store'])->name('gallery.store');
    Route::delete('images/{image}', [ImageController::class, 'destroy'])->name('images.destroy');
    Route::delete('images',         [ImageController::class, 'bulkDestroy'])->name('images.bulk-destroy');

    Route::post('articles/{article}/images', [ImageController::class, 'storeForArticle'])->name('articles.images.store');
    // إنشاء فئة سريع (AJAX)
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::post('categories/quick-store', [CategoryController::class, 'store'])->name('categories.quick-store');

    // this route is for the admin panel
    Route::resource('roles', RoleController::class);

    // Badges (Padges) routes
    Route::resource('padges', PadgeController::class);
    Route::get('padges/{padge}/people', [PadgePeopleController::class, 'index'])->name('padges.people.index');
    Route::get('dashboard/padges/{padge}/people/search', [PadgePeopleController::class, 'search'])->name('padges.people.search');

    Route::post('padges/{padge}/people', [PadgePeopleController::class, 'attach'])->name('padges.people.attach');
    Route::delete('padges/{padge}/people/{person}', [PadgePeopleController::class, 'detach'])->name('padges.people.detach');
    Route::patch('padges/{padge}/people/{person}/toggle', [PadgePeopleController::class, 'toggle'])->name('padges.people.toggle');

    // Breastfeeding routes
    Route::resource('breastfeeding', BreastfeedingController::class);
    Route::patch('breastfeeding/{breastfeeding}/toggle-status', [BreastfeedingController::class, 'toggleStatus'])->name('breastfeeding.toggle-status');
    Route::get('breastfeeding/nursing-mothers/search', [BreastfeedingController::class, 'getNursingMothers'])->name('breastfeeding.nursing-mothers.search');
    Route::get('breastfeeding/breastfed-children/search', [BreastfeedingController::class, 'getBreastfedChildren'])->name('breastfeeding.breastfed-children.search');
});

require __DIR__ . '/auth.php';

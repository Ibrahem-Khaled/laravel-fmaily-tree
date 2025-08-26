<?php

use App\Http\Controllers\admin\ArticleController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\MarriageController;
use App\Http\Controllers\admin\OutsideFamilyPersonController;
use App\Http\Controllers\admin\PersonController;
use App\Http\Controllers\admin\RoleController;
use App\Http\Controllers\FamilyTreeController;
use App\Http\Controllers\GalleryController;
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


Route::group(['middleware' => ['auth'], 'prefix' => 'dashboard'], function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('people', PersonController::class);
    Route::post('/people/reorder', [PersonController::class, 'reorder'])->name('people.reorder');
    Route::get('/people/search', [PersonController::class, 'search'])->name('people.search');
    Route::get('/people/{father}/wives', [PersonController::class, 'getWives'])->name('people.getWives');

    Route::post('/persons/store-outside', [OutsideFamilyPersonController::class, 'store'])->name('persons.store.outside');

    Route::resource('marriages', MarriageController::class)->except(['show']);

    Route::resource('articles', ArticleController::class)->except(['show', 'update']);
    Route::post('articles/{article}', [ArticleController::class, 'update'])->name('articles.update');
    Route::delete('/articles/image/{id}', [ArticleController::class, 'deleteImage'])->name('articles.image.delete');
    Route::post('/articles/add-images', [ArticleController::class, 'storeImages'])->name('articles.images.store');

    // راوت مخصص لإنشاء الفئات عبر AJAX من داخل مودال المقالات
    Route::post('categories/store-ajax', [CategoryController::class, 'storeAjax'])->name('categories.store.ajax');
    Route::resource('categories', CategoryController::class);

    // this route is for the admin panel
    Route::resource('roles', RoleController::class);
});

require __DIR__ . '/auth.php';

<?php

use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\MarriageController;
use App\Http\Controllers\admin\OutsideFamilyPersonController;
use App\Http\Controllers\admin\PersonController;
use App\Http\Controllers\FamilyTreeController;
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

Route::get('/', [FamilyTreeController::class, 'index'])->name('family-tree');
Route::get('/old/family-tree', [FamilyTreeController::class, 'oldIndex'])->name('old.family-tree');
Route::get('/add-self', [FamilyTreeController::class, 'addSelf'])->name('add.self');

Route::prefix('api')->group(function () {
    Route::get('/family-tree', [FamilyTreeController::class, 'getFamilyTree']);
    Route::get('/person/{id}', [FamilyTreeController::class, 'getPersonDetails']);
    Route::get('/person/{id}/children', [FamilyTreeController::class, 'getChildren']);
});


Route::group(['middleware' => ['auth',]], function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');



    Route::resource('people', PersonController::class);
    Route::post('/people/reorder', [PersonController::class, 'reorder'])->name('people.reorder');
    Route::get('/people/search', [PersonController::class, 'search'])->name('people.search');
    Route::get('/people/{father}/wives', [PersonController::class, 'getWives'])->name('people.getWives');

    Route::post('/persons/store-outside', [OutsideFamilyPersonController::class, 'store'])->name('persons.store.outside');

    Route::resource('marriages', MarriageController::class)->except(['show']);
});

require __DIR__ . '/auth.php';

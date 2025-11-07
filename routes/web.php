<?php

use App\Http\Controllers\admin\LocationController;
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
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\ArticleController as AdminArticleController;
use App\Http\Controllers\BreastfeedingPublicController;
use App\Http\Controllers\FamilyTreeController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HomePersonController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\LogsController;
use App\Http\Controllers\StoriesPublicController;
use App\Http\Controllers\admin\StoryController;
use App\Http\Controllers\admin\VisitLogController;
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

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/sila', [FamilyTreeController::class, 'index'])->name('sila'); // صفحة صلة - شجرة العائلة
Route::get('/family-tree', [FamilyTreeController::class, 'newIndex'])->name('family-tree');
Route::get('/add-self', [FamilyTreeController::class, 'addSelf'])->name('add.self');

Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');
Route::get('/article/{id}', [GalleryController::class, 'show'])->name('article.show');
Route::get('/gallery/articles', [GalleryController::class, 'articles'])->name('gallery.articles');
Route::get('/person-gallery/{person}', [GalleryController::class, 'personGallery'])->name('person.gallery');
Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
Route::get('/api/reports/person/{personId}/statistics', [ReportsController::class, 'getPersonStatistics'])->name('reports.person.statistics');

Route::prefix('api')->group(function () {
    Route::get('/family-tree', [FamilyTreeController::class, 'getFamilyTree']);
    Route::get('/person/{id}', [FamilyTreeController::class, 'getPersonDetails']);
    Route::get('/person/{id}/children', [FamilyTreeController::class, 'getChildren']);
    Route::get('/person/{id}/children-details', [FamilyTreeController::class, 'getChildrenForDetails']);
    Route::get('/person/{father}/wives', [FamilyTreeController::class, 'getWives']);
    Route::get('/person/{id}/stories/count', [StoriesPublicController::class, 'countForPerson']);
});

Route::get('persons/badges', [HomePersonController::class, 'personsWhereHasBadges'])->name('persons.badges');
Route::get('/people/profile/{person}', [HomePersonController::class, 'show'])->name('people.profile.show');

// Public stories pages (distinct names to avoid admin resource conflicts)
Route::get('/stories/person/{person}', [StoriesPublicController::class, 'personStories'])->name('public.stories.person');
Route::get('/stories/{story}', [StoriesPublicController::class, 'show'])->name('public.stories.show');

// Public Breastfeeding Routes
Route::get('/breastfeeding', [BreastfeedingPublicController::class, 'index'])->name('breastfeeding.public.index');
Route::get('/breastfeeding/{person}', [BreastfeedingPublicController::class, 'show'])->name('breastfeeding.public.show');


Route::group(['middleware' => ['auth'], 'prefix' => 'dashboard'], function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('people', PersonController::class)->middleware(['permission:people.view|people.create|people.update|people.delete']);
    Route::delete('/people/{person}/photo', [PersonController::class, 'removePhoto'])->name('people.removePhoto')->middleware(['permission:people.update']);
    Route::post('/people/reorder', [PersonController::class, 'reorder'])->name('people.reorder')->middleware(['permission:people.update']);
    Route::get('/people/search', [PersonController::class, 'search'])->name('people.search')->middleware(['permission:people.view']);
    Route::get('/people/{father}/wives', [PersonController::class, 'getWives'])->name('people.getWives')->middleware(['permission:people.view']);

    Route::post('/persons/store-outside', [OutsideFamilyPersonController::class, 'store'])->name('persons.store.outside')->middleware(['permission:people.create']);

    Route::resource('marriages', MarriageController::class)->except(['show']);

    Route::resource('articles', ArticleController::class)->only(['index', 'store', 'update', 'destroy']);
    // حذف فيديو من مقال
    Route::delete('/articles/{article}/videos/{video}', [AdminArticleController::class, 'destroyVideo'])->name('articles.videos.destroy');
    Route::delete('/attachments/{attachment}', [ArticleController::class, 'destroyAttachment'])
        ->name('attachments.destroy');
    Route::get('/attachments/{attachment}/download', [ArticleController::class, 'downloadAttachment'])
        ->name('attachments.download');

    // معرض الصور
    Route::get('images/index',        [ImageController::class, 'index'])->name('dashboard.images.index');
    Route::get('images/{image}/edit', [ImageController::class, 'edit'])->name('images.edit');
    Route::put('images/{image}',      [ImageController::class, 'update'])->name('images.update');
    Route::post('gallery/upload', [ImageController::class, 'store'])->name('gallery.store');
    Route::delete('images/{image}', [ImageController::class, 'destroy'])->name('images.destroy');
    Route::delete('images',         [ImageController::class, 'bulkDestroy'])->name('images.bulk-destroy');
    Route::delete('images/{image}/remove-person/{person}', [ImageController::class, 'removePerson'])->name('images.remove-person');
    Route::post('images/{image}/reorder-persons', [ImageController::class, 'reorderPersons'])->name('images.reorder-persons');
    Route::get('images/{image}/download', [ImageController::class, 'download'])->name('images.download');

    Route::post('articles/{article}/images', [ImageController::class, 'storeForArticle'])->name('articles.images.store');
    // إنشاء فئة سريع (AJAX)
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::post('categories/quick-store', [CategoryController::class, 'store'])->name('categories.quick-store');
    Route::post('categories/delete-empty', [CategoryController::class, 'deleteEmpty'])->name('categories.delete-empty');

    // Locations routes
    Route::get('locations/find-similar', [LocationController::class, 'findSimilar'])->name('locations.find-similar');
    Route::get('locations/autocomplete', [LocationController::class, 'autocomplete'])->name('locations.autocomplete');
    Route::post('locations/do-merge', [LocationController::class, 'merge'])->name('locations.do-merge');
    Route::resource('locations', LocationController::class)->where(['location' => '[0-9]+']);
    Route::resource('roles', RoleController::class)->middleware(['permission:roles.manage']);
    Route::resource('users', UserController::class)->only(['index', 'store', 'update', 'destroy'])->middleware(['permission:users.manage']);
    Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status')->middleware(['permission:users.manage']);

    // Badges (Padges) routes
    Route::resource('padges', PadgeController::class);
    Route::get('padges/{padge}/people', [PadgePeopleController::class, 'index'])->name('padges.people.index');
    Route::get('dashboard/padges/{padge}/people/search', [PadgePeopleController::class, 'search'])->name('padges.people.search');

    Route::post('padges/{padge}/people', [PadgePeopleController::class, 'attach'])->name('padges.people.attach');
    Route::delete('padges/{padge}/people/{person}', [PadgePeopleController::class, 'detach'])->name('padges.people.detach');
    Route::patch('padges/{padge}/people/{person}/toggle', [PadgePeopleController::class, 'toggle'])->name('padges.people.toggle');

    // Breastfeeding routes
    Route::resource('breastfeeding', BreastfeedingController::class)->middleware(['permission:breastfeeding.view|breastfeeding.create|breastfeeding.update|breastfeeding.delete']);
    Route::patch('breastfeeding/{breastfeeding}/toggle-status', [BreastfeedingController::class, 'toggleStatus'])->name('breastfeeding.toggle-status')->middleware(['permission:breastfeeding.update']);
    Route::get('breastfeeding/nursing-mothers/search', [BreastfeedingController::class, 'getNursingMothers'])->name('breastfeeding.nursing-mothers.search')->middleware(['permission:breastfeeding.view']);
    Route::get('breastfeeding/breastfed-children/search', [BreastfeedingController::class, 'getBreastfedChildren'])->name('breastfeeding.breastfed-children.search')->middleware(['permission:breastfeeding.view']);

    // Stories routes
    Route::resource('stories', StoryController::class);

    // Logs routes
    Route::get('logs/activity', [LogsController::class, 'activity'])->name('logs.activity');
    Route::get('logs/audits',   [LogsController::class, 'audits'])->name('logs.audits');

    // Visit Logs routes
    Route::get('visit-logs', [VisitLogController::class, 'index'])->name('dashboard.visit-logs.index');
    Route::get('visit-logs/{visitLog}', [VisitLogController::class, 'show'])->name('dashboard.visit-logs.show');

    // Site Content routes
    Route::get('site-content', [\App\Http\Controllers\admin\SiteContentController::class, 'index'])->name('dashboard.site-content.index');
    Route::post('site-content/family-brief', [\App\Http\Controllers\admin\SiteContentController::class, 'updateFamilyBrief'])->name('dashboard.site-content.update-family-brief');
    Route::post('site-content/whats-new', [\App\Http\Controllers\admin\SiteContentController::class, 'updateWhatsNew'])->name('dashboard.site-content.update-whats-new');
    
    // Slideshow routes
    Route::get('slideshow', [\App\Http\Controllers\admin\SiteContentController::class, 'slideshow'])->name('dashboard.slideshow.index');
    Route::get('slideshow/{slideshowImage}', [\App\Http\Controllers\admin\SiteContentController::class, 'getSlideshowImage'])->name('dashboard.slideshow.show');
    Route::post('slideshow/add', [\App\Http\Controllers\admin\SiteContentController::class, 'addSlideshowImage'])->name('dashboard.slideshow.add');
    Route::post('slideshow/{slideshowImage}/update', [\App\Http\Controllers\admin\SiteContentController::class, 'updateSlideshowImage'])->name('dashboard.slideshow.update');
    Route::post('slideshow/reorder', [\App\Http\Controllers\admin\SiteContentController::class, 'reorderSlideshow'])->name('dashboard.slideshow.reorder');
    Route::delete('slideshow/{slideshowImage}', [\App\Http\Controllers\admin\SiteContentController::class, 'removeSlideshowImage'])->name('dashboard.slideshow.remove');
    Route::post('slideshow/{slideshowImage}/toggle', [\App\Http\Controllers\admin\SiteContentController::class, 'toggleSlideshowImage'])->name('dashboard.slideshow.toggle');
    
    // Courses routes
    Route::get('courses', [\App\Http\Controllers\admin\CourseController::class, 'index'])->name('dashboard.courses.index');
    Route::post('courses', [\App\Http\Controllers\admin\CourseController::class, 'store'])->name('dashboard.courses.store');
    Route::get('courses/{course}', [\App\Http\Controllers\admin\CourseController::class, 'show'])->name('dashboard.courses.show');
    Route::post('courses/{course}/update', [\App\Http\Controllers\admin\CourseController::class, 'update'])->name('dashboard.courses.update');
    Route::post('courses/reorder', [\App\Http\Controllers\admin\CourseController::class, 'reorder'])->name('dashboard.courses.reorder');
    Route::delete('courses/{course}', [\App\Http\Controllers\admin\CourseController::class, 'destroy'])->name('dashboard.courses.destroy');
    Route::post('courses/{course}/toggle', [\App\Http\Controllers\admin\CourseController::class, 'toggle'])->name('dashboard.courses.toggle');
    
    // Programs routes
    Route::get('programs', [\App\Http\Controllers\admin\ProgramController::class, 'index'])->name('dashboard.programs.index');
    Route::post('programs', [\App\Http\Controllers\admin\ProgramController::class, 'store'])->name('dashboard.programs.store');
    Route::get('programs/{program}', [\App\Http\Controllers\admin\ProgramController::class, 'show'])->name('dashboard.programs.show');
    Route::post('programs/{program}/update', [\App\Http\Controllers\admin\ProgramController::class, 'update'])->name('dashboard.programs.update');
    Route::post('programs/reorder', [\App\Http\Controllers\admin\ProgramController::class, 'reorder'])->name('dashboard.programs.reorder');
    Route::delete('programs/{program}', [\App\Http\Controllers\admin\ProgramController::class, 'destroy'])->name('dashboard.programs.destroy');
});

require __DIR__ . '/auth.php';

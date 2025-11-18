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
use App\Http\Controllers\ProgramPageController;
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
Route::get('/programs/{program}', [ProgramPageController::class, 'show'])->name('programs.show');
Route::get('/councils', [\App\Http\Controllers\FamilyCouncilPublicController::class, 'index'])->name('councils.index');
Route::get('/councils/{council}', [\App\Http\Controllers\FamilyCouncilPublicController::class, 'show'])->name('councils.show');
Route::get('/sila', [FamilyTreeController::class, 'index'])->name('sila'); // صفحة صلة - شجرة العائلة
Route::get('/family-tree', [FamilyTreeController::class, 'newIndex'])->name('family-tree');
Route::get('/add-self', [FamilyTreeController::class, 'addSelf'])->name('add.self');

Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');
Route::get('/article/{id}', [GalleryController::class, 'show'])->name('article.show');
Route::get('/gallery/articles', [GalleryController::class, 'articles'])->name('gallery.articles');
Route::get('/person-gallery/{person}', [GalleryController::class, 'personGallery'])->name('person.gallery');
Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
Route::get('/api/reports/person/{personId}/statistics', [ReportsController::class, 'getPersonStatistics'])->name('reports.person.statistics');
Route::get('/api/reports/location/{locationId}/persons', [ReportsController::class, 'getLocationPersons'])->name('reports.location.persons');

Route::prefix('api')->group(function () {
    Route::get('/family-tree', [FamilyTreeController::class, 'getFamilyTree']);
    Route::get('/person/{id}', [FamilyTreeController::class, 'getPersonDetails']);
    Route::get('/person/{id}/children', [FamilyTreeController::class, 'getChildren']);
    Route::get('/person/{id}/children-details', [FamilyTreeController::class, 'getChildrenForDetails']);
    Route::get('/person/{father}/wives', [FamilyTreeController::class, 'getWives']);
    Route::get('/person/{id}/stories/count', [StoriesPublicController::class, 'countForPerson']);
    Route::get('/person/{id}/friendships/count', [FamilyTreeController::class, 'getFriendshipsCount']);
});

// Routes for friendships
Route::get('/person/{person}/friends', [\App\Http\Controllers\FriendshipController::class, 'index'])->name('person.friends');

Route::get('persons/badges', [HomePersonController::class, 'personsWhereHasBadges'])->name('persons.badges');
Route::get('/people/profile/{person}', [HomePersonController::class, 'show'])->name('people.profile.show');

// Public stories pages (distinct names to avoid admin resource conflicts)
Route::get('/stories/person/{person}', [StoriesPublicController::class, 'personStories'])->name('public.stories.person');
Route::get('/stories/{story}', [StoriesPublicController::class, 'show'])->name('public.stories.show');

// Public Breastfeeding Routes
Route::get('/breastfeeding', [BreastfeedingPublicController::class, 'index'])->name('breastfeeding.public.index');
Route::get('/breastfeeding/{person}', [BreastfeedingPublicController::class, 'show'])->name('breastfeeding.public.show');

// Public Store Routes
Route::prefix('store')->name('store.')->group(function () {
    Route::get('/', [\App\Http\Controllers\ProductStoreController::class, 'index'])->name('index');
    Route::get('category/{category}', [\App\Http\Controllers\ProductStoreController::class, 'category'])->name('category');
    Route::get('subcategory/{subcategory}', [\App\Http\Controllers\ProductStoreController::class, 'subcategory'])->name('subcategory');
    Route::get('{product}', [\App\Http\Controllers\ProductStoreController::class, 'show'])->name('show');
});


Route::group(['middleware' => ['auth'], 'prefix' => 'dashboard'], function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('people', PersonController::class)->middleware(['permission:people.view|people.create|people.update|people.delete']);
    
    // Routes for managing person contact accounts
    Route::post('people/{person}/contact-accounts', [\App\Http\Controllers\admin\PersonContactAccountController::class, 'store'])->name('people.contact-accounts.store');
    Route::put('people/{person}/contact-accounts/{contactAccount}', [\App\Http\Controllers\admin\PersonContactAccountController::class, 'update'])->name('people.contact-accounts.update');
    Route::delete('people/{person}/contact-accounts/{contactAccount}', [\App\Http\Controllers\admin\PersonContactAccountController::class, 'destroy'])->name('people.contact-accounts.destroy');
    
    // Routes for managing person locations
    Route::post('people/{person}/locations', [\App\Http\Controllers\admin\PersonLocationController::class, 'store'])->name('people.locations.store');
    Route::put('people/{person}/locations/{personLocation}', [\App\Http\Controllers\admin\PersonLocationController::class, 'update'])->name('people.locations.update');
    Route::delete('people/{person}/locations/{personLocation}', [\App\Http\Controllers\admin\PersonLocationController::class, 'destroy'])->name('people.locations.destroy');
    Route::delete('/people/{person}/photo', [PersonController::class, 'removePhoto'])->name('people.removePhoto')->middleware(['permission:people.update']);
    Route::post('/people/reorder', [PersonController::class, 'reorder'])->name('people.reorder')->middleware(['permission:people.update']);
    Route::get('/people/search', [PersonController::class, 'search'])->name('people.search')->middleware(['permission:people.view']);
    Route::get('/people/{father}/wives', [PersonController::class, 'getWives'])->name('people.getWives')->middleware(['permission:people.view']);
    Route::get('/people/export/excel', [PersonController::class, 'export'])->name('people.export.excel')->middleware(['permission:people.view']);

    Route::post('/persons/store-outside', [OutsideFamilyPersonController::class, 'store'])->name('persons.store.outside')->middleware(['permission:people.create']);

    Route::resource('marriages', MarriageController::class)->except(['show']);
    Route::resource('friendships', \App\Http\Controllers\admin\FriendshipController::class)->except(['show']);

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
    
    // Home Gallery routes
    Route::get('home-gallery', [\App\Http\Controllers\admin\SiteContentController::class, 'homeGallery'])->name('dashboard.home-gallery.index');
    Route::post('home-gallery/add', [\App\Http\Controllers\admin\SiteContentController::class, 'addHomeGalleryImage'])->name('dashboard.home-gallery.add');
    Route::post('home-gallery/{homeGalleryImage}/update', [\App\Http\Controllers\admin\SiteContentController::class, 'updateHomeGalleryImage'])->name('dashboard.home-gallery.update');
    Route::post('home-gallery/reorder', [\App\Http\Controllers\admin\SiteContentController::class, 'reorderHomeGallery'])->name('dashboard.home-gallery.reorder');
    Route::delete('home-gallery/{homeGalleryImage}', [\App\Http\Controllers\admin\SiteContentController::class, 'removeHomeGalleryImage'])->name('dashboard.home-gallery.remove');
    Route::post('home-gallery/{homeGalleryImage}/toggle', [\App\Http\Controllers\admin\SiteContentController::class, 'toggleHomeGalleryImage'])->name('dashboard.home-gallery.toggle');
    
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
    Route::get('programs/{program}/manage', [\App\Http\Controllers\admin\ProgramController::class, 'manage'])->name('dashboard.programs.manage');
    Route::post('programs/{program}/media', [\App\Http\Controllers\admin\ProgramController::class, 'storeMedia'])->name('dashboard.programs.media.store');
    Route::post('programs/{program}/media/{media}/update', [\App\Http\Controllers\admin\ProgramController::class, 'updateMedia'])->name('dashboard.programs.media.update');
    Route::delete('programs/{program}/media/{media}', [\App\Http\Controllers\admin\ProgramController::class, 'destroyMedia'])->name('dashboard.programs.media.destroy');
    Route::post('programs/{program}/media/reorder', [\App\Http\Controllers\admin\ProgramController::class, 'reorderMedia'])->name('dashboard.programs.media.reorder');
    Route::post('programs/{program}/links', [\App\Http\Controllers\admin\ProgramController::class, 'storeLink'])->name('dashboard.programs.links.store');
    Route::delete('programs/{program}/links/{link}', [\App\Http\Controllers\admin\ProgramController::class, 'destroyLink'])->name('dashboard.programs.links.destroy');
    Route::post('programs/{program}/links/reorder', [\App\Http\Controllers\admin\ProgramController::class, 'reorderLinks'])->name('dashboard.programs.links.reorder');
    // Gallery routes
    Route::post('programs/{program}/galleries', [\App\Http\Controllers\admin\ProgramController::class, 'storeGallery'])->name('dashboard.programs.galleries.store');
    Route::post('programs/{program}/galleries/{gallery}/update', [\App\Http\Controllers\admin\ProgramController::class, 'updateGallery'])->name('dashboard.programs.galleries.update');
    Route::delete('programs/{program}/galleries/{gallery}', [\App\Http\Controllers\admin\ProgramController::class, 'destroyGallery'])->name('dashboard.programs.galleries.destroy');
    Route::post('programs/{program}/galleries/{gallery}/media', [\App\Http\Controllers\admin\ProgramController::class, 'storeGalleryMedia'])->name('dashboard.programs.galleries.media.store');
    Route::post('programs/{program}/galleries/{gallery}/media/{media}/update', [\App\Http\Controllers\admin\ProgramController::class, 'updateGalleryMedia'])->name('dashboard.programs.galleries.media.update');
    Route::delete('programs/{program}/galleries/{gallery}/media/{media}', [\App\Http\Controllers\admin\ProgramController::class, 'destroyGalleryMedia'])->name('dashboard.programs.galleries.media.destroy');
    
    // Family Councils routes
    Route::get('councils', [\App\Http\Controllers\admin\FamilyCouncilController::class, 'index'])->name('dashboard.councils.index');
    Route::post('councils', [\App\Http\Controllers\admin\FamilyCouncilController::class, 'store'])->name('dashboard.councils.store');
    Route::get('councils/{council}', [\App\Http\Controllers\admin\FamilyCouncilController::class, 'show'])->name('dashboard.councils.show');
    Route::post('councils/{council}/update', [\App\Http\Controllers\admin\FamilyCouncilController::class, 'update'])->name('dashboard.councils.update');
    Route::post('councils/reorder', [\App\Http\Controllers\admin\FamilyCouncilController::class, 'reorder'])->name('dashboard.councils.reorder');
    Route::delete('councils/{council}', [\App\Http\Controllers\admin\FamilyCouncilController::class, 'destroy'])->name('dashboard.councils.destroy');
    Route::get('councils/{council}/manage', [\App\Http\Controllers\admin\FamilyCouncilController::class, 'manage'])->name('dashboard.councils.manage');
    Route::post('councils/{council}/images', [\App\Http\Controllers\admin\FamilyCouncilController::class, 'storeImage'])->name('dashboard.councils.images.store');
    Route::delete('councils/{council}/images/{image}', [\App\Http\Controllers\admin\FamilyCouncilController::class, 'destroyImage'])->name('dashboard.councils.images.destroy');
    Route::post('councils/{council}/images/reorder', [\App\Http\Controllers\admin\FamilyCouncilController::class, 'reorderImages'])->name('dashboard.councils.images.reorder');
    
    // Products Store routes
    Route::prefix('products')->name('products.')->group(function () {
        // Categories
        Route::get('categories', [\App\Http\Controllers\admin\ProductCategoryController::class, 'index'])->name('categories.index');
        Route::post('categories', [\App\Http\Controllers\admin\ProductCategoryController::class, 'store'])->name('categories.store');
        Route::put('categories/{category}', [\App\Http\Controllers\admin\ProductCategoryController::class, 'update'])->name('categories.update');
        Route::delete('categories/{category}', [\App\Http\Controllers\admin\ProductCategoryController::class, 'destroy'])->name('categories.destroy');
        Route::post('categories/{category}/toggle', [\App\Http\Controllers\admin\ProductCategoryController::class, 'toggle'])->name('categories.toggle');
        Route::post('categories/reorder', [\App\Http\Controllers\admin\ProductCategoryController::class, 'reorder'])->name('categories.reorder');
        
        // Subcategories
        Route::get('subcategories', [\App\Http\Controllers\admin\ProductSubcategoryController::class, 'index'])->name('subcategories.index');
        Route::post('subcategories', [\App\Http\Controllers\admin\ProductSubcategoryController::class, 'store'])->name('subcategories.store');
        Route::put('subcategories/{subcategory}', [\App\Http\Controllers\admin\ProductSubcategoryController::class, 'update'])->name('subcategories.update');
        Route::delete('subcategories/{subcategory}', [\App\Http\Controllers\admin\ProductSubcategoryController::class, 'destroy'])->name('subcategories.destroy');
        Route::post('subcategories/{subcategory}/toggle', [\App\Http\Controllers\admin\ProductSubcategoryController::class, 'toggle'])->name('subcategories.toggle');
        
        // Products
        Route::get('/', [\App\Http\Controllers\admin\ProductController::class, 'index'])->name('index');
        Route::get('create', [\App\Http\Controllers\admin\ProductController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\admin\ProductController::class, 'store'])->name('store');
        Route::get('{product}/edit', [\App\Http\Controllers\admin\ProductController::class, 'edit'])->name('edit');
        Route::put('{product}', [\App\Http\Controllers\admin\ProductController::class, 'update'])->name('update');
        Route::delete('{product}', [\App\Http\Controllers\admin\ProductController::class, 'destroy'])->name('destroy');
        Route::post('{product}/toggle', [\App\Http\Controllers\admin\ProductController::class, 'toggle'])->name('toggle');
    });
});

require __DIR__ . '/auth.php';

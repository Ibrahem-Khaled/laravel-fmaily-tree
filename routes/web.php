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
use App\Http\Controllers\admin\PersonSearchController;
use App\Http\Controllers\admin\RoleController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\ArticleController as AdminArticleController;
use App\Http\Controllers\BreastfeedingPublicController;
use App\Http\Controllers\FamilyNewsController;
use App\Http\Controllers\QuranCompetitionPublicController;
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
use App\Http\Controllers\SitePasswordController;

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

// Routes for site password protection (must be before other routes)
Route::get('/site-password', [SitePasswordController::class, 'show'])->name('site.password.show');
Route::post('/site-password', [SitePasswordController::class, 'verify'])->name('site.password.verify');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/programs/{program}', [ProgramPageController::class, 'show'])->name('programs.show');
Route::get('/councils', [\App\Http\Controllers\FamilyCouncilPublicController::class, 'index'])->name('councils.index');
Route::get('/councils/{council}', [\App\Http\Controllers\FamilyCouncilPublicController::class, 'show'])->name('councils.show');
Route::get('/sila', [FamilyTreeController::class, 'index'])->name('sila'); // صفحة صلة - شجرة العائلة
Route::get('/family-tree', [FamilyTreeController::class, 'newIndex'])->name('family-tree');
Route::get('/add-self', [FamilyTreeController::class, 'addSelf'])->name('add.self');

// User Registration Routes
Route::get('/register-person', [\App\Http\Controllers\UserRegistrationController::class, 'show'])->name('users.register');
Route::post('/register-person', [\App\Http\Controllers\UserRegistrationController::class, 'store'])->name('users.register.store');

Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');
Route::get('/article/{id}', [GalleryController::class, 'show'])->name('article.show');
Route::get('/gallery/articles', [GalleryController::class, 'articles'])->name('gallery.articles');
Route::get('/person-gallery/{person}', [GalleryController::class, 'personGallery'])->name('person.gallery');

// الروابط المهمة (تطبيقات ومواقع) - صفحة عامة
Route::get('/important-links', [\App\Http\Controllers\ImportantLinksPublicController::class, 'index'])->name('important-links.index');
Route::post('/important-links/suggest', [\App\Http\Controllers\ImportantLinksPublicController::class, 'suggest'])->name('important-links.suggest');

// Invitations (الدعوات) - Public
Route::prefix('invitations')->name('invitations.')->group(function () {
    Route::get('/', [\App\Http\Controllers\InvitationController::class, 'loginIndex'])->name('index');
    Route::post('/login-or-register', [\App\Http\Controllers\InvitationController::class, 'loginOrRegister'])->name('login-or-register');

    // Authenticated routes
    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\InvitationController::class, 'dashboard'])->name('dashboard');
        Route::get('/send', [\App\Http\Controllers\InvitationController::class, 'send'])->name('send');
        Route::post('/send', [\App\Http\Controllers\InvitationController::class, 'sendSubmit'])->name('send.submit');
        Route::get('/logs', [\App\Http\Controllers\InvitationController::class, 'logs'])->name('logs');
        Route::get('/logout', [\App\Http\Controllers\InvitationController::class, 'logout'])->name('logout');
        Route::get('/persons-with-whatsapp', [\App\Http\Controllers\InvitationController::class, 'personsWithWhatsApp'])->name('persons-with-whatsapp');
        Route::post('/preview-message', [\App\Http\Controllers\InvitationController::class, 'previewMessage'])->name('preview-message');

        // Groups management
        Route::prefix('groups')->name('groups.')->group(function () {
            Route::get('/', [\App\Http\Controllers\InvitationController::class, 'groupsIndex'])->name('index');
            Route::get('/create', [\App\Http\Controllers\InvitationController::class, 'groupsCreate'])->name('create');
            Route::post('/', [\App\Http\Controllers\InvitationController::class, 'groupsStore'])->name('store');
            Route::get('/{group}/edit', [\App\Http\Controllers\InvitationController::class, 'groupsEdit'])->name('edit');
            Route::put('/{group}', [\App\Http\Controllers\InvitationController::class, 'groupsUpdate'])->name('update');
            Route::delete('/{group}', [\App\Http\Controllers\InvitationController::class, 'groupsDestroy'])->name('destroy');
            Route::post('/{group}/attach-person', [\App\Http\Controllers\InvitationController::class, 'groupsAttachPerson'])->name('attach-person');
            Route::delete('/{group}/detach-person/{person}', [\App\Http\Controllers\InvitationController::class, 'groupsDetachPerson'])->name('detach-person');
        });
    });
});

Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
Route::get('/api/reports/person/{personId}/statistics', [ReportsController::class, 'getPersonStatistics'])->name('reports.person.statistics');
Route::get('/api/reports/location/{locationId}/persons', [ReportsController::class, 'getLocationPersons'])->name('reports.location.persons');
Route::get('/api/reports/name/{name}/persons', [ReportsController::class, 'getPersonsByName'])->name('reports.name.persons');

Route::prefix('api')->group(function () {
    Route::get('/family-tree', [FamilyTreeController::class, 'getFamilyTree']);
    Route::get('/person/{id}', [FamilyTreeController::class, 'getPersonDetails']);
    Route::get('/person/{id}/children', [FamilyTreeController::class, 'getChildren']);
    Route::get('/person/{id}/children-details', [FamilyTreeController::class, 'getChildrenForDetails']);
    Route::get('/person/{father}/wives', [FamilyTreeController::class, 'getWives']);
    Route::get('/person/{id}/stories/count', [StoriesPublicController::class, 'countForPerson']);
    Route::get('/person/{id}/friendships/count', [FamilyTreeController::class, 'getFriendshipsCount']);

    // WhatsApp Group Message Routes
    Route::get('/generations', [FamilyTreeController::class, 'getGenerations']);
    Route::get('/generation/{level}/whatsapp', [FamilyTreeController::class, 'getGenerationWithWhatsApp']);
    Route::get('/persons/search', [FamilyTreeController::class, 'searchPersons']);
    Route::get('/persons/search/whatsapp', [FamilyTreeController::class, 'searchPersonsWithWhatsApp']);
    Route::get('/person/{id}/whatsapp', [FamilyTreeController::class, 'getPersonWithWhatsApp']);
    Route::get('/person/{id}/children/whatsapp', [FamilyTreeController::class, 'getChildrenWithWhatsApp']);
    Route::get('/person/{id}/descendants/whatsapp', [FamilyTreeController::class, 'getDescendantsWithWhatsApp']);
});

// Routes for friendships
Route::get('/person/{person}/friends', [\App\Http\Controllers\FriendshipController::class, 'index'])->name('person.friends');

Route::get('persons/badges', [HomePersonController::class, 'personsWhereHasBadges'])->name('persons.badges');
Route::get('/people/profile/{person}', [HomePersonController::class, 'show'])->name('people.profile.show');

// Public stories pages (distinct names to avoid admin resource conflicts)
Route::get('/stories/person/{person}', [StoriesPublicController::class, 'personStories'])->name('public.stories.person');
Route::get('/stories/{story}', [StoriesPublicController::class, 'show'])->name('public.stories.show');

// Public Family News Routes
Route::get('/family-news/{id}', [FamilyNewsController::class, 'show'])->name('family-news.show');

// Public Breastfeeding Routes
Route::get('/breastfeeding', [BreastfeedingPublicController::class, 'index'])->name('breastfeeding.public.index');
Route::get('/breastfeeding/{person}', [BreastfeedingPublicController::class, 'show'])->name('breastfeeding.public.show');

// Public Quran Competitions Routes
Route::get('/quran-competitions', [\App\Http\Controllers\QuranCompetitionPublicController::class, 'index'])->name('quran-competitions.index');
Route::get('/quran-competitions/{id}', [\App\Http\Controllers\QuranCompetitionPublicController::class, 'show'])->name('quran-competitions.show');
Route::get('/quran-competitions/category/{category}', [\App\Http\Controllers\QuranCompetitionPublicController::class, 'showByCategory'])->name('quran-competitions.category');

// Public Quran Categories Routes
Route::get('/quran-categories/{category}', [\App\Http\Controllers\QuranCategoryController::class, 'show'])->name('quran-categories.show');

// Public Store Routes
Route::prefix('store')->name('store.')->group(function () {
    Route::get('/', [\App\Http\Controllers\ProductStoreController::class, 'index'])->name('index');
    Route::get('category/{category}', [\App\Http\Controllers\ProductStoreController::class, 'category'])->name('category');
    Route::get('subcategory/{subcategory}', [\App\Http\Controllers\ProductStoreController::class, 'subcategory'])->name('subcategory');
    Route::get('{product}', [\App\Http\Controllers\ProductStoreController::class, 'show'])->name('show');
});

// Rental Store Routes
Route::prefix('rental')->name('rental.')->group(function () {
    Route::get('/', [\App\Http\Controllers\RentalStoreController::class, 'index'])->name('index');
    Route::get('{product}', [\App\Http\Controllers\RentalStoreController::class, 'show'])->name('show');
    Route::post('{product}/request', [\App\Http\Controllers\RentalRequestController::class, 'store'])->name('request');
});

// User Rental Requests
Route::middleware('auth')->prefix('my-rentals')->name('my-rentals.')->group(function () {
    Route::get('/', [\App\Http\Controllers\RentalRequestController::class, 'index'])->name('index');
    Route::get('{request}', [\App\Http\Controllers\RentalRequestController::class, 'show'])->name('show');
});

// Health Websites Routes
Route::prefix('health-websites')->name('health-websites.')->group(function () {
    Route::get('/', [\App\Http\Controllers\HealthWebsiteController::class, 'index'])->name('index');
    Route::get('{website}', [\App\Http\Controllers\HealthWebsiteController::class, 'show'])->name('show');
});

// Public Quiz Competitions Routes (لا يوجد index - التوجيه للرئيسية)
Route::prefix('quiz-competitions')->name('quiz-competitions.')->group(function () {
    Route::get('/', fn () => redirect()->route('home')->withFragment('activeQuizSection'))->name('index');
    Route::get('{quizCompetition}', [\App\Http\Controllers\QuizCompetitionPublicController::class, 'show'])->name('show');
    Route::get('{quizCompetition}/questions/{quizQuestion}', [\App\Http\Controllers\QuizCompetitionPublicController::class, 'question'])->name('question');
    Route::get('{quizCompetition}/questions/{quizQuestion}/winner', [\App\Http\Controllers\QuizCompetitionPublicController::class, 'getWinner'])->name('question.winner');
    Route::post('{quizCompetition}/questions/{quizQuestion}/answer', [\App\Http\Controllers\QuizCompetitionPublicController::class, 'storeAnswer'])->name('store-answer');
});

// Public Competition Registration Routes
Route::prefix('competitions')->name('competitions.')->group(function () {
    Route::get('register/{token}', [\App\Http\Controllers\CompetitionRegistrationController::class, 'register'])->name('register');
    Route::post('register/{token}', [\App\Http\Controllers\CompetitionRegistrationController::class, 'store'])->name('register.store');
    Route::get('team/{team}/register', [\App\Http\Controllers\CompetitionRegistrationController::class, 'teamRegister'])->name('team.register');
    Route::post('team/{team}/register', [\App\Http\Controllers\CompetitionRegistrationController::class, 'teamStore'])->name('team.register.store');
});


Route::group(['middleware' => ['auth'], 'prefix' => 'dashboard'], function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard')->middleware(['permission:dashboard.view']);

    Route::resource('people', PersonController::class)->middleware(['permission:people.view|people.create|people.update|people.delete']);

    // Routes for managing person contact accounts
    Route::post('people/{person}/contact-accounts', [\App\Http\Controllers\admin\PersonContactAccountController::class, 'store'])->name('people.contact-accounts.store')->middleware(['permission:people.update']);
    Route::put('people/{person}/contact-accounts/{contactAccountId}', [\App\Http\Controllers\admin\PersonContactAccountController::class, 'update'])->name('people.contact-accounts.update')->middleware(['permission:people.update'])->where(['contactAccountId' => '[0-9]+']);
    Route::delete('people/{person}/contact-accounts/{contactAccountId}', [\App\Http\Controllers\admin\PersonContactAccountController::class, 'destroy'])->name('people.contact-accounts.destroy')->where(['contactAccountId' => '[0-9]+']);

    // Routes for managing person locations - متاح لجميع المستخدمين المسجلين
    Route::post('people/{person}/locations', [\App\Http\Controllers\admin\PersonLocationController::class, 'store'])->name('people.locations.store');
    Route::put('people/{person}/locations/{personLocation}', [\App\Http\Controllers\admin\PersonLocationController::class, 'update'])->name('people.locations.update');
    Route::delete('people/{person}/locations/{personLocation}', [\App\Http\Controllers\admin\PersonLocationController::class, 'destroy'])->name('people.locations.destroy');
    Route::delete('/people/{person}/photo', [PersonController::class, 'removePhoto'])->name('people.removePhoto')->middleware(['permission:people.update']);
    Route::post('/people/reorder', [PersonController::class, 'reorder'])->name('people.reorder')->middleware(['permission:people.update']);
    Route::get('/people/search', [PersonController::class, 'search'])->name('people.search')->middleware(['permission:people.view']);
    Route::get('/persons/search', PersonSearchController::class)->name('persons.search')->middleware(['permission:people.view']);
    Route::get('/people/{father}/wives', [PersonController::class, 'getWives'])->name('people.getWives')->middleware(['permission:people.view']);
    Route::get('/people/export/excel', [PersonController::class, 'export'])->name('people.export.excel')->middleware(['permission:people.view']);

    Route::post('/persons/store-outside', [OutsideFamilyPersonController::class, 'store'])->name('persons.store.outside')->middleware(['permission:people.create']);

    Route::resource('marriages', MarriageController::class)->except(['show'])->middleware(['permission:marriages.view|marriages.create|marriages.update|marriages.delete']);
    Route::resource('friendships', \App\Http\Controllers\admin\FriendshipController::class)->except(['show'])->middleware(['permission:friendships.view|friendships.create|friendships.update|friendships.delete']);

    Route::resource('articles', ArticleController::class)->only(['index', 'store', 'update', 'destroy'])->middleware(['permission:articles.view|articles.create|articles.update|articles.delete']);
    // حذف فيديو من مقال
    Route::delete('/articles/{article}/videos/{video}', [AdminArticleController::class, 'destroyVideo'])->name('articles.videos.destroy');
    Route::delete('/attachments/{attachment}', [ArticleController::class, 'destroyAttachment'])
        ->name('attachments.destroy');
    Route::get('/attachments/{attachment}/download', [ArticleController::class, 'downloadAttachment'])
        ->name('attachments.download');

    // معرض الصور
    Route::get('images/index',        [ImageController::class, 'index'])->name('dashboard.images.index')->middleware(['permission:images.view']);
    Route::get('gallery/category/{category}', [ImageController::class, 'showCategory'])->name('dashboard.gallery.category')->middleware(['permission:images.view']);
    Route::get('images/{image}/edit', [ImageController::class, 'edit'])->name('images.edit')->middleware(['permission:images.view']);
    Route::put('images/{image}',      [ImageController::class, 'update'])->name('images.update')->middleware(['permission:images.update']);
    Route::post('gallery/upload', [ImageController::class, 'store'])->name('gallery.store')->middleware(['permission:images.upload']);
    Route::delete('images/{image}', [ImageController::class, 'destroy'])->name('images.destroy')->middleware(['permission:images.delete']);
    Route::delete('images',         [ImageController::class, 'bulkDestroy'])->name('images.bulk-destroy')->middleware(['permission:images.delete']);
    Route::post('images/bulk-move', [ImageController::class, 'bulkMove'])->name('images.bulk-move')->middleware(['permission:images.update']);
    Route::delete('images/{image}/remove-person/{person}', [ImageController::class, 'removePerson'])->name('images.remove-person')->middleware(['permission:images.update']);
    Route::post('images/{image}/reorder-persons', [ImageController::class, 'reorderPersons'])->name('images.reorder-persons')->middleware(['permission:images.update']);
    Route::get('images/{image}/download', [ImageController::class, 'download'])->name('images.download')->middleware(['permission:images.view']);

    Route::post('articles/{article}/images', [ImageController::class, 'storeForArticle'])->name('articles.images.store');
    // إنشاء فئة سريع (AJAX)
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index')->middleware(['permission:categories.view']);
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update')->middleware(['permission:categories.update']);
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy')->middleware(['permission:categories.delete']);
    Route::post('categories/quick-store', [CategoryController::class, 'store'])->name('categories.quick-store')->middleware(['permission:categories.create']);
    Route::post('categories/delete-empty', [CategoryController::class, 'deleteEmpty'])->name('categories.delete-empty')->middleware(['permission:categories.delete']);
    Route::post('categories/{category}/toggle-active', [CategoryController::class, 'toggleActive'])->name('categories.toggle-active')->middleware(['permission:categories.update']);
    Route::post('categories/update-order', [CategoryController::class, 'updateOrder'])->name('categories.update-order')->middleware(['permission:categories.update']);
    Route::post('categories/{category}/managers', [CategoryController::class, 'addManager'])->name('categories.add-manager')->middleware(['permission:categories.update']);
    Route::delete('categories/managers/{manager}', [CategoryController::class, 'removeManager'])->name('categories.remove-manager')->middleware(['permission:categories.update']);
    Route::post('categories/{category}/managers/update-order', [CategoryController::class, 'updateManagerOrder'])->name('categories.update-manager-order')->middleware(['permission:categories.update']);

    // Locations routes
    Route::get('locations/find-similar', [LocationController::class, 'findSimilar'])->name('locations.find-similar')->middleware(['permission:locations.view']);
    Route::get('locations/autocomplete', [LocationController::class, 'autocomplete'])->name('locations.autocomplete')->middleware(['permission:locations.view']);
    Route::post('locations/do-merge', [LocationController::class, 'merge'])->name('locations.do-merge')->middleware(['permission:locations.update']);
    Route::resource('locations', LocationController::class)->where(['location' => '[0-9]+'])->middleware(['permission:locations.view|locations.create|locations.update|locations.delete']);
    Route::resource('roles', RoleController::class)->middleware(['permission:roles.manage']);
    Route::resource('users', UserController::class)->only(['index', 'store', 'update', 'destroy'])->middleware(['permission:users.manage']);
    Route::post('users/bulk-delete', [UserController::class, 'bulkDelete'])->name('users.bulk-delete')->middleware(['permission:users.manage']);
    Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status')->middleware(['permission:users.manage']);

    // Badges (Padges) routes
    Route::resource('padges', PadgeController::class)->middleware(['permission:padges.view|padges.create|padges.update|padges.delete']);
    Route::get('padges/{padge}/people', [PadgePeopleController::class, 'index'])->name('padges.people.index')->middleware(['permission:padges.view']);
    Route::get('dashboard/padges/{padge}/people/search', [PadgePeopleController::class, 'search'])->name('padges.people.search')->middleware(['permission:padges.view']);

    Route::post('padges/{padge}/people', [PadgePeopleController::class, 'attach'])->name('padges.people.attach')->middleware(['permission:padges.update']);
    Route::delete('padges/{padge}/people/{person}', [PadgePeopleController::class, 'detach'])->name('padges.people.detach')->middleware(['permission:padges.update']);
    Route::patch('padges/{padge}/people/{person}/toggle', [PadgePeopleController::class, 'toggle'])->name('padges.people.toggle')->middleware(['permission:padges.update']);

    // Breastfeeding routes
    Route::resource('breastfeeding', BreastfeedingController::class)->middleware(['permission:breastfeeding.view|breastfeeding.create|breastfeeding.update|breastfeeding.delete']);
    Route::patch('breastfeeding/{breastfeeding}/toggle-status', [BreastfeedingController::class, 'toggleStatus'])->name('breastfeeding.toggle-status')->middleware(['permission:breastfeeding.update']);
    Route::get('breastfeeding/nursing-mothers/search', [BreastfeedingController::class, 'getNursingMothers'])->name('breastfeeding.nursing-mothers.search')->middleware(['permission:breastfeeding.view']);
    Route::get('breastfeeding/breastfed-children/search', [BreastfeedingController::class, 'getBreastfedChildren'])->name('breastfeeding.breastfed-children.search')->middleware(['permission:breastfeeding.view']);

    // Competitions routes (Admin)
    Route::resource('competitions', \App\Http\Controllers\admin\CompetitionController::class)->names([
        'index' => 'dashboard.competitions.index',
        'create' => 'dashboard.competitions.create',
        'store' => 'dashboard.competitions.store',
        'show' => 'dashboard.competitions.show',
        'edit' => 'dashboard.competitions.edit',
        'update' => 'dashboard.competitions.update',
        'destroy' => 'dashboard.competitions.destroy',
    ]);
    Route::post('competitions/{competition}/create-team', [\App\Http\Controllers\admin\CompetitionController::class, 'createTeamFromIndividuals'])->name('dashboard.competitions.create-team');
    Route::delete('competitions/{competition}/registrations/{user}', [\App\Http\Controllers\admin\CompetitionController::class, 'removeRegistration'])->name('dashboard.competitions.remove-registration');
    Route::delete('competitions/teams/{team}', [\App\Http\Controllers\admin\CompetitionController::class, 'destroyTeam'])->name('dashboard.competitions.teams.destroy');

    // Quran Competitions routes (Admin)
    Route::resource('quran-competitions', \App\Http\Controllers\admin\QuranCompetitionController::class)->names([
        'index' => 'dashboard.quran-competitions.index',
        'create' => 'dashboard.quran-competitions.create',
        'store' => 'dashboard.quran-competitions.store',
        'show' => 'dashboard.quran-competitions.show',
        'edit' => 'dashboard.quran-competitions.edit',
        'update' => 'dashboard.quran-competitions.update',
        'destroy' => 'dashboard.quran-competitions.destroy',
    ]);
    Route::post('quran-competitions/{quranCompetition}/winners', [\App\Http\Controllers\admin\QuranCompetitionController::class, 'addWinner'])->name('dashboard.quran-competitions.add-winner');
    Route::delete('quran-competitions/winners/{winner}', [\App\Http\Controllers\admin\QuranCompetitionController::class, 'removeWinner'])->name('dashboard.quran-competitions.remove-winner');
    Route::post('quran-competitions/{quranCompetition}/media', [\App\Http\Controllers\admin\QuranCompetitionController::class, 'addMedia'])->name('dashboard.quran-competitions.add-media');
    Route::delete('quran-competitions/media/{media}', [\App\Http\Controllers\admin\QuranCompetitionController::class, 'removeMedia'])->name('dashboard.quran-competitions.remove-media');

    // Quran Competition Sections (Admin)
    Route::post('quran-competitions/{quranCompetition}/sections', [\App\Http\Controllers\admin\QuranCompetitionController::class, 'storeSection'])
        ->name('dashboard.quran-competitions.sections.store');
    Route::put('quran-competitions/sections/{section}', [\App\Http\Controllers\admin\QuranCompetitionController::class, 'updateSection'])
        ->name('dashboard.quran-competitions.sections.update');
    Route::delete('quran-competitions/sections/{section}', [\App\Http\Controllers\admin\QuranCompetitionController::class, 'destroySection'])
        ->name('dashboard.quran-competitions.sections.destroy');
    Route::post('quran-competitions/sections/{section}/people', [\App\Http\Controllers\admin\QuranCompetitionController::class, 'attachSectionPeople'])
        ->name('dashboard.quran-competitions.sections.attach-people');
    Route::delete('quran-competitions/sections/{section}/people/{person}', [\App\Http\Controllers\admin\QuranCompetitionController::class, 'detachSectionPerson'])
        ->name('dashboard.quran-competitions.sections.detach-person');

    // Quiz Competitions routes (Admin)
    Route::resource('quiz-competitions', \App\Http\Controllers\admin\QuizCompetitionController::class)->names([
        'index' => 'dashboard.quiz-competitions.index',
        'create' => 'dashboard.quiz-competitions.create',
        'store' => 'dashboard.quiz-competitions.store',
        'show' => 'dashboard.quiz-competitions.show',
        'edit' => 'dashboard.quiz-competitions.edit',
        'update' => 'dashboard.quiz-competitions.update',
        'destroy' => 'dashboard.quiz-competitions.destroy',
    ]);
    Route::post('quiz-competitions/{quizCompetition}/simulate-answers', [\App\Http\Controllers\admin\QuizCompetitionController::class, 'simulateAnswers'])->name('dashboard.quiz-competitions.simulate-answers');
    Route::get('quiz-competitions/{quizCompetition}/export', [\App\Http\Controllers\admin\QuizCompetitionController::class, 'export'])->name('dashboard.quiz-competitions.export');
    Route::get('quiz-competitions/{quizCompetition}/questions/create', [\App\Http\Controllers\admin\QuizQuestionController::class, 'create'])->name('dashboard.quiz-questions.create');
    Route::post('quiz-competitions/{quizCompetition}/questions', [\App\Http\Controllers\admin\QuizQuestionController::class, 'store'])->name('dashboard.quiz-questions.store');
    Route::get('quiz-competitions/{quizCompetition}/questions/{quizQuestion}/edit', [\App\Http\Controllers\admin\QuizQuestionController::class, 'edit'])->name('dashboard.quiz-questions.edit');
    Route::put('quiz-competitions/{quizCompetition}/questions/{quizQuestion}', [\App\Http\Controllers\admin\QuizQuestionController::class, 'update'])->name('dashboard.quiz-questions.update');
    Route::delete('quiz-competitions/{quizCompetition}/questions/{quizQuestion}', [\App\Http\Controllers\admin\QuizQuestionController::class, 'destroy'])->name('dashboard.quiz-questions.destroy');
    Route::post('quiz-competitions/{quizCompetition}/questions/{quizQuestion}/select-winners', [\App\Http\Controllers\admin\QuizQuestionController::class, 'selectWinners'])->name('dashboard.quiz-questions.select-winners');

    // Stories routes
    Route::resource('stories', StoryController::class)->middleware(['permission:stories.view|stories.create|stories.update|stories.delete']);

    // Logs routes
    Route::get('logs/activity', [LogsController::class, 'activity'])->name('logs.activity')->middleware(['permission:logs.view']);
    Route::get('logs/audits',   [LogsController::class, 'audits'])->name('logs.audits')->middleware(['permission:audit.view']);

    // Visit Logs routes
    Route::get('visit-logs', [VisitLogController::class, 'index'])->name('dashboard.visit-logs.index')->middleware(['permission:visit-logs.view']);
    Route::get('visit-logs/{visitLog}', [VisitLogController::class, 'show'])->name('dashboard.visit-logs.show')->middleware(['permission:visit-logs.view']);

    // Site Content routes
    Route::get('site-content', [\App\Http\Controllers\admin\SiteContentController::class, 'index'])->name('dashboard.site-content.index')->middleware(['permission:site-content.view']);
    Route::post('site-content/family-brief', [\App\Http\Controllers\admin\SiteContentController::class, 'updateFamilyBrief'])->name('dashboard.site-content.update-family-brief')->middleware(['permission:site-content.update']);
    Route::post('site-content/whats-new', [\App\Http\Controllers\admin\SiteContentController::class, 'updateWhatsNew'])->name('dashboard.site-content.update-whats-new')->middleware(['permission:site-content.update']);

    // Important Links routes
    Route::get('important-links', [\App\Http\Controllers\admin\ImportantLinkController::class, 'index'])->name('dashboard.important-links.index')->middleware(['permission:site-content.view']);
    Route::post('important-links', [\App\Http\Controllers\admin\ImportantLinkController::class, 'store'])->name('dashboard.important-links.store')->middleware(['permission:site-content.update']);
    Route::put('important-links/{importantLink}', [\App\Http\Controllers\admin\ImportantLinkController::class, 'update'])->name('dashboard.important-links.update')->middleware(['permission:site-content.update']);
    Route::post('important-links/reorder', [\App\Http\Controllers\admin\ImportantLinkController::class, 'reorder'])->name('dashboard.important-links.reorder')->middleware(['permission:site-content.update']);
    Route::delete('important-links/{importantLink}', [\App\Http\Controllers\admin\ImportantLinkController::class, 'destroy'])->name('dashboard.important-links.destroy')->middleware(['permission:site-content.update']);
    Route::post('important-links/{importantLink}/toggle', [\App\Http\Controllers\admin\ImportantLinkController::class, 'toggle'])->name('dashboard.important-links.toggle')->middleware(['permission:site-content.update']);
    Route::post('important-links/{importantLink}/approve', [\App\Http\Controllers\admin\ImportantLinkController::class, 'approve'])->name('dashboard.important-links.approve')->middleware(['permission:site-content.update']);
    Route::post('important-links/{importantLink}/reject', [\App\Http\Controllers\admin\ImportantLinkController::class, 'reject'])->name('dashboard.important-links.reject')->middleware(['permission:site-content.update']);

    // Site Password Settings routes
    Route::get('site-password-settings', [\App\Http\Controllers\admin\SitePasswordSettingsController::class, 'index'])->name('dashboard.site-password-settings.index')->middleware(['permission:site-content.view']);
    Route::post('site-password-settings', [\App\Http\Controllers\admin\SitePasswordSettingsController::class, 'update'])->name('dashboard.site-password-settings.update')->middleware(['permission:site-content.update']);

    // Slideshow routes
    Route::get('slideshow', [\App\Http\Controllers\admin\SiteContentController::class, 'slideshow'])->name('dashboard.slideshow.index')->middleware(['permission:slideshow.view']);
    Route::get('slideshow/{slideshowImage}', [\App\Http\Controllers\admin\SiteContentController::class, 'getSlideshowImage'])->name('dashboard.slideshow.show')->middleware(['permission:slideshow.view']);
    Route::post('slideshow/add', [\App\Http\Controllers\admin\SiteContentController::class, 'addSlideshowImage'])->name('dashboard.slideshow.add')->middleware(['permission:slideshow.create']);
    Route::post('slideshow/{slideshowImage}/update', [\App\Http\Controllers\admin\SiteContentController::class, 'updateSlideshowImage'])->name('dashboard.slideshow.update')->middleware(['permission:slideshow.update']);
    Route::post('slideshow/reorder', [\App\Http\Controllers\admin\SiteContentController::class, 'reorderSlideshow'])->name('dashboard.slideshow.reorder')->middleware(['permission:slideshow.update']);
    Route::delete('slideshow/{slideshowImage}', [\App\Http\Controllers\admin\SiteContentController::class, 'removeSlideshowImage'])->name('dashboard.slideshow.remove')->middleware(['permission:slideshow.delete']);
    Route::post('slideshow/{slideshowImage}/toggle', [\App\Http\Controllers\admin\SiteContentController::class, 'toggleSlideshowImage'])->name('dashboard.slideshow.toggle')->middleware(['permission:slideshow.update']);

    // Home Gallery routes
    Route::get('home-gallery', [\App\Http\Controllers\admin\SiteContentController::class, 'homeGallery'])->name('dashboard.home-gallery.index')->middleware(['permission:home-gallery.view']);
    Route::post('home-gallery/add', [\App\Http\Controllers\admin\SiteContentController::class, 'addHomeGalleryImage'])->name('dashboard.home-gallery.add')->middleware(['permission:home-gallery.create']);
    Route::post('home-gallery/{homeGalleryImage}/update', [\App\Http\Controllers\admin\SiteContentController::class, 'updateHomeGalleryImage'])->name('dashboard.home-gallery.update')->middleware(['permission:home-gallery.update']);
    Route::post('home-gallery/reorder', [\App\Http\Controllers\admin\SiteContentController::class, 'reorderHomeGallery'])->name('dashboard.home-gallery.reorder')->middleware(['permission:home-gallery.update']);
    Route::delete('home-gallery/{homeGalleryImage}', [\App\Http\Controllers\admin\SiteContentController::class, 'removeHomeGalleryImage'])->name('dashboard.home-gallery.remove')->middleware(['permission:home-gallery.delete']);
    Route::post('home-gallery/{homeGalleryImage}/toggle', [\App\Http\Controllers\admin\SiteContentController::class, 'toggleHomeGalleryImage'])->name('dashboard.home-gallery.toggle')->middleware(['permission:home-gallery.update']);

    // Home Sections routes (Dynamic Sections)
    Route::get('home-sections', [\App\Http\Controllers\admin\HomeSectionController::class, 'index'])->name('dashboard.home-sections.index')->middleware(['permission:site-content.view']);
    Route::get('home-sections/create', [\App\Http\Controllers\admin\HomeSectionController::class, 'create'])->name('dashboard.home-sections.create')->middleware(['permission:site-content.create']);
    Route::post('home-sections', [\App\Http\Controllers\admin\HomeSectionController::class, 'store'])->name('dashboard.home-sections.store')->middleware(['permission:site-content.create']);
    Route::get('home-sections/{homeSection}/edit', [\App\Http\Controllers\admin\HomeSectionController::class, 'edit'])->name('dashboard.home-sections.edit')->middleware(['permission:site-content.view']);
    Route::post('home-sections/{homeSection}', [\App\Http\Controllers\admin\HomeSectionController::class, 'update'])->name('dashboard.home-sections.update')->middleware(['permission:site-content.update']);
    Route::delete('home-sections/{homeSection}', [\App\Http\Controllers\admin\HomeSectionController::class, 'destroy'])->name('dashboard.home-sections.destroy')->middleware(['permission:site-content.delete']);
    Route::post('home-sections/reorder', [\App\Http\Controllers\admin\HomeSectionController::class, 'reorder'])->name('dashboard.home-sections.reorder')->middleware(['permission:site-content.update']);
    Route::post('home-sections/{homeSection}/toggle', [\App\Http\Controllers\admin\HomeSectionController::class, 'toggle'])->name('dashboard.home-sections.toggle')->middleware(['permission:site-content.update']);
    Route::post('home-sections/{homeSection}/duplicate', [\App\Http\Controllers\admin\HomeSectionController::class, 'duplicate'])->name('dashboard.home-sections.duplicate')->middleware(['permission:site-content.create']);

    // Home Section Items routes
    Route::get('home-sections/{homeSection}/items/{homeSectionItem}', [\App\Http\Controllers\admin\HomeSectionItemController::class, 'show'])->name('dashboard.home-section-items.show')->middleware(['permission:site-content.view']);
    Route::post('home-sections/{homeSection}/items', [\App\Http\Controllers\admin\HomeSectionItemController::class, 'store'])->name('dashboard.home-section-items.store')->middleware(['permission:site-content.create']);
    Route::post('home-sections/{homeSection}/items/{homeSectionItem}', [\App\Http\Controllers\admin\HomeSectionItemController::class, 'update'])->name('dashboard.home-section-items.update')->middleware(['permission:site-content.update']);
    Route::delete('home-sections/{homeSection}/items/{homeSectionItem}', [\App\Http\Controllers\admin\HomeSectionItemController::class, 'destroy'])->name('dashboard.home-section-items.destroy')->middleware(['permission:site-content.delete']);
    Route::post('home-sections/{homeSection}/items/reorder', [\App\Http\Controllers\admin\HomeSectionItemController::class, 'reorder'])->name('dashboard.home-section-items.reorder')->middleware(['permission:site-content.update']);

    // Courses routes
    Route::get('courses', [\App\Http\Controllers\admin\CourseController::class, 'index'])->name('dashboard.courses.index')->middleware(['permission:courses.view']);
    Route::post('courses', [\App\Http\Controllers\admin\CourseController::class, 'store'])->name('dashboard.courses.store')->middleware(['permission:courses.create']);
    Route::get('courses/{course}', [\App\Http\Controllers\admin\CourseController::class, 'show'])->name('dashboard.courses.show')->middleware(['permission:courses.view']);
    Route::post('courses/{course}/update', [\App\Http\Controllers\admin\CourseController::class, 'update'])->name('dashboard.courses.update')->middleware(['permission:courses.update']);
    Route::post('courses/reorder', [\App\Http\Controllers\admin\CourseController::class, 'reorder'])->name('dashboard.courses.reorder')->middleware(['permission:courses.update']);
    Route::delete('courses/{course}', [\App\Http\Controllers\admin\CourseController::class, 'destroy'])->name('dashboard.courses.destroy')->middleware(['permission:courses.delete']);
    Route::post('courses/{course}/toggle', [\App\Http\Controllers\admin\CourseController::class, 'toggle'])->name('dashboard.courses.toggle')->middleware(['permission:courses.update']);

    // Programs routes
    Route::get('programs', [\App\Http\Controllers\admin\ProgramController::class, 'index'])->name('dashboard.programs.index')->middleware(['permission:programs.view']);
    Route::post('programs', [\App\Http\Controllers\admin\ProgramController::class, 'store'])->name('dashboard.programs.store')->middleware(['permission:programs.create']);
    Route::get('programs/{program}', [\App\Http\Controllers\admin\ProgramController::class, 'show'])->name('dashboard.programs.show')->middleware(['permission:programs.view']);
    Route::post('programs/{program}/update', [\App\Http\Controllers\admin\ProgramController::class, 'update'])->name('dashboard.programs.update')->middleware(['permission:programs.update']);
    Route::post('programs/reorder', [\App\Http\Controllers\admin\ProgramController::class, 'reorder'])->name('dashboard.programs.reorder')->middleware(['permission:programs.update']);
    Route::post('programs/{program}/toggle', [\App\Http\Controllers\admin\ProgramController::class, 'toggle'])->name('dashboard.programs.toggle')->middleware(['permission:programs.update']);
    Route::delete('programs/{program}', [\App\Http\Controllers\admin\ProgramController::class, 'destroy'])->name('dashboard.programs.destroy')->middleware(['permission:programs.delete']);
    Route::get('programs/{program}/manage', [\App\Http\Controllers\admin\ProgramController::class, 'manage'])->name('dashboard.programs.manage')->middleware(['permission:programs.view']);
    Route::post('programs/{program}/media', [\App\Http\Controllers\admin\ProgramController::class, 'storeMedia'])->name('dashboard.programs.media.store')->middleware(['permission:programs.update']);
    Route::post('programs/{program}/media/{media}/update', [\App\Http\Controllers\admin\ProgramController::class, 'updateMedia'])->name('dashboard.programs.media.update')->middleware(['permission:programs.update']);
    Route::delete('programs/{program}/media/{media}', [\App\Http\Controllers\admin\ProgramController::class, 'destroyMedia'])->name('dashboard.programs.media.destroy')->middleware(['permission:programs.update']);
    Route::post('programs/{program}/media/reorder', [\App\Http\Controllers\admin\ProgramController::class, 'reorderMedia'])->name('dashboard.programs.media.reorder')->middleware(['permission:programs.update']);
    Route::post('programs/{program}/links', [\App\Http\Controllers\admin\ProgramController::class, 'storeLink'])->name('dashboard.programs.links.store')->middleware(['permission:programs.update']);
    Route::delete('programs/{program}/links/{link}', [\App\Http\Controllers\admin\ProgramController::class, 'destroyLink'])->name('dashboard.programs.links.destroy')->middleware(['permission:programs.update']);
    Route::post('programs/{program}/links/reorder', [\App\Http\Controllers\admin\ProgramController::class, 'reorderLinks'])->name('dashboard.programs.links.reorder')->middleware(['permission:programs.update']);
    // Gallery routes
    Route::post('programs/{program}/galleries', [\App\Http\Controllers\admin\ProgramController::class, 'storeGallery'])->name('dashboard.programs.galleries.store')->middleware(['permission:programs.update']);
    Route::post('programs/{program}/galleries/{gallery}/update', [\App\Http\Controllers\admin\ProgramController::class, 'updateGallery'])->name('dashboard.programs.galleries.update')->middleware(['permission:programs.update']);
    Route::delete('programs/{program}/galleries/{gallery}', [\App\Http\Controllers\admin\ProgramController::class, 'destroyGallery'])->name('dashboard.programs.galleries.destroy')->middleware(['permission:programs.update']);
    Route::post('programs/{program}/galleries/{gallery}/media', [\App\Http\Controllers\admin\ProgramController::class, 'storeGalleryMedia'])->name('dashboard.programs.galleries.media.store')->middleware(['permission:programs.update']);
    Route::post('programs/{program}/galleries/{gallery}/media/{media}/update', [\App\Http\Controllers\admin\ProgramController::class, 'updateGalleryMedia'])->name('dashboard.programs.galleries.media.update')->middleware(['permission:programs.update']);
    Route::delete('programs/{program}/galleries/{gallery}/media/{media}', [\App\Http\Controllers\admin\ProgramController::class, 'destroyGalleryMedia'])->name('dashboard.programs.galleries.media.destroy')->middleware(['permission:programs.update']);
    // Sub-Programs routes
    Route::post('programs/{program}/sub-programs', [\App\Http\Controllers\admin\ProgramController::class, 'attachSubProgram'])->name('dashboard.programs.sub-programs.attach')->middleware(['permission:programs.update']);
    Route::delete('programs/{program}/sub-programs/{subProgram}', [\App\Http\Controllers\admin\ProgramController::class, 'detachSubProgram'])->name('dashboard.programs.sub-programs.detach')->middleware(['permission:programs.update']);
    Route::post('programs/{program}/sub-programs/reorder', [\App\Http\Controllers\admin\ProgramController::class, 'reorderSubPrograms'])->name('dashboard.programs.sub-programs.reorder')->middleware(['permission:programs.update']);

    // Proud Of routes
    Route::get('proud-of', [\App\Http\Controllers\admin\ProudOfController::class, 'index'])->name('dashboard.proud-of.index')->middleware(['permission:programs.view']);
    Route::post('proud-of', [\App\Http\Controllers\admin\ProudOfController::class, 'store'])->name('dashboard.proud-of.store')->middleware(['permission:programs.create']);
    Route::get('proud-of/{item}', [\App\Http\Controllers\admin\ProudOfController::class, 'show'])->name('dashboard.proud-of.show')->middleware(['permission:programs.view']);
    Route::post('proud-of/{item}/update', [\App\Http\Controllers\admin\ProudOfController::class, 'update'])->name('dashboard.proud-of.update')->middleware(['permission:programs.update']);
    Route::post('proud-of/reorder', [\App\Http\Controllers\admin\ProudOfController::class, 'reorder'])->name('dashboard.proud-of.reorder')->middleware(['permission:programs.update']);
    Route::post('proud-of/{item}/toggle', [\App\Http\Controllers\admin\ProudOfController::class, 'toggle'])->name('dashboard.proud-of.toggle')->middleware(['permission:programs.update']);
    Route::delete('proud-of/{item}', [\App\Http\Controllers\admin\ProudOfController::class, 'destroy'])->name('dashboard.proud-of.destroy')->middleware(['permission:programs.delete']);
    Route::get('proud-of/{item}/manage', [\App\Http\Controllers\admin\ProudOfController::class, 'manage'])->name('dashboard.proud-of.manage')->middleware(['permission:programs.view']);
    Route::post('proud-of/{item}/media', [\App\Http\Controllers\admin\ProudOfController::class, 'storeMedia'])->name('dashboard.proud-of.media.store')->middleware(['permission:programs.update']);
    Route::post('proud-of/{item}/media/{media}/update', [\App\Http\Controllers\admin\ProudOfController::class, 'updateMedia'])->name('dashboard.proud-of.media.update')->middleware(['permission:programs.update']);
    Route::delete('proud-of/{item}/media/{media}', [\App\Http\Controllers\admin\ProudOfController::class, 'destroyMedia'])->name('dashboard.proud-of.media.destroy')->middleware(['permission:programs.update']);
    Route::post('proud-of/{item}/media/reorder', [\App\Http\Controllers\admin\ProudOfController::class, 'reorderMedia'])->name('dashboard.proud-of.media.reorder')->middleware(['permission:programs.update']);
    Route::post('proud-of/{item}/links', [\App\Http\Controllers\admin\ProudOfController::class, 'storeLink'])->name('dashboard.proud-of.links.store')->middleware(['permission:programs.update']);
    Route::delete('proud-of/{item}/links/{link}', [\App\Http\Controllers\admin\ProudOfController::class, 'destroyLink'])->name('dashboard.proud-of.links.destroy')->middleware(['permission:programs.update']);
    Route::post('proud-of/{item}/links/reorder', [\App\Http\Controllers\admin\ProudOfController::class, 'reorderLinks'])->name('dashboard.proud-of.links.reorder')->middleware(['permission:programs.update']);
    Route::post('proud-of/{item}/galleries', [\App\Http\Controllers\admin\ProudOfController::class, 'storeGallery'])->name('dashboard.proud-of.galleries.store')->middleware(['permission:programs.update']);
    Route::post('proud-of/{item}/galleries/{gallery}/update', [\App\Http\Controllers\admin\ProudOfController::class, 'updateGallery'])->name('dashboard.proud-of.galleries.update')->middleware(['permission:programs.update']);
    Route::delete('proud-of/{item}/galleries/{gallery}', [\App\Http\Controllers\admin\ProudOfController::class, 'destroyGallery'])->name('dashboard.proud-of.galleries.destroy')->middleware(['permission:programs.update']);
    Route::post('proud-of/{item}/galleries/{gallery}/media', [\App\Http\Controllers\admin\ProudOfController::class, 'storeGalleryMedia'])->name('dashboard.proud-of.galleries.media.store')->middleware(['permission:programs.update']);
    Route::post('proud-of/{item}/galleries/{gallery}/media/{media}/update', [\App\Http\Controllers\admin\ProudOfController::class, 'updateGalleryMedia'])->name('dashboard.proud-of.galleries.media.update')->middleware(['permission:programs.update']);
    Route::delete('proud-of/{item}/galleries/{gallery}/media/{media}', [\App\Http\Controllers\admin\ProudOfController::class, 'destroyGalleryMedia'])->name('dashboard.proud-of.galleries.media.destroy')->middleware(['permission:programs.update']);

    // Family Councils routes
    Route::get('councils', [\App\Http\Controllers\admin\FamilyCouncilController::class, 'index'])->name('dashboard.councils.index')->middleware(['permission:councils.view']);
    Route::post('councils', [\App\Http\Controllers\admin\FamilyCouncilController::class, 'store'])->name('dashboard.councils.store')->middleware(['permission:councils.create']);
    Route::get('councils/{council}', [\App\Http\Controllers\admin\FamilyCouncilController::class, 'show'])->name('dashboard.councils.show')->middleware(['permission:councils.view']);
    Route::post('councils/{council}/update', [\App\Http\Controllers\admin\FamilyCouncilController::class, 'update'])->name('dashboard.councils.update')->middleware(['permission:councils.update']);
    Route::post('councils/reorder', [\App\Http\Controllers\admin\FamilyCouncilController::class, 'reorder'])->name('dashboard.councils.reorder')->middleware(['permission:councils.update']);
    Route::delete('councils/{council}', [\App\Http\Controllers\admin\FamilyCouncilController::class, 'destroy'])->name('dashboard.councils.destroy')->middleware(['permission:councils.delete']);

    // Family Events routes
    Route::get('events', [\App\Http\Controllers\admin\FamilyEventController::class, 'index'])->name('dashboard.events.index')->middleware(['permission:councils.view']);
    Route::post('events', [\App\Http\Controllers\admin\FamilyEventController::class, 'store'])->name('dashboard.events.store')->middleware(['permission:councils.create']);
    Route::get('events/{event}', [\App\Http\Controllers\admin\FamilyEventController::class, 'show'])->name('dashboard.events.show')->middleware(['permission:councils.view']);
    Route::post('events/{event}/update', [\App\Http\Controllers\admin\FamilyEventController::class, 'update'])->name('dashboard.events.update')->middleware(['permission:councils.update']);
    Route::post('events/reorder', [\App\Http\Controllers\admin\FamilyEventController::class, 'reorder'])->name('dashboard.events.reorder')->middleware(['permission:councils.update']);
    Route::delete('events/{event}', [\App\Http\Controllers\admin\FamilyEventController::class, 'destroy'])->name('dashboard.events.destroy')->middleware(['permission:councils.delete']);
    Route::get('councils/{council}/manage', [\App\Http\Controllers\admin\FamilyCouncilController::class, 'manage'])->name('dashboard.councils.manage')->middleware(['permission:councils.view']);
    Route::post('councils/{council}/images', [\App\Http\Controllers\admin\FamilyCouncilController::class, 'storeImage'])->name('dashboard.councils.images.store')->middleware(['permission:councils.update']);
    Route::delete('councils/{council}/images/{image}', [\App\Http\Controllers\admin\FamilyCouncilController::class, 'destroyImage'])->name('dashboard.councils.images.destroy')->middleware(['permission:councils.update']);
    Route::post('councils/{council}/images/reorder', [\App\Http\Controllers\admin\FamilyCouncilController::class, 'reorderImages'])->name('dashboard.councils.images.reorder')->middleware(['permission:councils.update']);

    // Family News routes
    Route::get('family-news', [\App\Http\Controllers\admin\FamilyNewsController::class, 'index'])->name('dashboard.family-news.index')->middleware(['permission:councils.view']);
    Route::get('family-news/create', [\App\Http\Controllers\admin\FamilyNewsController::class, 'create'])->name('dashboard.family-news.create')->middleware(['permission:councils.create']);
    Route::post('family-news', [\App\Http\Controllers\admin\FamilyNewsController::class, 'store'])->name('dashboard.family-news.store')->middleware(['permission:councils.create']);
    Route::get('family-news/{familyNews}/edit', [\App\Http\Controllers\admin\FamilyNewsController::class, 'edit'])->name('dashboard.family-news.edit')->middleware(['permission:councils.update']);
    Route::put('family-news/{familyNews}', [\App\Http\Controllers\admin\FamilyNewsController::class, 'update'])->name('dashboard.family-news.update')->middleware(['permission:councils.update']);
    Route::delete('family-news/{familyNews}', [\App\Http\Controllers\admin\FamilyNewsController::class, 'destroy'])->name('dashboard.family-news.destroy')->middleware(['permission:councils.delete']);
    Route::post('family-news/reorder', [\App\Http\Controllers\admin\FamilyNewsController::class, 'reorder'])->name('dashboard.family-news.reorder')->middleware(['permission:councils.update']);
    Route::post('family-news/{familyNews}/toggle', [\App\Http\Controllers\admin\FamilyNewsController::class, 'toggle'])->name('dashboard.family-news.toggle')->middleware(['permission:councils.update']);
    Route::post('family-news/{familyNews}/add-image', [\App\Http\Controllers\admin\FamilyNewsController::class, 'addImage'])->name('dashboard.family-news.add-image')->middleware(['permission:councils.update']);
    Route::delete('family-news/images/{image}', [\App\Http\Controllers\admin\FamilyNewsController::class, 'deleteImage'])->name('dashboard.family-news.delete-image')->middleware(['permission:councils.update']);
    Route::post('family-news/{familyNews}/reorder-images', [\App\Http\Controllers\admin\FamilyNewsController::class, 'reorderImages'])->name('dashboard.family-news.reorder-images')->middleware(['permission:councils.update']);

    // Products Store routes
    Route::prefix('products')->name('products.')->middleware(['permission:products.view|products.create|products.update|products.delete'])->group(function () {
        // Categories
        Route::get('categories', [\App\Http\Controllers\admin\ProductCategoryController::class, 'index'])->name('categories.index');
        Route::post('categories', [\App\Http\Controllers\admin\ProductCategoryController::class, 'store'])->name('categories.store');
        Route::put('categories/{category}', [\App\Http\Controllers\admin\ProductCategoryController::class, 'update'])->name('categories.update');
        Route::delete('categories/{category}', [\App\Http\Controllers\admin\ProductCategoryController::class, 'destroy'])->name('categories.destroy');
        Route::post('categories/{category}/toggle', [\App\Http\Controllers\admin\ProductCategoryController::class, 'toggle'])->name('categories.toggle');
        Route::post('categories/reorder', [\App\Http\Controllers\admin\ProductCategoryController::class, 'reorder'])->name('categories.reorder');

        // Subcategories
        Route::get('subcategories', [\App\Http\Controllers\admin\ProductSubcategoryController::class, 'index'])->name('subcategories.index');
        Route::get('subcategories/by-category/{category}', [\App\Http\Controllers\admin\ProductSubcategoryController::class, 'getByCategory'])->name('subcategories.by-category');
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

    // Rental Requests Management
    Route::prefix('rental-requests')->name('dashboard.rental-requests.')->group(function () {
        Route::get('/', [\App\Http\Controllers\admin\RentalRequestAdminController::class, 'index'])->name('index');
        Route::get('{request}', [\App\Http\Controllers\admin\RentalRequestAdminController::class, 'show'])->name('show');
        Route::patch('{request}/status', [\App\Http\Controllers\admin\RentalRequestAdminController::class, 'updateStatus'])->name('update-status');
    });

    // Health Websites Management
    Route::resource('health-websites', \App\Http\Controllers\admin\HealthWebsiteAdminController::class)->names([
        'index' => 'dashboard.health-websites.index',
        'create' => 'dashboard.health-websites.create',
        'store' => 'dashboard.health-websites.store',
        'show' => 'dashboard.health-websites.show',
        'edit' => 'dashboard.health-websites.edit',
        'update' => 'dashboard.health-websites.update',
        'destroy' => 'dashboard.health-websites.destroy',
    ]);
    Route::post('health-websites/{healthWebsite}/toggle', [\App\Http\Controllers\admin\HealthWebsiteAdminController::class, 'toggle'])->name('dashboard.health-websites.toggle');

    // Walking Program routes
    Route::get('walking', [\App\Http\Controllers\admin\WalkingProgramController::class, 'index'])->name('dashboard.walking.index')->middleware(['permission:walking-program.view']);
    Route::get('walking/create', [\App\Http\Controllers\admin\WalkingProgramController::class, 'create'])->name('dashboard.walking.create')->middleware(['permission:walking-program.create']);
    Route::post('walking', [\App\Http\Controllers\admin\WalkingProgramController::class, 'store'])->name('dashboard.walking.store')->middleware(['permission:walking-program.create']);
    Route::get('walking/{walking}/edit', [\App\Http\Controllers\admin\WalkingProgramController::class, 'edit'])->name('dashboard.walking.edit')->middleware(['permission:walking-program.update']);
    Route::put('walking/{walking}', [\App\Http\Controllers\admin\WalkingProgramController::class, 'update'])->name('dashboard.walking.update')->middleware(['permission:walking-program.update']);
    Route::delete('walking/{walking}', [\App\Http\Controllers\admin\WalkingProgramController::class, 'destroy'])->name('dashboard.walking.destroy')->middleware(['permission:walking-program.delete']);

    // Notifications (الاشعارات) - WhatsApp / UltraMSG
    Route::prefix('notifications')->name('dashboard.notifications.')->middleware(['permission:notifications.view'])->group(function () {
        Route::get('/', [\App\Http\Controllers\admin\NotificationController::class, 'index'])->name('index');
        Route::get('send', [\App\Http\Controllers\admin\NotificationController::class, 'send'])->name('send')->middleware(['permission:notifications.send']);
        Route::post('send', [\App\Http\Controllers\admin\NotificationController::class, 'sendSubmit'])->name('send.submit')->middleware(['permission:notifications.send']);
        Route::get('logs', [\App\Http\Controllers\admin\NotificationController::class, 'logs'])->name('logs');
        Route::get('persons-with-whatsapp', [\App\Http\Controllers\admin\NotificationController::class, 'personsWithWhatsApp'])->name('persons-with-whatsapp');
        Route::post('preview-message', [\App\Http\Controllers\admin\NotificationController::class, 'previewMessage'])->name('preview-message')->middleware(['permission:notifications.send']);
    });
    Route::prefix('notification-groups')->name('dashboard.notification-groups.')->middleware(['permission:notifications.manage-groups'])->group(function () {
        Route::get('/', [\App\Http\Controllers\admin\NotificationController::class, 'groupsIndex'])->name('index');
        Route::post('/', [\App\Http\Controllers\admin\NotificationController::class, 'groupsStore'])->name('store');
        Route::get('create', [\App\Http\Controllers\admin\NotificationController::class, 'groupsCreate'])->name('create');
        Route::get('{notificationGroup}/edit', [\App\Http\Controllers\admin\NotificationController::class, 'groupsEdit'])->name('edit');
        Route::put('{notificationGroup}', [\App\Http\Controllers\admin\NotificationController::class, 'groupsUpdate'])->name('update');
        Route::delete('{notificationGroup}', [\App\Http\Controllers\admin\NotificationController::class, 'groupsDestroy'])->name('destroy');
        Route::get('{notificationGroup}/persons', [\App\Http\Controllers\admin\NotificationController::class, 'groupsPersons'])->name('persons');
        Route::post('{notificationGroup}/persons', [\App\Http\Controllers\admin\NotificationController::class, 'groupsAttachPerson'])->name('attach-person');
        Route::delete('{notificationGroup}/persons/{person}', [\App\Http\Controllers\admin\NotificationController::class, 'groupsDetachPerson'])->name('detach-person');
    });
});

require __DIR__ . '/auth.php';

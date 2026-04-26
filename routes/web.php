<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ParameterController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\RegionController;
use App\Http\Controllers\Admin\TranslationController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Marketplace\AssortimentController;
use App\Http\Controllers\Marketplace\CompanyController as MarketplaceCompanyController;
use App\Http\Controllers\Marketplace\DashboardController as MarketplaceDashboard;
use App\Http\Controllers\Marketplace\OnboardingController;
use App\Http\Controllers\Marketplace\TeamController;
use App\Http\Controllers\Producer\DashboardController as ProducerDashboard;
use App\Http\Controllers\Producer\PartnerController;
use App\Http\Controllers\Producer\SerialController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// ── Public ────────────────────────────────────────────────
Route::get('/', HomeController::class)->name('home');
Route::get('/catalog',                          [CatalogController::class, 'index'])->name('catalog');
Route::get('/catalog/{slug}',                   [CatalogController::class, 'category'])->name('catalog.category');
Route::get('/catalog/{category}/{slug}',        [CatalogController::class, 'show'])->name('products.show');

// ── Profile (all authenticated roles) ────────────────────
Route::middleware('auth')->group(function () {
    Route::get('profile',  [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile',  [ProfileController::class, 'update'])->name('profile.update');
});

// ── Auth ──────────────────────────────────────────────────
Auth::routes(['verify' => false, 'confirm' => false]);

// ── Language switcher ─────────────────────────────────────
Route::get('/lang/{lang}', function (string $lang) {
    if (in_array($lang, ['uz', 'ru', 'en'])) {
        if (auth()->check()) {
            auth()->user()->update(['lang' => $lang]);
        }
        session(['lang' => $lang]);
    }
    return redirect()->back();
})->name('lang.switch')->where('lang', 'uz|ru|en');

// ── Admin ─────────────────────────────────────────────────
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {

        Route::get('/', DashboardController::class)->name('dashboard');

        // Profile (redirects to shared profile route)
        Route::get('/profile', fn() => redirect()->route('profile.edit'))->name('profile');

        // Geography
        Route::resource('countries',  CountryController::class);
        Route::resource('regions',    RegionController::class);
        Route::resource('cities',     CityController::class);

        // Catalog
        Route::resource('categories', CategoryController::class);
        Route::post(  'categories/{category}/parameters',             [CategoryController::class, 'attachParameter'])->name('categories.parameters.attach');
        Route::delete('categories/{category}/parameters/{parameter}', [CategoryController::class, 'detachParameter'])->name('categories.parameters.detach');
        Route::resource('parameters', ParameterController::class);
        Route::resource('products',   ProductController::class);

        // Companies
        Route::get('companies/pending',             [CompanyController::class, 'pending'])->name('companies.pending');
        Route::patch('companies/{company}/approve', [CompanyController::class, 'approve'])->name('companies.approve');
        Route::patch('companies/{company}/reject',  [CompanyController::class, 'reject'])->name('companies.reject');
        Route::resource('companies', CompanyController::class);

        // Users
        Route::resource('users', UserController::class);

        // UI Translations
        Route::get('translations',          [TranslationController::class, 'index'])->name('translations.index');
        Route::post('translations',         [TranslationController::class, 'store'])->name('translations.store');
        Route::post('translations/add-key', [TranslationController::class, 'addKey'])->name('translations.add-key');
        Route::delete('translations/key',   [TranslationController::class, 'destroyKey'])->name('translations.destroy-key');
    });

// ── Marketplace ───────────────────────────────────────────
// Onboarding (owner without a company yet)
Route::prefix('marketplace')->name('marketplace.')->middleware(['auth', 'role:owner'])->group(function () {
    Route::get('onboarding',  [OnboardingController::class, 'show'])->name('onboarding');
    Route::post('onboarding', [OnboardingController::class, 'store'])->name('onboarding.store');
    Route::get('pending',     fn() => view('marketplace.pending'))->name('pending');
});

// Marketplace main (owner + user, company must be approved)
Route::prefix('marketplace')->name('marketplace.')->middleware(['auth', 'role:owner,user', 'company.approved'])->group(function () {
    Route::get('/', MarketplaceDashboard::class)->name('dashboard');

    // Assortiment (owner + user)
    Route::get('assortiment',                        [AssortimentController::class, 'index'])->name('assortiment.index');
    Route::get('assortiment/create',                 [AssortimentController::class, 'create'])->name('assortiment.create');
    Route::post('assortiment',                       [AssortimentController::class, 'store'])->name('assortiment.store');
    Route::get('assortiment/{companyProduct}/edit',  [AssortimentController::class, 'edit'])->name('assortiment.edit');
    Route::put('assortiment/{companyProduct}',       [AssortimentController::class, 'update'])->name('assortiment.update');
    Route::delete('assortiment/{companyProduct}',    [AssortimentController::class, 'destroy'])->name('assortiment.destroy');

    // Team management — owner only
    Route::middleware('role:owner')->group(function () {
        Route::get('team',             [TeamController::class, 'index'])->name('team.index');
        Route::get('team/create',      [TeamController::class, 'create'])->name('team.create');
        Route::post('team',            [TeamController::class, 'store'])->name('team.store');
        Route::delete('team/{user}',   [TeamController::class, 'destroy'])->name('team.destroy');

        // Company profile
        Route::get('company',                         [MarketplaceCompanyController::class, 'show'])->name('company.show');
        Route::put('company',                         [MarketplaceCompanyController::class, 'update'])->name('company.update');
        Route::post('company/address',                [MarketplaceCompanyController::class, 'storeAddress'])->name('company.address.store');
        Route::delete('company/address/{address}',   [MarketplaceCompanyController::class, 'destroyAddress'])->name('company.address.destroy');
    });
});

// ── Producer ──────────────────────────────────────────────
Route::prefix('producer')->name('producer.')->middleware(['auth', 'role:producer'])->group(function () {
    Route::get('/', ProducerDashboard::class)->name('dashboard');

    // Serials
    Route::get('serials',         [SerialController::class, 'index'])->name('serials.index');
    Route::get('serials/import',  [SerialController::class, 'import'])->name('serials.import');
    Route::post('serials/import', [SerialController::class, 'store'])->name('serials.store');

    // Partners (manufacturer_status only)
    Route::get('partners',                    [PartnerController::class, 'index'])->name('partners.index');
    Route::patch('partners/{company}/status', [PartnerController::class, 'updateStatus'])->name('partners.status');
});

// ── Role-based catch-all redirects ────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/home', fn() => redirect(\App\Models\User::findOrFail(Auth::id())->redirectPath()))->name('home.redirect');
});

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
use App\Http\Controllers\Marketplace\DashboardController as MarketplaceDashboard;
use App\Http\Controllers\Marketplace\OnboardingController;
use App\Http\Controllers\Marketplace\TeamController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// ── Public ────────────────────────────────────────────────
Route::get('/', fn() => view('welcome'))->name('home');

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

    // Team management — owner only
    Route::middleware('role:owner')->group(function () {
        Route::get('team',             [TeamController::class, 'index'])->name('team.index');
        Route::get('team/create',      [TeamController::class, 'create'])->name('team.create');
        Route::post('team',            [TeamController::class, 'store'])->name('team.store');
        Route::delete('team/{user}',   [TeamController::class, 'destroy'])->name('team.destroy');
    });
});

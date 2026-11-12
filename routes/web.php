<?php

use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ChatbotController;
use App\Http\Controllers\Admin\ClientMessageController;
use App\Http\Controllers\Admin\FileController;
use App\Http\Controllers\Admin\ProductFileController;
use App\Http\Controllers\Admin\HelpController;
use App\Http\Controllers\Admin\HomeContentController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ParameterController;
use App\Http\Controllers\Admin\ParameterValueController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\RegionController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\TranslationController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\TwoFactorController;
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
use App\Http\Controllers\PageController;
use App\Http\Controllers\PartnerDirectoryController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TelegramAppController;
use App\Http\Controllers\TelegramWebhookController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// ── Public ────────────────────────────────────────────────
// No browser-level page caching here on purpose: every one of these pages renders
// session-dependent content (locale, logged-in nav state), so a shared/public
// Cache-Control would serve visitors a stale language or wrong auth state for
// up to max-age after switching. The expensive parts (category/product queries)
// are already cached server-side via Cache::remember() in the controllers.
Route::get('/', HomeController::class)->name('home');
Route::get('/catalog',                   [CatalogController::class, 'index'])->name('catalog');
Route::get('/catalog/{slug}',            [CatalogController::class, 'category'])->name('catalog.category');
Route::get('/catalog/{category}/{slug}', [CatalogController::class, 'show'])->name('products.show');
Route::get('/about',                     [PageController::class, 'about'])->name('about');
Route::get('/contact',                   [PageController::class, 'contact'])->name('contact');
Route::get('/partners',                  [PartnerDirectoryController::class, 'index'])->name('partners.index');
Route::get('/partners/{slug}',           [PartnerDirectoryController::class, 'show'])->name('partners.show');
Route::get('/sitemap.xml',               [SitemapController::class, 'index'])->name('sitemap');

// ── Telegram bot ──────────────────────────────────────────
Route::post('/telegram/webhook', [TelegramWebhookController::class, 'handle'])->name('telegram.webhook')->middleware('throttle:120,1');
Route::get('/telegram/app',      [TelegramAppController::class, 'index'])->name('telegram.app');

// ── Two-factor challenge (auth only — must stay outside the 2fa middleware) ──
Route::middleware('auth')->group(function () {
    Route::get('two-factor/challenge',  [TwoFactorController::class, 'challenge'])->name('two-factor.challenge');
    Route::post('two-factor/challenge', [TwoFactorController::class, 'verify'])->name('two-factor.verify')->middleware('throttle:5,1');
});

// ── Profile (all authenticated roles) ────────────────────
Route::middleware(['auth', '2fa'])->group(function () {
    Route::get('profile',  [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile',  [ProfileController::class, 'update'])->name('profile.update');

    Route::get('two-factor',    [TwoFactorController::class, 'setup'])->name('two-factor.setup');
    Route::post('two-factor',   [TwoFactorController::class, 'confirm'])->name('two-factor.confirm')->middleware('throttle:5,1');
    Route::delete('two-factor', [TwoFactorController::class, 'destroy'])->name('two-factor.destroy');
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
    ->middleware(['auth', 'role:admin', '2fa'])
    ->group(function () {

        Route::get('/', DashboardController::class)->name('dashboard');

        // Profile (redirects to shared profile route)
        Route::get('/profile', fn() => redirect()->route('profile.edit'))->name('profile');

        // Audit log
        Route::get('activity', [ActivityController::class, 'index'])->name('activity.index');

        // Geography
        Route::resource('countries',  CountryController::class);
        Route::resource('regions',    RegionController::class);
        Route::resource('cities',     CityController::class);

        // Catalog
        Route::resource('categories', CategoryController::class);
        Route::post(  'categories/{category}/parameters',                 [CategoryController::class, 'attachParameter'])->name('categories.parameters.attach');
        Route::delete('categories/{category}/parameters/{parameter}',     [CategoryController::class, 'detachParameter'])->name('categories.parameters.detach');
        Route::patch( 'categories/{category}/parameters/{parameter}/variant', [CategoryController::class, 'toggleParameterVariant'])->name('categories.parameters.toggle-variant');
        Route::resource('parameters', ParameterController::class);
        Route::post(  'parameters/{parameter}/values',         [ParameterValueController::class, 'store'])->name('parameters.values.store');
        Route::put(   'parameters/{parameter}/values/{value}', [ParameterValueController::class, 'update'])->name('parameters.values.update');
        Route::delete('parameters/{parameter}/values/{value}', [ParameterValueController::class, 'destroy'])->name('parameters.values.destroy');
        Route::resource('products',   ProductController::class);

        // File library
        Route::resource('files', FileController::class);

        // Link files to products
        Route::post('products/{product}/files',           [ProductFileController::class, 'attach'])->name('products.files.attach');
        Route::delete('products/{product}/files/{file}',  [ProductFileController::class, 'detach'])->name('products.files.detach');

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

        // Banners
        Route::resource('banners', BannerController::class);
        Route::patch('banners/{banner}/toggle', [BannerController::class, 'toggle'])->name('banners.toggle');

        // Help articles
        Route::resource('help', HelpController::class);

        // Homepage content
        Route::get('homepage', [HomeContentController::class, 'edit'])->name('homepage.edit');
        Route::put('homepage', [HomeContentController::class, 'update'])->name('homepage.update');

        // Site settings (contacts, work time, analytics tokens)
        Route::get('settings',  [SettingsController::class, 'edit'])->name('settings.edit');
        Route::put('settings',  [SettingsController::class, 'update'])->name('settings.update');
        Route::post('settings/telegram-webhook', [SettingsController::class, 'setTelegramWebhook'])->name('settings.telegram-webhook');

        // Telegram client messages (support inbox)
        Route::get('messages',           [ClientMessageController::class, 'index'])->name('messages.index');
        Route::get('messages/{user}',    [ClientMessageController::class, 'show'])->name('messages.show');
        Route::post('messages/{user}',   [ClientMessageController::class, 'reply'])->name('messages.reply');

        // Chatbot: unanswered questions + knowledge the admin teaches the bot
        Route::prefix('chatbot')->name('chatbot.')->controller(ChatbotController::class)->group(function () {
            Route::get('/',                              'index')->name('index');
            Route::get('questions/{question}',           'showQuestion')->name('questions.show');
            Route::post('questions/{question}',          'answerQuestion')->name('questions.answer');
            Route::delete('questions/{question}',        'destroyQuestion')->name('questions.destroy');
            Route::get('knowledge/create',               'createKnowledge')->name('knowledge.create');
            Route::post('knowledge',                     'storeKnowledge')->name('knowledge.store');
            Route::get('knowledge/{knowledge}/edit',     'editKnowledge')->name('knowledge.edit');
            Route::put('knowledge/{knowledge}',          'updateKnowledge')->name('knowledge.update');
            Route::delete('knowledge/{knowledge}',       'destroyKnowledge')->name('knowledge.destroy');
        });
    });

// ── Marketplace ───────────────────────────────────────────
// Onboarding (owner without a company yet)
Route::prefix('marketplace')->name('marketplace.')->middleware(['auth', 'role:owner', '2fa'])->group(function () {
    Route::get('onboarding',  [OnboardingController::class, 'show'])->name('onboarding');
    Route::post('onboarding', [OnboardingController::class, 'store'])->name('onboarding.store');
    Route::get('pending',     fn() => view('marketplace.pending'))->name('pending');
});

// Marketplace main (owner + user, company must be approved)
Route::prefix('marketplace')->name('marketplace.')->middleware(['auth', 'role:owner,user', '2fa', 'company.approved'])->group(function () {
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
Route::prefix('producer')->name('producer.')->middleware(['auth', 'role:producer', '2fa'])->group(function () {
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

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\HospitalController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\AIAssistantController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Hospital\HospitalDashboardController;
use App\Http\Controllers\Hospital\HospitalOrderController;
use App\Http\Controllers\Hospital\HospitalStockController;
use App\Http\Controllers\Hospital\HospitalSupplierController;
use App\Http\Controllers\Hospital\HospitalSettingsController;
use App\Http\Controllers\Hospital\CartController;
use App\Http\Controllers\Supplier\SupplierDashboardController;
use App\Http\Controllers\Supplier\SupplierOrderController;
use App\Http\Controllers\Supplier\SupplierProductController;
use App\Http\Controllers\Supplier\SupplierDeliveryController;
use App\Http\Controllers\Supplier\SupplierInvoiceController;
use App\Http\Controllers\Supplier\SupplierSettingsController;
use App\Http\Controllers\TrackingController;

Route::get('/', fn() => redirect('/login'));
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Inscription
Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Mot de passe oublié
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendLink'])->name('password.email');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

// ─── ADMIN ───────────────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/users/export', [UserController::class, 'export'])->name('users.export');
    Route::resource('/users', UserController::class);

    Route::get('/hospitals/export', [HospitalController::class, 'export'])->name('hospitals.export');
    Route::resource('/hospitals', HospitalController::class);

    Route::put('/suppliers/{id}/verify', [SupplierController::class, 'verify'])->name('suppliers.verify');
    Route::resource('/suppliers', SupplierController::class);

    Route::put('/orders/{id}/validate', [OrderController::class, 'validateOrder'])->name('orders.validate');
    Route::get('/orders/{id}/invoice',  [OrderController::class, 'invoice'])->name('orders.invoice');
    Route::resource('/orders', OrderController::class);

    Route::get('/ai-assistant',       [AIAssistantController::class, 'index'])->name('ai-assistant');
    Route::post('/ai-assistant/chat', [AIAssistantController::class, 'chat'])->name('ai.chat');

    Route::get('/analytics',  [AnalyticsController::class, 'index'])->name('analytics');
    Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs');
    Route::get('/settings',   [SettingsController::class, 'index'])->name('settings');
    Route::post('/settings',  [SettingsController::class, 'update'])->name('settings.update');
});

// ─── HOSPITAL ─────────────────────────────────────────────────────────────────
Route::prefix('hospital')->name('hospital.')->middleware('auth')->group(function () {
    Route::get('/dashboard', [HospitalDashboardController::class, 'index'])->name('dashboard');

    Route::get('/ai-assistant',       [AIAssistantController::class, 'hospitalIndex'])->name('ai-assistant');
    Route::post('/ai-assistant/chat', [AIAssistantController::class, 'chat'])->name('ai.chat');

    // Commandes
    Route::resource('/orders', HospitalOrderController::class);
    Route::get('/orders/{id}/tracking',  [TrackingController::class, 'show'])->name('orders.tracking');
    Route::post('/orders/{id}/tracking', [TrackingController::class, 'update'])->name('orders.tracking.update');

    // Panier
    Route::get('/cart',           [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add',      [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/{id}',      [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{id}',   [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart',        [CartController::class, 'clear'])->name('cart.clear');
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');

    // Stock
    Route::get('/stock',         [HospitalStockController::class, 'index'])->name('stock.index');
    Route::post('/stock',        [HospitalStockController::class, 'store'])->name('stock.store');
    Route::put('/stock/{id}',    [HospitalStockController::class, 'update'])->name('stock.update');
    Route::delete('/stock/{id}', [HospitalStockController::class, 'destroy'])->name('stock.destroy');

    // Catalogue & Alertes
    Route::get('/catalog', [HospitalStockController::class, 'catalog'])->name('catalog.index');
    Route::get('/alerts',  [HospitalStockController::class, 'alerts'])->name('alerts.index');

    // Fournisseurs
    Route::get('/suppliers', [HospitalSupplierController::class, 'index'])->name('suppliers.index');

    // Paramètres
    Route::get('/settings',  [HospitalSettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [HospitalSettingsController::class, 'update'])->name('settings.update');
});

// ─── SUPPLIER ─────────────────────────────────────────────────────────────────
Route::prefix('supplier')->name('supplier.')->middleware('auth')->group(function () {
    Route::get('/dashboard', [SupplierDashboardController::class, 'index'])->name('dashboard');

    // Commandes
    Route::get('/orders',               [SupplierOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}',          [SupplierOrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{id}/validate', [SupplierOrderController::class, 'validate'])->name('orders.validate');
    Route::post('/orders/{id}/tracking',[SupplierOrderController::class, 'updateTracking'])->name('orders.tracking.update');

    // Produits — routes statiques AVANT {id} pour éviter les conflits
    Route::get('/products/import',  [SupplierProductController::class, 'importForm'])->name('products.import.form');
    Route::post('/products/import', [SupplierProductController::class, 'import'])->name('products.import');
    Route::get('/products/create',  [SupplierProductController::class, 'create'])->name('products.create');
    Route::get('/products',         [SupplierProductController::class, 'index'])->name('products.index');
    Route::post('/products',        [SupplierProductController::class, 'store'])->name('products.store');
    Route::delete('/products/{id}', [SupplierProductController::class, 'destroy'])->name('products.destroy');

    Route::get('/catalog',    [SupplierProductController::class, 'catalog'])->name('catalog.index');
    Route::get('/deliveries', [SupplierDeliveryController::class, 'index'])->name('deliveries.index');
    Route::get('/invoices',   [SupplierInvoiceController::class, 'index'])->name('invoices.index');

    // Paramètres
    Route::get('/settings',  [SupplierSettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SupplierSettingsController::class, 'update'])->name('settings.update');
    Route::get('/profile',   [SupplierSettingsController::class, 'profile'])->name('profile.index');
});
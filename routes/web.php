<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MotorController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProspectController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

// ---------- Frontend ----------
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/pricelist', [PageController::class, 'pricelist'])->name('pricelist');
Route::get('/faq', [PageController::class, 'faq'])->name('faq');
Route::get('/promo', [PageController::class, 'promos'])->name('promos.index');
Route::get('/promo/{promo}', [PageController::class, 'promo'])->name('promos.show');
Route::get('/motor/{motor}', [MotorController::class, 'show'])->name('motor.show');
Route::post('/prospek', [ProspectController::class, 'store'])->middleware('throttle:10,1')->name('prospect.store');
Route::post('/prospek/wa', [ProspectController::class, 'wa'])->middleware('throttle:20,1')->name('prospect.wa');
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

// ---------- Backend ----------
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [Admin\AuthController::class, 'showLogin'])->middleware('guest')->name('login');
    Route::post('login', [Admin\AuthController::class, 'login'])->middleware('throttle:10,1')->name('login.post');

    Route::middleware('auth')->group(function () {
        Route::post('logout', [Admin\AuthController::class, 'logout'])->name('logout');
        Route::get('/', Admin\DashboardController::class)->name('dashboard');

        Route::resource('motors', Admin\MotorController::class)->except('show');
        Route::resource('categories', Admin\CategoryController::class)->except('show');
        Route::resource('banners', Admin\BannerController::class)->except('show');
        Route::resource('promos', Admin\PromoController::class)->except('show');
        Route::resource('faqs', Admin\FaqController::class)->except('show');

        Route::get('prospects', [Admin\ProspectController::class, 'index'])->name('prospects.index');
        Route::get('prospects/export', [Admin\ProspectController::class, 'export'])->name('prospects.export');
        Route::get('prospects/export', [Admin\ProspectController::class, 'export'])->name('prospects.export');
        Route::get('prospects/{prospect}', [Admin\ProspectController::class, 'show'])->name('prospects.show');
        Route::put('prospects/{prospect}', [Admin\ProspectController::class, 'update'])->name('prospects.update');
        Route::delete('prospects/{prospect}', [Admin\ProspectController::class, 'destroy'])->name('prospects.destroy');

        Route::get('settings', [Admin\SettingController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [Admin\SettingController::class, 'update'])->name('settings.update');
    });
});

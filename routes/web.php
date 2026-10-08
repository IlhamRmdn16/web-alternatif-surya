<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MotorController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProspectController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\StaticPageController;
use App\Support\PageDefaults;
use Illuminate\Support\Facades\Route;

// ---------- Frontend ----------
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/daftar-harga', [PageController::class, 'pricelist'])->name('pricelist');
Route::redirect('/pricelist', '/daftar-harga', 301);
// Halaman FAQ & Syarat Kredit sudah dihapus (FAQ hanya di beranda): alamat lama dialihkan ke beranda.
Route::redirect('/cara-beli-dan-syarat-kredit', '/', 301);
Route::redirect('/syarat-kredit', '/', 301);
Route::redirect('/faq', '/', 301)->name('faq');
Route::get('/promo', [PageController::class, 'promos'])->name('promos.index');
Route::get('/promo/{promo}', [PageController::class, 'promo'])->name('promos.show');
Route::get('/berita', [NewsController::class, 'index'])->name('news.index');
Route::get('/berita/{post}', [NewsController::class, 'show'])->name('news.show');
Route::get('/motor/{motor}', [MotorController::class, 'show'])->name('motor.show');
Route::post('/prospek', [ProspectController::class, 'store'])->middleware('throttle:10,1')->name('prospect.store');
Route::post('/prospek/wa', [ProspectController::class, 'wa'])->middleware('throttle:20,1')->name('prospect.wa');
Route::get('/kontak', ContactController::class)->name('contact');
Route::get('/tentang-kami', AboutController::class)->name('about');
// Halaman statis: /kebijakan-privasi (isi diatur di Admin > Halaman)
Route::get('/{page}', [StaticPageController::class, 'show'])->whereIn('page', array_keys(PageDefaults::all()))->name('page.show');
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
        Route::resource('sales', Admin\SalesContactController::class)->except('show');
        Route::resource('pages', Admin\PageController::class)->only(['index', 'edit', 'update']);
        Route::resource('posts', Admin\PostController::class)->except('show');
        Route::post('uploads/image', [Admin\UploadController::class, 'image'])->name('uploads.image');

        Route::get('prospects', [Admin\ProspectController::class, 'index'])->name('prospects.index');
        Route::get('prospects/export', [Admin\ProspectController::class, 'export'])->name('prospects.export');
        Route::get('prospects/export', [Admin\ProspectController::class, 'export'])->name('prospects.export');
        Route::get('prospects/{prospect}', [Admin\ProspectController::class, 'show'])->name('prospects.show');
        Route::put('prospects/{prospect}', [Admin\ProspectController::class, 'update'])->name('prospects.update');
        Route::delete('prospects/{prospect}', [Admin\ProspectController::class, 'destroy'])->name('prospects.destroy');

        Route::get('about', [Admin\AboutController::class, 'edit'])->name('about.edit');
        Route::put('about', [Admin\AboutController::class, 'update'])->name('about.update');

        Route::get('settings', [Admin\SettingController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [Admin\SettingController::class, 'update'])->name('settings.update');
    });
});

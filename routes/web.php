<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Shop;
use App\Http\Middleware\TrackProductView;

// ─── Frontend (Shop) Routes ───────────────────────────────────
Route::get('/', [Shop\HomeController::class, 'index'])->name('home');

// Kategoriler
Route::get('/kategori/{category}', [Shop\CategoryController::class, 'show'])->name('category.show');
Route::get('/kategori/{parent}/{category}', [Shop\CategoryController::class, 'showSub'])->name('category.sub');

// Ürünler
Route::get('/urun/{product}', [Shop\ProductController::class, 'show'])
    ->middleware(TrackProductView::class)
    ->name('product.show');
Route::get('/urun-karsilastir', [Shop\ProductController::class, 'compare'])->name('product.compare');
Route::post('/urun-karsilastir/ekle', [Shop\ProductController::class, 'addToCompare'])->name('product.compare.add');
Route::post('/urun-karsilastir/kaldir', [Shop\ProductController::class, 'removeFromCompare'])->name('product.compare.remove');
Route::post('/stok-uyari', [Shop\ProductController::class, 'stockAlert'])->name('product.stock.alert');
Route::post('/whatsapp-tiklama', [Shop\ProductController::class, 'whatsappClick'])->name('product.whatsapp.click');

// Arama
Route::get('/arama', [Shop\SearchController::class, 'index'])->name('search');

// Sepet
Route::get('/sepet', [Shop\CartController::class, 'index'])->name('cart.index');
Route::post('/sepet/ekle', [Shop\CartController::class, 'add'])->name('cart.add');
Route::post('/sepet/guncelle', [Shop\CartController::class, 'update'])->name('cart.update');
Route::post('/sepet/kaldir', [Shop\CartController::class, 'remove'])->name('cart.remove');
Route::post('/sepet/kupon', [Shop\CartController::class, 'applyCoupon'])->name('cart.coupon');
Route::post('/sepet/kupon-kaldir', [Shop\CartController::class, 'removeCoupon'])->name('cart.coupon.remove');

// Favoriler
Route::post('/favori/ekle', [Shop\WishlistController::class, 'add'])->name('wishlist.add');
Route::post('/favori/kaldir', [Shop\WishlistController::class, 'remove'])->name('wishlist.remove');
Route::get('/favorilerim', [Shop\WishlistController::class, 'index'])->name('wishlist.index');

// Ödeme
Route::get('/odeme', [Shop\CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/odeme', [Shop\CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/siparis/tamamlandi/{orderNo}', [Shop\CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/siparis-takip', [Shop\OrderController::class, 'track'])->name('order.track');
Route::post('/siparis-takip', [Shop\OrderController::class, 'trackPost'])->name('order.track.post');

// Ödeme callback'leri (Iyzico)
Route::post('/odeme/callback', [Shop\CheckoutController::class, 'callback'])->name('checkout.callback');
Route::get('/odeme/basarisiz', [Shop\CheckoutController::class, 'failed'])->name('checkout.failed');

// PayTR Ödeme
Route::post('/paytr/create', [Shop\PaytrController::class, 'create'])->name('paytr.create');
Route::post('/paytr/callback', [Shop\PaytrController::class, 'callback'])->name('paytr.callback');
Route::post('/paytr/installments', [Shop\PaytrController::class, 'getInstallments'])->name('paytr.installments');
Route::get('/paytr/iframe/{token}', [Shop\PaytrController::class, 'iframe'])->name('paytr.iframe');

// Hesabım (Auth gerekli)
Route::middleware('auth')->prefix('hesabim')->name('account.')->group(function () {
    Route::get('/', [Shop\AccountController::class, 'index'])->name('index');
    Route::get('/siparislerim', [Shop\AccountController::class, 'orders'])->name('orders');
    Route::get('/siparislerim/{order}', [Shop\AccountController::class, 'orderDetail'])->name('order.detail');
    Route::get('/adreslerim', [Shop\AccountController::class, 'addresses'])->name('addresses');
    Route::post('/adreslerim', [Shop\AccountController::class, 'storeAddress'])->name('addresses.store');
    Route::put('/adreslerim/{address}', [Shop\AccountController::class, 'updateAddress'])->name('addresses.update');
    Route::delete('/adreslerim/{address}', [Shop\AccountController::class, 'deleteAddress'])->name('addresses.delete');
    Route::get('/profil', [Shop\AccountController::class, 'profile'])->name('profile');
    Route::post('/profil', [Shop\AccountController::class, 'updateProfile'])->name('profile.update');
    Route::post('/yorum', [Shop\AccountController::class, 'submitReview'])->name('review.store');
});

// Blog
Route::get('/blog', [Shop\BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{post}', [Shop\BlogController::class, 'show'])->name('blog.show');

// Statik Sayfalar
Route::get('/hakkimizda', [Shop\PageController::class, 'about'])->name('page.about');
Route::get('/iletisim', [Shop\PageController::class, 'contact'])->name('page.contact');
Route::post('/iletisim', [Shop\PageController::class, 'contactPost'])->name('page.contact.post');
Route::get('/kargo-ve-iade', [Shop\PageController::class, 'shipping'])->name('page.shipping');
Route::get('/gizlilik-politikasi', [Shop\PageController::class, 'privacy'])->name('page.privacy');
Route::get('/kullanim-kosullari', [Shop\PageController::class, 'terms'])->name('page.terms');

// Sitemap
Route::get('/sitemap.xml', [Shop\SitemapController::class, 'index'])->name('sitemap');

// Auth (Laravel Breeze tarafından üretilecek)
require __DIR__.'/auth.php';

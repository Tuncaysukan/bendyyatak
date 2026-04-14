<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin;
use App\Http\Middleware\AdminAuth;

// ─── Admin Auth ───────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/giris', [Admin\AuthController::class, 'showLogin'])->name('login');
    Route::post('/giris', [Admin\AuthController::class, 'login'])->name('login.post');
    Route::post('/cikis', [Admin\AuthController::class, 'logout'])->name('logout');

    // ─── Protected Admin Routes ───────────────────────────────
    Route::middleware(AdminAuth::class)->group(function () {

        // Dashboard
        Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

        // Kategoriler
        Route::resource('kategoriler', Admin\CategoryController::class)
            ->parameters(['kategoriler' => 'category'])
            ->names([
                'index'   => 'categories.index',
                'create'  => 'categories.create',
                'store'   => 'categories.store',
                'edit'    => 'categories.edit',
                'update'  => 'categories.update',
                'destroy' => 'categories.destroy',
            ]);
        Route::post('kategoriler/siralama', [Admin\CategoryController::class, 'updateOrder'])->name('categories.order');

        // Ürünler
        Route::resource('urunler', Admin\ProductController::class)
            ->parameters(['urunler' => 'product'])
            ->names([
                'index'   => 'products.index',
                'create'  => 'products.create',
                'store'   => 'products.store',
                'edit'    => 'products.edit',
                'update'  => 'products.update',
                'destroy' => 'products.destroy',
            ]);
        Route::post('urunler/{product}/gorsel-sil', [Admin\ProductController::class, 'deleteImage'])->name('products.image.delete');
        Route::post('urunler/{product}/gorsel-sirala', [Admin\ProductController::class, 'reorderImages'])->name('products.image.reorder');

        // Siparişler
        Route::get('siparisler', [Admin\OrderController::class, 'index'])->name('orders.index');
        Route::get('siparisler/{order}', [Admin\OrderController::class, 'show'])->name('orders.show');
        Route::post('siparisler/{order}/durum', [Admin\OrderController::class, 'updateStatus'])->name('orders.status');
        Route::post('siparisler/{order}/kargo', [Admin\OrderController::class, 'updateCargo'])->name('orders.cargo');
        Route::post('siparisler/{order}/not', [Admin\OrderController::class, 'addNote'])->name('orders.note');

        // Müşteriler
        Route::get('musteriler', [Admin\CustomerController::class, 'index'])->name('customers.index');
        Route::get('musteriler/{customer}', [Admin\CustomerController::class, 'show'])->name('customers.show');

        // Raporlar
        Route::get('raporlar/satis', [Admin\ReportController::class, 'sales'])->name('reports.sales');
        Route::get('raporlar/goruntulenmeler', [Admin\ReportController::class, 'views'])->name('reports.views');
        Route::get('raporlar/satis/export', [Admin\ReportController::class, 'exportSales'])->name('reports.sales.export');

        // Kuponlar
        Route::resource('kuponlar', Admin\CouponController::class)
            ->parameters(['kuponlar' => 'coupon'])
            ->names([
                'index'   => 'coupons.index',
                'create'  => 'coupons.create',
                'store'   => 'coupons.store',
                'edit'    => 'coupons.edit',
                'update'  => 'coupons.update',
                'destroy' => 'coupons.destroy',
            ]);

        // Blog
        Route::resource('blog', Admin\BlogController::class)
            ->parameters(['blog' => 'post'])
            ->names([
                'index'   => 'blog.index',
                'create'  => 'blog.create',
                'store'   => 'blog.store',
                'edit'    => 'blog.edit',
                'update'  => 'blog.update',
                'destroy' => 'blog.destroy',
            ]);

        // Yorumlar
        Route::get('yorumlar', [Admin\ReviewController::class, 'index'])->name('reviews.index');
        Route::post('yorumlar/{review}/onayla', [Admin\ReviewController::class, 'approve'])->name('reviews.approve');
        Route::delete('yorumlar/{review}', [Admin\ReviewController::class, 'destroy'])->name('reviews.destroy');

        // Ödeme Kanalları
        Route::get('odeme-kanallari', [Admin\PaymentChannelController::class, 'index'])->name('payment.index');
        Route::post('odeme-kanallari', [Admin\PaymentChannelController::class, 'update'])->name('payment.update');
        Route::resource('taksit-planlari', Admin\InstallmentPlanController::class)
            ->parameters(['taksit-planlari' => 'plan'])
            ->names([
                'index'   => 'installment.index',
                'create'  => 'installment.create',
                'store'   => 'installment.store',
                'edit'    => 'installment.edit',
                'update'  => 'installment.update',
                'destroy' => 'installment.destroy',
            ]);
        Route::patch('taksit-planlari/{plan}/toggle', [Admin\InstallmentPlanController::class, 'toggle'])->name('installment.toggle');

        // Kargo Ayarları
        Route::get('kargo', [Admin\ShippingController::class, 'index'])->name('shipping.index');
        Route::post('kargo', [Admin\ShippingController::class, 'update'])->name('shipping.update');

        // Ayarlar
        Route::get('ayarlar', [Admin\SettingController::class, 'index'])->name('settings.index');
        Route::post('ayarlar', [Admin\SettingController::class, 'update'])->name('settings.update');
        Route::get('ayarlar/seo', [Admin\SettingController::class, 'seo'])->name('settings.seo');
        Route::post('ayarlar/seo', [Admin\SettingController::class, 'updateSeo'])->name('settings.seo.update');
        Route::get('ayarlar/mailler', [Admin\SettingController::class, 'mails'])->name('settings.mails');
        Route::post('ayarlar/mailler', [Admin\SettingController::class, 'updateMails'])->name('settings.mails.update');
        Route::resource('mesajlar', Admin\ContactMessageController::class)
            ->only(['index', 'show', 'destroy'])
            ->parameters(['mesajlar' => 'message'])
            ->names([
                'index'   => 'messages.index',
                'show'    => 'messages.show',
                'destroy' => 'messages.destroy',
            ]);
            
        Route::resource('anasayfa-vitrini', Admin\SliderController::class)
            ->parameters(['anasayfa-vitrini' => 'slider'])
            ->names([
                'index'   => 'sliders.index',
                'create'  => 'sliders.create',
                'store'   => 'sliders.store',
                'edit'    => 'sliders.edit',
                'update'  => 'sliders.update',
                'destroy' => 'sliders.destroy',
            ]);
        Route::post('anasayfa-vitrini/siralama', [Admin\SliderController::class, 'updateOrder'])->name('sliders.order');

        // PayTR Ödemeleri
        Route::get('paytr', [Admin\PaytrController::class, 'index'])->name('paytr.index');
        Route::get('paytr/ayarlar', [Admin\PaytrController::class, 'settings'])->name('paytr.settings');
        Route::post('paytr/ayarlar', [Admin\PaytrController::class, 'saveSettings'])->name('paytr.settings.save');
        Route::get('paytr/rapor', [Admin\PaytrController::class, 'report'])->name('paytr.report');
        Route::get('paytr/export', [Admin\PaytrController::class, 'export'])->name('paytr.export');
        Route::get('paytr/{transaction}', [Admin\PaytrController::class, 'show'])->name('paytr.show');
    });
});

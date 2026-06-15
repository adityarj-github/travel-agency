<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public / Frontend Routes
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Account\AccountController;
use App\Http\Controllers\Account\WishlistController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');

Route::get('/packages', [PackageController::class, 'index'])->name('packages.index');
Route::get('/packages/{package:slug}', [PackageController::class, 'show'])->name('packages.show');

Route::get('/destinations', [DestinationController::class, 'index'])->name('destinations.index');
Route::get('/destinations/{destination:slug}', [DestinationController::class, 'show'])->name('destinations.show');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{blog:slug}', [BlogController::class, 'show'])->name('blog.show');

Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');

Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:8,1')->name('contact.store');

Route::get('/booking', [BookingController::class, 'create'])->name('booking.create');
Route::post('/booking', [BookingController::class, 'store'])->middleware('throttle:8,1')->name('booking.store');
Route::post('/booking/apply-coupon', [BookingController::class, 'applyCoupon'])->middleware('throttle:15,1')->name('booking.apply-coupon');

// Razorpay payment (token-guarded so guests can pay for their own booking)
Route::get('/booking/{booking}/pay', [PaymentController::class, 'show'])->name('booking.pay');
Route::post('/booking/{booking}/payment', [PaymentController::class, 'callback'])->middleware('throttle:10,1')->name('booking.payment.callback');
Route::get('/booking/{booking}/thank-you', [PaymentController::class, 'success'])->name('booking.payment.success');
Route::post('/razorpay/webhook', [PaymentController::class, 'webhook'])->name('razorpay.webhook');

// SEO
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');

/*
|--------------------------------------------------------------------------
| Customer Authentication & Account
|--------------------------------------------------------------------------
*/

// Guest-only pages guard themselves in the controller (redirectIfAuthenticated).
Route::get('register', [AuthController::class, 'showRegister'])->name('register');
Route::post('register', [AuthController::class, 'register'])->middleware('throttle:6,1')->name('register.attempt');
Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login'])->middleware('throttle:6,1')->name('login.attempt');

Route::post('logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('customer')->prefix('account')->name('account.')->group(function () {
    Route::get('/', [AccountController::class, 'dashboard'])->name('dashboard');
    Route::get('bookings', [AccountController::class, 'bookings'])->name('bookings');
    Route::get('bookings/{booking}/voucher', [AccountController::class, 'voucher'])->name('bookings.voucher');
    Route::get('profile', [AccountController::class, 'profile'])->name('profile');
    Route::put('profile', [AccountController::class, 'updateProfile'])->name('profile.update');
    Route::put('password', [AccountController::class, 'updatePassword'])->name('password.update');

    Route::get('wishlist', [WishlistController::class, 'index'])->name('wishlist');
    Route::delete('wishlist/{package:id}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');
});

// Wishlist toggle — available to any authenticated customer from anywhere on the site.
Route::post('wishlist/{package:id}', [WishlistController::class, 'toggle'])
    ->middleware('customer')
    ->name('wishlist.toggle');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Admin\AuthController as AdminAuth;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PackageController as AdminPackage;
use App\Http\Controllers\Admin\BookingController as AdminBooking;
use App\Http\Controllers\Admin\PaymentLinkController as AdminPaymentLink;
use App\Http\Controllers\Admin\BlogController as AdminBlog;
use App\Http\Controllers\Admin\BlogCategoryController as AdminBlogCategory;
use App\Http\Controllers\Admin\DestinationController as AdminDestination;
use App\Http\Controllers\Admin\SliderController as AdminSlider;
use App\Http\Controllers\Admin\GalleryController as AdminGallery;
use App\Http\Controllers\Admin\TestimonialController as AdminTestimonial;
use App\Http\Controllers\Admin\InquiryController as AdminInquiry;
use App\Http\Controllers\Admin\SettingController as AdminSetting;
use App\Http\Controllers\Admin\CouponController as AdminCoupon;
use App\Http\Controllers\Admin\UserController as AdminUser;

Route::prefix('admin')->name('admin.')->group(function () {

    // Guest (login) routes
    Route::get('login', [AdminAuth::class, 'showLogin'])->name('login');
    Route::post('login', [AdminAuth::class, 'login'])->middleware('throttle:6,1')->name('login.attempt');

    // Authenticated admin routes
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::post('logout', [AdminAuth::class, 'logout'])->name('logout');

        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Content management (editor / manager / admin)
        Route::middleware('can:manage_content')->group(function () {
            Route::delete('packages/images/{image}', [AdminPackage::class, 'deleteImage'])->name('packages.images.destroy');
            Route::patch('packages/{package}/toggle', [AdminPackage::class, 'toggle'])->name('packages.toggle');
            Route::resource('packages', AdminPackage::class);

            Route::patch('destinations/{destination}/toggle', [AdminDestination::class, 'toggle'])->name('destinations.toggle');
            Route::resource('destinations', AdminDestination::class);

            Route::patch('blogs/{blog}/toggle', [AdminBlog::class, 'toggle'])->name('blogs.toggle');
            Route::resource('blogs', AdminBlog::class);
            Route::resource('blog-categories', AdminBlogCategory::class)
                ->except('show')
                ->parameters(['blog-categories' => 'blogCategory']);

            Route::patch('sliders/{slider}/toggle', [AdminSlider::class, 'toggle'])->name('sliders.toggle');
            Route::resource('sliders', AdminSlider::class)->except('show');

            Route::patch('galleries/{gallery}/toggle', [AdminGallery::class, 'toggle'])->name('galleries.toggle');
            Route::resource('galleries', AdminGallery::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

            Route::patch('testimonials/{testimonial}/toggle', [AdminTestimonial::class, 'toggle'])->name('testimonials.toggle');
            Route::resource('testimonials', AdminTestimonial::class)->except('show');
        });

        // Bookings (manager / admin)
        Route::middleware('can:manage_bookings')->group(function () {
            Route::patch('bookings/{booking}/status', [AdminBooking::class, 'updateStatus'])->name('bookings.status');
            Route::get('bookings/{booking}/voucher', [AdminBooking::class, 'voucher'])->name('bookings.voucher');
            Route::resource('bookings', AdminBooking::class)->only(['index', 'show', 'update', 'destroy']);

            // Custom payment-link generator — create a token-guarded pay link to share.
            Route::get('payment-links', [AdminPaymentLink::class, 'index'])->name('payment-links.index');
            Route::post('payment-links', [AdminPaymentLink::class, 'store'])->name('payment-links.store');
            Route::delete('payment-links/{paymentLink}', [AdminPaymentLink::class, 'destroy'])->name('payment-links.destroy');
        });

        // Coupons (manager / admin)
        Route::middleware('can:manage_coupons')->group(function () {
            Route::patch('coupons/{coupon}/toggle', [AdminCoupon::class, 'toggle'])->name('coupons.toggle');
            Route::resource('coupons', AdminCoupon::class)->except('show');
        });

        // Contact inquiries (manager / admin)
        Route::middleware('can:manage_inquiries')->group(function () {
            Route::patch('inquiries/{inquiry}/read', [AdminInquiry::class, 'toggleRead'])->name('inquiries.read');
            Route::get('inquiries', [AdminInquiry::class, 'index'])->name('inquiries.index');
            Route::get('inquiries/{inquiry}', [AdminInquiry::class, 'show'])->name('inquiries.show');
            Route::delete('inquiries/{inquiry}', [AdminInquiry::class, 'destroy'])->name('inquiries.destroy');
        });

        // Staff / user management (admin only)
        Route::middleware('can:manage_users')->group(function () {
            Route::resource('users', AdminUser::class)->except('show');
        });

        // Website settings (admin only)
        Route::middleware('can:manage_settings')->group(function () {
            Route::get('settings', [AdminSetting::class, 'edit'])->name('settings.edit');
            Route::put('settings', [AdminSetting::class, 'update'])->name('settings.update');
        });
    });
});

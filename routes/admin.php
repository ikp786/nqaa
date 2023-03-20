<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application admin panel.
| These routes are loaded by the RouteServiceProvider within a group which
| contains the "admin" middleware group. Now create something great!
|
*/
// Route::get('/login', [AdminAuthController::class, 'index'])->name('login');


// Route::get('login',[DashboardController::class,'index'])->name('login');

Route::name('admin.')->group(function () {
    Route::middleware('guest')->group(
        function () {
            Route::get('/', [AdminAuthController::class, 'index']);
            Route::get('/login', [AdminAuthController::class, 'index'])->name('login');
            Route::post('/login', [AdminAuthController::class, 'login']);
        }
    );
    
    Route::get('get_order_whatsapp/{id}', [OrderController::class, 'getOrderWhatsapp'])->name('orders.get_order_whatsapp');
    Route::post('upload_order_image/{id}', [OrderController::class, 'uploadOrderImage'])->name('orders.upload_order_image');
    Route::get('order_delivered', [OrderController::class, 'orderDelivered'])->name('orders.order_delivered');
    /*
    |--------------------------------------------------------------------------
    |AUTHENTIC ROUTE
    |--------------------------------------------------------------------------
    */
    Route::middleware('admin')->group(
        function () {
            // Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');
            Route::resources([
                'dashboard' => DashboardController::class,
                'topics' => TopicController::class,
                'languages' => LanguageController::class,
                'users' => UserController::class,
                'merchants' => MerchantController::class,
                'products' => ProductController::class,
                'orders' => OrderController::class,
                'pages' => PageController::class
            ]);
            Route::controller(AdminAuthController::class)->group(function () {
                Route::post('logout', 'logout')->name('logout');
                Route::get('password-change', 'changePasswordGet')->name('password-change');
                Route::post('change-password',  'changePassword')->name('change-password');
            });
            Route::get('topics-status', [TopicController::class, 'changeStatus'])->name('topics.status');
            Route::get('languages-status', [LanguageController::class, 'changeStatus'])->name('languages.status');
            Route::get('users-status', [UserController::class, 'changeStatus'])->name('users.status');
            Route::get('merchants-status', [UserController::class, 'changeStatus'])->name('merchants.status');
            Route::get('products-status', [ProductController::class, 'changeStatus'])->name('products.status');
            Route::get('orders-status', [OrderController::class, 'changeStatus'])->name('orders.status');
            Route::post('sent_whatsapp_message', [OrderController::class, 'sentWhatsappMessage'])->name('orders.sent_whatsapp_message');
            Route::post('get_order_product', [OrderController::class, 'getOrderProduct'])->name('orders.get_order_product');
            Route::get('transactions', [OrderController::class, 'transaction'])->name('transactions.index');
        }
    );
});
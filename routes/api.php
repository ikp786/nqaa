<?php
namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::fallback(function () {
    return response()->json([
        'ResponseCode'  => 404,
        'status'        => False,
        'message'       => 'URL not found as you lookings'
    ]);
});//->name('api.unauthorized');

Route::get('unauthorized', function () {
    return response()->json(['statusCode' => 401, 'status' => 'unauthorized', 'message' => 'Unauthorized user.']);
})->name('api.unauthorized');

/*
        |--------------------------------------------------------------------------
        | AUTH REGISTER LOGIN SENT LOGIN OTP ROUTE
        |--------------------------------------------------------------------------
        */
        Route::controller(AuthController::class)->group(function () {
            Route::post('login', 'login');
            Route::post('login_otp_verify', 'loginOtpVerify');
            Route::get('test','Test');
            Route::get('sendOtpViaSMS','sendOtpViaSMS');
            Route::get('sendOtpViaEmail','sendOtpViaEmail');
            // Route::post('forgot_password', 'forgotPassword');
            // Route::post('update_forget_password', 'updateForgetPassword');
            Route::get('version', 'version');
        });

        /*
        |--------------------------------------------------------------------------
        | ADD TO CART GET CART DATA REMOVE CART DATA ROUTE
        |--------------------------------------------------------------------------
        */
        Route::controller(CartController::class)->group(function(){
            Route::post('add_to_cart','addToCart');
            Route::post('get_cart_data','getCartData');
            Route::post('delete_prodcut_in_cart','deleteProdcutInCart');
        });

        /*
        |--------------------------------------------------------------------------
        | GET ALL PRODUCT SEARCH PRODUCT PRODUCT DETAIL
        |--------------------------------------------------------------------------
        */
        Route::controller(ProductController::class)->group(function(){
            Route::get('get_product','getProduct');
            Route::any('get_search_product','getSearchProduct');
            Route::get('get_product_details/{id}','getProductDetails');
        });

    Route::middleware('auth:sanctum')->group(function () {   
    Route::controller(AuthController::class)->group(function(){
        Route::get('profile','getUserProfile');
        Route::post('update_profile','updateUserProfile');    
        Route::get('delete_my_account', 'deleteMyAccount');
        Route::get('get_notifications', 'getNotification');
    });
        Route::controller(OrderController::class)->group(function(){
        Route::post('create_order','createOrder');
        Route::get('get_order_list','getOrderList');
        Route::post('get_order_detail/{id}','getOrderDetail');
        });
    });

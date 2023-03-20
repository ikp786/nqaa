<?php
namespace App\Http\Controllers\Front;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::controller(PageController::class)->group(function(){
Route::get('privacy-policy', 'privacyPolicy')->name('front.privacy-policy');
    Route::get('terms-and-conditions', 'termsAndConditions')->name('front.terms-and-conditions');
});
Route::get('/', function () {
    return view('welcome');
});

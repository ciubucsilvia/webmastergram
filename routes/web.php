<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TimelineController;
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



Auth::routes(['verify'=>true]);


Route::middleware(['auth'])->group(function () {
    Route::get('/verify/email', [VerifyEmailController::class, 'create'])->name('verify/email');
    Route::post('/verify/email', [VerifyEmailController::class, 'store'])->name('verify/email');

    Route::get('/', [TimelineController::class, 'index'])->name('home');
    
    Route::get('/auth/{provider}/redirect', [LoginController::class, 'redirectToProvider']);
    Route::get('/auth/{provider}/callback', [LoginController::class, 'handleProviderCallback']);
    
    Route::post('search', [ProfileController::class, 'search'])->name('search');
    Route::get('follow/{user}', [ProfileController::class, 'follow'])->name('follow');
    Route::get('unfollow/{user}', [ProfileController::class, 'unfollow'])->name('unfollow');
    Route::resource('profile', ProfileController::class);
    
    Route::get('posts/like/{post}/{user}', [PostController::class, 'like'])->name('like');
    Route::resource('posts', PostController::class)->only(['create', 'store']);        

});

Route::get('secret', function() {
    return 'secure';
})->middleware('verified.email');


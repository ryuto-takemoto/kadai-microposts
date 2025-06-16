<?php

// use App\Http\Controllers\ProfileController;    // コメントアウトにする
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UsersController; // 追記
use App\Http\Controllers\MicropostsController; //追記
use App\Http\Controllers\UserFollowController;  // 追記
use App\Http\Controllers\FavoritesController; // 追記

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

Route::get('/', [MicropostsController::class, 'index']);

Route::get('/dashboard', [MicropostsController::class, 'index'])->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::resource('users', UsersController::class, ['only' => ['index', 'show']]);

    // フォロー/アンフォローのルートを追加
    Route::post('/users/{id}/follow', [UsersController::class, 'follow'])->name('users.follow');
    Route::delete('/users/{id}/unfollow', [UsersController::class, 'unfollow'])->name('users.unfollow');

    Route::get('/users/{id}/followings', [UsersController::class, 'followings'])->name('users.followings');
    Route::get('/users/{user}/followers', [UsersController::class, 'followers'])->name('users.followers');
    Route::get('/users/{user}/favorites', [UsersController::class, 'favorites'])->name('users.favorites');

    Route::resource('microposts', MicropostsController::class, ['only' => ['store', 'destroy']]);

    Route::post('/microposts/{micropost}/favorite', [MicropostsController::class, 'favorite'])->name('microposts.favorite');
    Route::delete('/microposts/{micropost}/unfavorite', [MicropostsController::class, 'unfavorite'])->name('microposts.unfavorite');
    //Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    //Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    //Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
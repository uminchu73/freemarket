<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\AddressController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**
 * 未認証でも表示
 */
Route::get('/', [ItemController::class,'index'])->name('home');
Route::get('/search', [ItemController::class, 'search'])->name('items.search');
Route::get('/item/{item}', [ItemController::class, 'show'])->name('items.show');

/**
 * ログイン
 */
Route::get('/login', function() {
    return view('auth.login');
})->name('login');

Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);

/**
 * 会員登録
 */
Route::post('/register', [RegisteredUserController::class, 'store']);

/**
 * 認証が必要なルート
 */
Route::middleware('auth')->group(function () {

    /**
     * プロフィール設定
     */
    Route::get('/mypage', [UserController::class, 'show'])->name('profile.show');
    Route::patch('/mypage', [UserController::class, 'update'])->name('profile.update');
    Route::get('/mypage/profile', [UserController::class, 'edit'])->name('profile.edit');


    /**
     * 出品
     */
    Route::get('/sell', [ItemController::class, 'create'])->name('items.create');
    Route::post('/sell', [ItemController::class, 'store'])->name('items.store');

    /**
     * お気に入り
     */

    Route::post('/item/{item}/favorite', [FavoriteController::class, 'toggle'])->name('item.favorite');

    /**
     * コメント
     */
    Route::post('/item/{item}/comment', [ItemController::class, 'addComment'])->name('item.comment');

    /**
     * 購入
     */
    Route::get('/purchase/{item}', [PurchaseController::class, 'show'])->name('purchase.show');
    Route::post('/purchase/{item}', [PurchaseController::class, 'store'])->name('purchase.store');
    Route::get('/purchase/{item}/success', [PurchaseController::class, 'success'])->name('purchase.success');
    Route::get('/purchase/{item}/cancel', [PurchaseController::class, 'cancel'])->name('purchase.cancel');


    /**
     * 住所変更
     */
    Route::get('/purchase/address/{item}', [AddressController::class, 'edit'])->name('purchase.address.edit');
    Route::put('/purchase/address/{item}', [AddressController::class, 'update'])->name('purchase.address.update');

});
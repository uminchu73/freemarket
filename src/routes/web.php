<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\PurchaseController;

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

Route::middleware('auth')->group(function () {
    Route::get('/', [ItemController::class,'index'])->name('home');
});
Route::get('/my-favorites', [FavoriteController::class, 'index'])->name('mylist');

Route::get('/search', [ItemController::class, 'search']);
Route::get('/sell', [ItemController::class, 'create']);
Route::post('/sell', [ItemController::class, 'store']);

Route::get('/item/{item}', [ItemController::class, 'show'])->name('items.show');
Route::middleware('auth')->group(function () {
    Route::post('/item/{item}/favorite', [FavoriteController::class, 'toggle'])->name('item.favorite');
});
Route::post('/item/{item}/comment', [ItemController::class, 'addComment'])->name('item.comment')->middleware('auth');



Route::middleware('auth')->get('/purchase/{item}', [PurchaseController::class, 'show'])->name('purchase.show');

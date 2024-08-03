<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductMediaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubcategoryController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('subcategories/{category_code}', [SubcategoryController::class, 'index']);
Route::post('products/fetch', [ProductController::class, 'fetch']);
Route::post('products/best_seller',[ProductController::class, 'bestSeller']);
Route::get('get_medias/{id}', [ProductMediaController::class, 'fetch']);
Route::get('cart', [ProductController::class, 'cart']);
Route::get('account', [ProfileController::class, 'createAccount']);
Route::match(['post', 'put'], 'customers/submit', [CustomerController::class, 'submit']);
Route::post('create', [OrderController::class, 'create']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
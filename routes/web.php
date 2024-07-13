<?php

use App\Http\Controllers\ProfileController;
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
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::prefix('admins')->group(function(){
        Route::get('/', 'AdminController@index');
        Route::post('load', 'AdminController@load');
        Route::match(['post', 'put'], 'submit', 'AdminController@submit');
        Route::post('edit_status', 'AdminController@editStatus');
    });

    Route::prefix('retailers')->group(function(){
        Route::get('/', 'RetailerController@index');
        Route::post('load', 'RetailerController@load');
        Route::match(['post', 'put'], 'submit', 'RetailerController@submit');
    });

    Route::prefix('brands')->group(function(){
        Route::get('/', 'BrandController@index');
        Route::post('load', 'BrandController@load');
        Route::match(['post', 'put'], 'submit', 'BrandController@submit');
    });

    Route::prefix('categories')->group(function(){
        Route::get('/', 'CategoryController@index');
        Route::post('load', 'CategoryController@load');
        Route::match(['post', 'put'], 'submit', 'CategoryController@submit');
    });

    Route::prefix('subcategories')->group(function(){
        Route::get('/', 'SubcategoryController@index');
        Route::post('load', 'SubcategoryController@load');
        Route::match(['post', 'put'], 'submit', 'SubcategoryController@submit');
        Route::post('get_sizes', 'SubcategoryController@getSizes');
    });

    Route::prefix('sizes')->group(function(){
        Route::get('/', 'SizeController@index');
        Route::post('load', 'SizeController@load');
        Route::match(['post', 'put'], 'submit', 'SizeController@submit');
    });

    Route::prefix('customers')->group(function(){
        Route::get('/', 'CustomerController@index');
        Route::post('load', 'CustomerController@load');
        Route::match(['post', 'put'], 'submit', 'CustomerController@submit');
    });

    Route::prefix('products')->group(function(){
        Route::get('/', 'ProductController@index');
        Route::post('load', 'ProductController@load');
        Route::get('subcategory/{id}', 'ProductController@getSubcategory');
        Route::match(['post', 'put'], 'submit', 'ProductController@submit');
        Route::get('view/{product_code}', 'ProductController@view');

    });

    Route::prefix('product_sizes')->group(function(){
        Route::post('load', 'ProductSizeController@load');
        Route::post('submit', 'ProductSizeController@submit');
        Route::put('update', 'ProductSizeController@update');
        Route::post('edit_status', 'ProductSizeController@editStatus');
    });

    Route::prefix('medias')->group(function(){
        Route::post('load', 'ProductMediaController@load');
        Route::post('submit', 'ProductMediaController@submit');
        Route::post('delete', 'ProductMediaController@destroy');
    });

    Route::prefix('orders')->group(function(){
        Route::get('/', 'OrderController@index');
        Route::post('load', 'OrderController@load');
        Route::get('create', 'OrderController@create');
        Route::match(['post', 'put'], 'submit', 'OrderController@submit');
        Route::get('view/{code}', 'OrderController@view');
        Route::post('update_qty', 'OrderController@updateQty');
        Route::post('del_product', 'OrderController@delProduct');
        Route::post('del_size', 'OrderController@delSize');
    });
});


// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__.'/auth.php';
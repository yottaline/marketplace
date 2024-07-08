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
});


// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__.'/auth.php';
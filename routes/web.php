<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

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
    return view('/products.index');
});

Route::get('/products', [ProductController::class , 'index'])->name('products.index');
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
Route::post('/products/store', [ProductController::class, 'store'])->name('product.store');
Route::get('/products/{id}/edit',[ProductController::class, 'edit'])->name('products.edit');
Route::put('/products/{product}',[ProductController::class, 'update'])->name('products.update');
Route::delete('/products/{product}',[ProductController::class, 'destroy'])->name('products.destroy');

<?php

use Illuminate\Support\Facades\Route;
use Core\Products\Controllers\ProductController;

Route::get('enviar-inventario', [ProductController::class, 'indexProductsFromPostgres']);
Route::resource('productos', ProductController::class);
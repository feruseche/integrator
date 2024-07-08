<?php

use Illuminate\Support\Facades\Route;
use Core\Products\Controllers\ProductController;

Route::get('enviar-inventario', [ProductController::class, 'indexProductsFromPostgres']);
Route::get('enviar-inventario-nexus', [ProductController::class, 'integratorNexus']);
Route::resource('productos', ProductController::class);
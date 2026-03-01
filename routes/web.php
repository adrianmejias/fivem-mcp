<?php

use App\Http\Controllers\DocsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DocsController::class, 'index'])->name('docs.index');
Route::get('/quickstart', [DocsController::class, 'quickstart'])->name('docs.quickstart');
Route::get('/documentation', [DocsController::class, 'documentation'])->name('docs.documentation');

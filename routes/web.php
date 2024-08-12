<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\Contact\App\Http\Controllers\ContactController;

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

// Define route for GET request to contact page
Route::get('contact', [ContactController::class, 'index'])->name('contact.index');

// Define route for POST request to handle form submissions
Route::post('contact', [ContactController::class, 'store'])->name('contact.store');

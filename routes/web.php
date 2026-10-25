<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TagController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
|Route::get('/', function () {
|    return view('welcome');
|});
|
|
*/

Route::get('/', [ContactController::class, 'index']);

Route::get('/contacts/confirm', [ContactController::class, 'show']);
Route::post('/contacts/confirm', [ContactController::class, 'show'])
    ->name('contact.confirm');

Route::post('/contacts', [ContactController::class, 'store']);

Route::get('/thanks', function() {
    return view('contact.thanks');
})->name('contact.thanks');

Route::middleware('auth')->group(function () {
    //Adminのルート
    Route::resource('admin', AdminController::class)->parameters([
        'admin' => 'contact'
    ]);

    //タグのルート
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('tags', TagController::class);
    });
});
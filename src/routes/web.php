<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;

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

Route::get('/', [ContactController::class, 'index']);
Route::post('/confirm', [ContactController::class, 'confirm']);
Route::post('/contacts', [ContactController::class, 'store']);
Route::get('/thanks', function () {
    return view('thanks');
})->name('thanks');
Route::middleware('auth')->group(function () {
    Route::get('/admin', [ContactController::class, 'show']);
});
Route::delete('contacts/{contact}', [ContactController::class, 'destroy'])->name('contacts.destroy');
Route::get('/admin/search', [ContactController::class, 'search']);
Route::get('admin/export', [ContactController::class, 'export'])
    ->middleware('auth')
    ->name('admin.export');

<?php

use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/videos', [VideoController::class,'index'])->middleware(['auth'])->name('videos.index');

Route::post('/videos/store', [VideoController::class,'store'])->middleware(['auth'])->name('videos.store');

Route::get('/videos/{video}', [VideoController::class,'view'])->middleware(['auth'])->name('videos.show');

Route::get('/files/{file}', [VideoController::class,'getFile'])->middleware(['auth'])->name('getFile');

require __DIR__.'/auth.php';

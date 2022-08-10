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

Route::get('/videos', [VideoController::class,'index'])->name('videos.index');

Route::post('/videos/store', [VideoController::class,'store'])->name('videos.store');

Route::get('/videos/{video}', [VideoController::class,'view'])->name('videos.show');

Route::get('/files/{file}', [VideoController::class,'getFile'])->name('getFile');

require __DIR__.'/auth.php';

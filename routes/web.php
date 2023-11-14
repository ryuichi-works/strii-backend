<?php

use App\Http\Controllers\GutImageController;
use App\Http\Controllers\MakerController;
use App\Http\Controllers\RacketImageController;
use App\Models\GutImage;
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
    return ['Laravel' => app()->version()];
});

// require __DIR__.'/auth.php';
// require __DIR__.'/admin.php';
// require __DIR__.'/admin/api.php';
// require __DIR__.'/user/api.php';


Route::apiResource('api/makers', MakerController::class);

Route::apiResource('api/gut_images', GutImageController::class);

Route::apiResource('api/racket_images', RacketImageController::class);

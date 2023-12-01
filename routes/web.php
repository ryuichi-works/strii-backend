<?php

use App\Http\Controllers\GutController;
use App\Http\Controllers\GutImageController;
use App\Http\Controllers\GutReviewController;
use App\Http\Controllers\MakerController;
use App\Http\Controllers\MyEquipmentController;
use App\Http\Controllers\RacketController;
use App\Http\Controllers\RacketImageController;
use App\Http\Controllers\TennisProfileController;
use App\Http\Controllers\User\UserController;
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


Route::middleware('auth:admin')->apiResource('api/makers', MakerController::class);

Route::apiResource('api/gut_images', GutImageController::class);

Route::apiResource('api/racket_images', RacketImageController::class);

Route::apiResource('api/guts', GutController::class);
Route::get('api/guts/{id}/others', [GutController::class, 'getRandamOtherGuts']);

Route::apiResource('api/rackets', RacketController::class);
Route::get('api/rackets/{id}/others', [RacketController::class, 'getRandamOtherRackets']);

Route::apiResource('api/tennis_profiles', TennisProfileController::class);

Route::apiResource('api/my_equipments', MyEquipmentController::class);
Route::get('/api/my_equipments/user/{id}', [ MyEquipmentController::class, 'getAllEquipmentOfUser']);

// Route::group(function() {
//     Route::apiResource('api/my_equipments', MyEquipmentController::class);
//     Route::get('api/my_equipments/user/{userId}', [ MyEquipmentController::class, 'getAllEquipmentOfUser']);
// });

Route::apiResource('api/gut_reviews', GutReviewController::class);

Route::apiResource('api/users', UserController::class)->except('store');

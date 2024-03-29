<?php

use App\Http\Controllers\GutController;
use App\Http\Controllers\GutImageController;
use App\Http\Controllers\GutReviewController;
use App\Http\Controllers\MakerController;
use App\Http\Controllers\MyEquipmentController;
use App\Http\Controllers\RacketController;
use App\Http\Controllers\RacketImageController;
use App\Http\Controllers\RacketSeriesController;
use App\Http\Controllers\TennisProfileController;
use App\Http\Controllers\User\UserController;
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

Route::post('api/racket_series/upload_csv', [RacketSeriesController::class, 'storeByCsv']);
Route::apiResource('api/racket_series', RacketSeriesController::class);

Route::get('api/gut_images/search', [GutImageController::class, 'gutImageSearch']);
Route::apiResource('api/gut_images', GutImageController::class);

Route::get('api/racket_images/search', [RacketImageController::class, 'racketImageSearch']);
Route::apiResource('api/racket_images', RacketImageController::class);

Route::post('api/guts/upload_csv', [GutController::class, 'storeByCsv']);
Route::get('api/guts/search', [GutController::class, 'gutSearch']);
Route::apiResource('api/guts', GutController::class);
Route::get('api/guts/{id}/others', [GutController::class, 'getRandamOtherGuts']);

Route::get('api/rackets/search', [RacketController::class, 'racketSearch']);
Route::apiResource('api/rackets', RacketController::class);
Route::get('api/rackets/{id}/others', [RacketController::class, 'getRandamOtherRackets']);

Route::apiResource('api/tennis_profiles', TennisProfileController::class);
Route::get('api/tennis_profiles/user/{userId}', [TennisProfileController::class, 'getCurrentUserTennisProfile']);

Route::apiResource('api/my_equipments', MyEquipmentController::class);
Route::get('/api/my_equipments/user/{id}', [ MyEquipmentController::class, 'getAllEquipmentOfUser']);
Route::get('/api/my_equipments/user/{id}/search', [ MyEquipmentController::class, 'searchEquipmentOfUser']);


// Route::group(function() {
//     Route::apiResource('api/my_equipments', MyEquipmentController::class);
//     Route::get('api/my_equipments/user/{userId}', [ MyEquipmentController::class, 'getAllEquipmentOfUser']);
// });

Route::get('api/gut_reviews/search', [GutReviewController::class, 'gutReviewSearch']);
Route::apiResource('api/gut_reviews', GutReviewController::class);

Route::apiResource('api/users', UserController::class)->except('store');

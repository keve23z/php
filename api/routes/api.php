<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// controllers are inside App\Http\Controllers\Main
use App\Http\Controllers\Main\PhongController;
use App\Http\Controllers\Main\TienNghiController;
use App\Http\Controllers\Main\LoaiPhongController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// list all phongs for a given LoaiPhong (path parameter)
Route::get('phongs/loai/{maLoai}', [PhongController::class, 'searchByLoai']);
// detailed info for a room including its amenities
Route::get('phongs/{id}/details', [PhongController::class, 'details']);
// return the first room for a given LoaiPhong (with its amenities)
Route::apiResource('phongs', PhongController::class);
Route::apiResource('loaiphongs', LoaiPhongController::class);
// quick health-check route for debugging
Route::get('ping', function () {
    return response()->json(['ok' => true]);
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
/*
cd "I:\Ky_06_2025_2026\php\DoAnCuoiKy\Api\api"
php .\artisan serve --host=127.0.0.1 --port=8001
*/
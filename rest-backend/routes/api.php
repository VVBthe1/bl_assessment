<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PeopleController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

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

Route::group([
    'middleware' => ["api.response"]
], function () {
    Route::post('/login', [AuthController::class, 'login']);

    Route::group([
        'middleware' => ["auth:sanctum"]
    ], function () {
        Route::get("/test/error", function () {
            throw new BadRequestHttpException("Test Error Trace");
        });

        Route::get("/test/error", function () {
            throw new BadRequestHttpException("Test Error Trace");
        });

        Route::resource('peoples', PeopleController::class);
    });

    Route::get("/health-check", function () {
        return new JsonResponse("Hello, API");
    });
});




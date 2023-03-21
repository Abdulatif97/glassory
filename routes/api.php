<?php

use App\Http\Controllers\Api\v1\GlossaryController;
use App\Http\Controllers\Api\v1\PostController;
use Illuminate\Support\Facades\Route;

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


Route::group(["middleware" => ['custom_auth']], function () {

    Route::prefix('v1')->group(function () {

        Route::group([
            'prefix'    => 'post'
        ], function () {
            Route::get('/', [PostController::class, 'index'])->name('api.v1.post.index');
            Route::get('/{id}', [PostController::class, 'show'])->name('api.v1.post.show');
            Route::post('/', [PostController::class, 'store'])->name('api.v1.post.store');
            Route::put('/{id}', [PostController::class, 'update'])->name('api.v1.post.update');
            Route::delete('/{id}', [PostController::class, 'destroy'])->name('api.v1.post.destroy');
        });

        Route::group([
            'prefix'    => 'glossary'
        ], function () {
            Route::get('/', [GlossaryController::class, 'index'])->name('api.v1.glossary.index');
            Route::get('/{id}', [GlossaryController::class, 'show'])->name('api.v1.glossary.show');
            Route::post('/', [GlossaryController::class, 'store'])->name('api.v1.glossary.store');
            Route::put('/{id}', [GlossaryController::class, 'update'])->name('api.v1.glossary.update');
            Route::delete('/{id}', [GlossaryController::class, 'destroy'])->name('api.v1.glossary.destroy');
        });

    });

});

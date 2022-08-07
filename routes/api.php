<?php

use App\Http\Controllers\Api\NewsItemController;
use Illuminate\Http\Request;
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



Route::get('news_items/fetch', [NewsItemController::class, 'fetch'])->name('news_items.fetch');
Route::apiResource('news_items', NewsItemController::class)->only(['index', 'show', 'update']);;

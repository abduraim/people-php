<?php

use App\Http\Controllers\Web\NewsItemController;
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

    $url = 'https://www.rbc.ru/';
    $test = new \App\Services\HtmlParser\HtmlParser(new \App\Services\NewsResources\Rbc());



    dd('end');



    return view('welcome');
});

Route::get('news_items/fetch', [NewsItemController::class, 'fetch'])->name('news_items.fetch');
Route::resource('news_items', NewsItemController::class)->only(['index', 'show']);

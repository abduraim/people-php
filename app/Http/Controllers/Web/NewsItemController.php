<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\NewsItemResource;
use App\Jobs\FetchNewsJob;
use App\Models\NewsItem;
use App\Services\HtmlParser\HtmlParser;
use Illuminate\Http\Request;

class NewsItemController extends Controller
{
    public function index()
    {
        return view('pages.news_items.index', ['news' => NewsItem::query()->paginate()]);
//        return response()->json(NewsItemResource::collection(NewsItem::query()->paginate()));
    }

    public function show(NewsItem $newsItem)
    {
        return view('pages.news_items.item', ['newsItem' => $newsItem]);
    }

    /**
     * Загрузка новых новостей.
     */
    public function fetch(HtmlParser $htmlParser)
    {
        FetchNewsJob::dispatch(15);
        dd('Загрузка завершена');
    }
}

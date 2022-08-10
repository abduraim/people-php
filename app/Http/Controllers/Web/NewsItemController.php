<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Jobs\FetchNewsJob;
use App\Models\NewsItem;
use App\Services\HtmlParser\HtmlParser;
use Illuminate\Contracts\View\View;

class NewsItemController extends Controller
{
    /**
     * Страница со списком новостей
     */
    public function index(): View
    {
        return view('pages.news_items.index', ['news' => NewsItem::query()->paginate()]);
    }

    /**
     * Страница определенной новости
     */
    public function show(NewsItem $newsItem): View
    {
        return view('pages.news_items.item', ['newsItem' => $newsItem]);
    }

    /**
     * Загрузка новых новостей.
     */
    public function fetch(HtmlParser $htmlParser): void
    {
        FetchNewsJob::dispatch(15);
        echo 'Загрузка завершена';
    }
}

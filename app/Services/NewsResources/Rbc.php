<?php

namespace App\Services\NewsResources;

use App\Services\NewsResources\Contracts\NewsResourceInterface;
use App\Services\NewsResources\Traits\NewsResourceable;

class Rbc implements NewsResourceInterface
{
    use NewsResourceable;

    private string $url = 'https://www.rbc.ru/';
    private string $newsBlockSelector = '.js-news-feed-list';
    private string $linkSelector = '.news-feed__item';
    private string $titleSelector = '.news-feed__item__title';
    private string $textSelector = '.article__main .article__content';
}
<?php

namespace App\Services\NewsResources;

use App\Services\NewsResources\Contracts\NewsResourceInterface;
use App\Services\NewsResources\Traits\NewsResourceable;

class Rbc implements NewsResourceInterface
{
    use NewsResourceable;

    /** @var string $url Страница с новостным списком */
    private string $url = 'https://www.rbc.ru/';

    /** @var string $newsBlockSelector html-селектор блока со списком новостей */
    private string $newsBlockSelector = '.js-news-feed-list';

    /** @var string|array $linkSelector html-селектор ссылки на полную новость в блоке списка новостей */
    private string|array $linkSelector = '.news-feed__item';

    /** @var string|array $titleSelector html-селектор заголовка новости */
    private string|array $titleSelector = ['.article__header__title', '.article__title'];

    /** @var string|array $textSelector html-селектор текста новости */
    private string|array $textSelector = ['.article__text', '.article__content', ];

    /** @var string|array $imageSelector html-селектор изображения новости */
    private string|array $imageSelector = '.article__header-img img';
}
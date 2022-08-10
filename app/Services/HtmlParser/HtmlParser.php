<?php

namespace App\Services\HtmlParser;

use App\Models\NewsItem;
use App\Services\NewsResources\Contracts\NewsResourceInterface;
use Illuminate\Database\QueryException;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Парсер новостного ресурса
 */
class HtmlParser
{
    private bool $useCache = true;

    public function __construct(protected NewsResourceInterface $newsResource)
    {
        //
    }

    /**
     * Отключение кэширования результатов запроса к URL
     * @return void
     */
    public function turnOffCache(): void
    {
        $this->useCache = false;
    }

    /**
     * Включение кэширования результатов запроса к URL
     * @return void
     */
    public function turnOnCache(): void
    {
        $this->useCache = true;
    }

    /**
     * Установить новостной ресурс
     */
    public function setNewsResource(NewsResourceInterface $newsResource): void
    {
        $this->newsResource = $newsResource;
    }

    /**
     * Получает массив новостей из ресурса
     *
     * @param int $newsCount Кол-во новостей, которые необходимо загрузить
     * @return Collection
     */
    public function fetchNews(int $newsCount): Collection
    {
        $html = $this->getHtml($this->newsResource->getUrl());

        $crawler = new Crawler($html);

        return collect($crawler
            ->filter($this->newsResource->getNewsBlockSelector())
            ->filter($this->newsResource->getLinkSelector())
            ->each(function (Crawler $node, $index) use ($newsCount) {

                if ($index >= $newsCount) {
                    return false;
                }

                $newsItem = new NewsItem();

                // Source
                $newsItem->source = $this->newsResource->getUrl();

                // Source link
                $newsItem->source_link = $node->link()->getUri();

                // Parsing newsItem
                $html = $this->getHtml($newsItem->source_link);
                $crawler = new Crawler($html);

                // Title
                $newsItem->title = $this->getText($crawler, $this->newsResource->getTitleSelector());

                // Text
                $newsItem->text = $this->getText($crawler, $this->newsResource->getTextSelector());

                // Rating
                $newsItem->rating = rand(1, 10);

                // Image
                $newsItem->image = $this->getImageSrc($crawler, $this->newsResource->getImageSelector(),
                    $newsItem->source_link);

                return $newsItem;
            }));
    }


    /**
     * Запись новостей в хранилище
     *
     * @param Collection $news Массив новостей
     * @return array{failed: Collection<NewsItem>}
     */
    #[ArrayShape(['failed' => "Illuminate\\Support\\Collection"])]
    public function updateOrCreateNews(Collection $news): array
    {
        $failedNewsItems = collect();
        DB::transaction(function () use ($news, $failedNewsItems) {
            $news->each(function (NewsItem $newsItem) use ($failedNewsItems) {
                $uniqueFields = [
                    'source' => $newsItem['source'],
                    'title' => $newsItem['title'],
                ];

                try {
                    NewsItem::query()
                        ->updateOrCreate($uniqueFields, $newsItem->toArray());
                } catch (QueryException $exception) {
                    $failedNewsItems->push($newsItem);
                }

            });
        });

        return [
            'failed' => $failedNewsItems
        ];
    }

    /**
     * Возвращает текстовое содержимое из crawler'а по первому совпадению селектору (массиву селекторов)
     */
    private function getText(Crawler $crawler, string|array $selectors): string|null
    {
        foreach (Arr::wrap($selectors) as $selector) {
            $node = $this->getFirstFoundedNode($crawler, $selector);
            if ($node->count()) {
                return $node->text();
            }
        }
        return null;
    }

    /**
     * Возвращает текстовое содержимое из crawler'а по первому совпадению селектору (массиву селекторов)
     * и исходной ссылки новости
     */
    private function getImageSrc(Crawler $crawler, string|array $selectors, string $sourceLink): string|null
    {
        $imgSrc = null;
        foreach (Arr::wrap($selectors) as $selector) {
            $node = $this->getFirstFoundedNode($crawler, $selector);
            if ($node->count()) {
                $src = $node->extract(['src'])[0];
                if (!isset(parse_url($src)['host'])) {
                    $parsedUrl = parse_url($sourceLink);
                    $imgSrc = $parsedUrl['scheme'] . '://' . $parsedUrl['host'] . '/' . $src;
                } else {
                    $imgSrc =  $src;
                }
                break;
            }
        }
        return $imgSrc;
    }

    /**
     * Возвращает первую совпавшую ноду по селектору
     */
    private function getFirstFoundedNode(Crawler $crawler, string $selector): Crawler
    {
        return $crawler
            ->filter($selector)
            ->first();
    }

    /**
     * Возвращает html-содержимое страницы
     * @param string $url URL сайта
     */
    private function getHtml(string $url): mixed
    {
        if ($this->useCache) {
//            Cache::flush();
            return Cache::get($url, function () use ($url) {
                return Http::get($url)->body();
            });
        } else {
            return Http::get($url)->body();
        }
    }
}
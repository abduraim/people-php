<?php

namespace App\Services\HtmlParser;

use App\Models\NewsItem;
use App\Services\NewsResources\Contracts\NewsResourceInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Парсер новостного ресурса
 */
class HtmlParser
{
    public function __construct(protected NewsResourceInterface $newsResource)
    {
        //
    }

    /**
     * Установить новостной ресурс
     */
    public function setNewsResource(NewsResourceInterface $newsResource): void
    {
        $this->newsResource = $newsResource;
    }

    /**
     * @param int $newsCount Кол-во новостей, которые необходимо загрузить
     * @return int Кол-во загруженные новостей
     */
    public function fetch(int $newsCount)
    {
        $html = $this->getHtml($this->newsResource->getUrl());

        $crawler = new Crawler($html);

        $news = $crawler
            ->filter($this->newsResource->getNewsBlockSelector())
            ->filter($this->newsResource->getLinkSelector())
            ->each(function (Crawler $node, $index) use ($newsCount) {

                if ($index >= $newsCount) {
                    return false;
                }

                // Source
                $source = $this->newsResource->getUrl();

                // Source link
                $sourceLink = $node->link()->getUri();

                // Parsing newsItem
                $html = $this->getHtml($sourceLink);
                $crawler = new Crawler($html);

                // Title
                $title = $this->getText($crawler, $this->newsResource->getTitleSelector());

                // Text
                $text = $this->getText($crawler, $this->newsResource->getTextSelector());

                // Rating
                $rating = rand(1, 10);

                // Image
                $imageSrc = $this->getImageSrc($crawler, $this->newsResource->getImageSelector(), $sourceLink);

                // Updating or Creating NewsItem
                $uniqueFields = [
                    'source' => $this->newsResource->getUrl(),
                    'title' => $title,
                ];

                $newsItem = [
                    'title' => $title,
                    'text' => $text,
                    'image' => $imageSrc,
                    'rating' => $rating,
                    'source' => $this->newsResource->getUrl(),
                    'source_link' => $sourceLink
                ];

                NewsItem::query()
                    ->updateOrCreate($uniqueFields, $newsItem);
            });

        return count($news);
    }

    /**
     * Возвращает текстовое содержимое из crawler'а по первому совпадению селектору (массиву селекторов)
     */
    private function getText(Crawler $crawler, string|array $selectors): string|null
    {
        $text = null;
        foreach (Arr::wrap($selectors) as $selector) {
            $node = $this->getFirstFoundedNode($crawler, $selector);
            if ($node->count()) {
                return $node->text();
            }
        }
        return $text;
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
        return Cache::get($url, function () use ($url) {
            return Http::get($url)->body();
        });
    }
}
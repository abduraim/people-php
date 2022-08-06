<?php

namespace App\Services\HtmlParser;

use App\Models\NewsItem;
use App\Services\NewsResources\Contracts\NewsResourceInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

class HtmlParser
{
    protected NewsResourceInterface $newsResource;

    public function __construct(
        NewsResourceInterface $newsResource
    )
    {
        $this->newsResource = $newsResource;
        return false;
        $html = $this->getHtml($url);

        $newsListSelector = '.js-news-feed-list';
        $newsItemLinkSelector = '.news-feed__item';
        $newsItemTitleSelector = '.news-feed__item__title';

        $crawler = new Crawler($html);

        $news = $crawler
            ->filter($newsListSelector)
            ->filter($newsItemLinkSelector)
            ->each(function (Crawler $node, $i) use ($url, $newsItemTitleSelector) {

                $newsItem = new NewsItem();

                // Source
                $newsItem->source = $url;

                // Source link
                $newsItem->source_link = $node->link()->getUri();

                // Title
                $titleNode = $node->filter($newsItemTitleSelector)->first();
                $newsItem->title = $titleNode->count()
                    ? $titleNode->text()
                    : '';


                // Parsing newsItem
                $html = $this->getHtml($newsItem->source_link);
                $crawler = new Crawler($html);



                // Text
                $newsItemText = $crawler
                    ->filter('.article__main .article__content')
                    ->first();
                $newsItem->text = $newsItemText->count()
                    ? $newsItemText->text()
                    : '';

                // Rating
                $newsItem->rating = rand(1, 5);

                // Save news item
                $newsItem->save();

                $newsItemImage = $crawler
                    ->filter('.article__image');

                dd($newsItemImage);

                dd($newsItemText->text());

                $newsItemSlug = $crawler
                    ->filter('.is-hide')
                    ->text();

                dd($newsItemSlug);



                dd($newsItem);

                $html = Http::get($link)->body();

                dd($html);
            });

        foreach ($news as $newsItem) {
            dd($newsItem);
        }
        dd($news);

        $this->document = new \DOMDocument();
        libxml_use_internal_errors(true);
        $this->document->loadHTML($html);
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

                // Title
                $titleNode = $node
                    ->filter($this->newsResource->getTitleSelector())
                    ->first();
                $title = $titleNode->count()
                    ? $titleNode->text()
                    : '';


                // Parsing newsItem
                $html = $this->getHtml($sourceLink);

                $crawler = new Crawler($html);

                // Text
                $newsItemText = $crawler
                    ->filter($this->newsResource->getTextSelector())
                    ->first();
                $text = $newsItemText->count()
                    ? $newsItemText->text()
                    : '';

                // Rating
                $rating = rand(1, 5);

                // Save news item
//                $newsItem->save();

//                $newsItemImage = $crawler
//                    ->filter('.article__image');

//                dd($newsItemImage);
            });

        return count($news);
    }

    public function getElementByClass(string $className)
    {
        $this->founded = [];
        $element = $this->test($this->document, $className);
        return $this->founded;
    }

    public function test(\DOMNode $element, string $className)
    {
        if ($element->hasChildNodes()) {
            foreach ($element->childNodes as $childNode) {
                $this->test($childNode, $className);
            }
        }


        if ($element instanceof \DOMElement) {
            if ($element->hasAttribute('class')) {
                $class = $element->getAttribute('class');
                if ($class === $className) {
                    $this->founded[] = $element;
                }
            }
        }


    }

    private function getHtml(string $url)
    {
        return Cache::get($url, function () use ($url) {
            return Http::get($url)->body();
        });
    }
}
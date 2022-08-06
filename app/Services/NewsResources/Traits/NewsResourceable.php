<?php

namespace App\Services\NewsResources\Traits;

trait NewsResourceable
{
    public function getUrl(): string
    {
        return $this->url;
    }
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function getNewsBlockSelector(): string
    {
        return $this->newsBlockSelector;
    }
    public function setNewsBlockSelector(string $selector): void
    {
        $this->newsBlockSelector = $selector;
    }

    public function getLinkSelector(): string
    {
        return $this->linkSelector;
    }

    public function setLinkSelector(string $selector): void
    {
        $this->linkSelector = $selector;
    }

    public function getTitleSelector(): string
    {
        return $this->titleSelector;
    }

    public function setTitleSelector(string $selector): void
    {
        $this->titleSelector = $selector;
    }

    public function getTextSelector(): string
    {
        return $this->textSelector;
    }

    public function setTextSelector(string $selector): void
    {
        $this->textSelector = $selector;
    }
}
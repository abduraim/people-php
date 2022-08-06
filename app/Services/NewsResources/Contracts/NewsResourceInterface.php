<?php

namespace App\Services\NewsResources\Contracts;

interface NewsResourceInterface
{
    /**
     * Возвращает Url сайта-ресурса
     */
    public function getUrl(): string;

    /**
     * Устанавливает Url сайта-ресурса
     * @param string $url Url сайта-ресурса
     */
    public function setUrl(string $url): void;

    /**
     * Возвращает html-селектор блока со списком новостей
     */
    public function getNewsBlockSelector(): string;

    /**
     * Устанавливает html-селектор блока со списком новостей
     * @param string $selector html-селектор блока со списком новостей
     */
    public function setNewsBlockSelector(string $selector): void;

    /**
     * Возвращает html-селектор ссылки на полную новость в блоке списка новостей
     */
    public function getLinkSelector(): string;

    /**
     * Устанавливает html-селектор ссылки на полную новость в блоке списка новостей
     * @param string $selector
     */
    public function setLinkSelector(string $selector): void;


    /**
     * Возвращает html-селектор заголовка новости
     */
    public function getTitleSelector(): string;

    /**
     * Устанавливает html-селектор заголовка новости
     * @param string $selector
     */
    public function setTitleSelector(string $selector): void;

    /**
     * Возвращает html-селектор текста новости
     */
    public function getTextSelector(): string;

    /**
     * Устанавливает html-селектор текста новости
     * @param string $selector
     */
    public function setTextSelector(string $selector): void;
}
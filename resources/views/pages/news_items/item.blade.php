@php
    /** @var \App\Models\NewsItem $newsItem */
@endphp

@extends('layouts.main')

@section('content')
    <div class="news-item">
        <h1 class="news-item__title">{{ $newsItem->title }}</h1>
        <div class="details news-item__details">
            <span class="details__rating">
                Рейтинг: {{ $newsItem->rating }}
            </span>
            <a class="details__source-link" href="{{ $newsItem->source_link }}" target="_blank">ссылка на
                источник</a>
        </div>

        <div class="news-item__text">
            @if($newsItem->image)
                <img class="news-item__img" src="{{ $newsItem->image }}" alt="{{ $newsItem->title }}">
            @endif
                {{ $newsItem->text }}
        </div>
    </div>
@endsection



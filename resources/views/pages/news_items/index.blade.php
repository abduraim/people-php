@php
    /** @var \App\Models\NewsItem $newsItem */
@endphp

@extends('layouts.main')

@section('content')
    <div class="news">
        @foreach($news as $newsItem)
            <div class="item news__item">
                <h2 class="item__title">
                    <a class="item__title-link" href="{{ route('news_items.show', $newsItem) }}">
                        {{ $newsItem->title }}
                    </a>
                </h2>
                <div class="content item__content">
                    <div class="content__rating">
                        Рейтинг: {{ $newsItem->rating }}
                    </div>
                    <div class="content__slug">
                        {{ \Illuminate\Support\Str::limit($newsItem->text, 200) }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="paginator">
        {{ $news->links() }}
    </div>
@endsection

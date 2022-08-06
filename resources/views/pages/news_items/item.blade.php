@php
/** @var \App\Models\NewsItem $newsItem */
@endphp
<p>Рейтинг: {{ $newsItem->rating }} (<a href="{{ $newsItem->source_link }}" target="_blank">ссылка на источник</a>)</p>
<h1>{{ $newsItem->title }}</h1>
<p>{{ $newsItem->text }}</p>
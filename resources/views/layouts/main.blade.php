<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Тестовое задание PHP 4people</title>

    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>
<body>

<div class="wrapper">
    <h1 class="app__title">Тестовое задание PHP 4people</h1>
</div>

<div class="wrapper">
    <nav>
        <ul class="navigation">
            <li class="navigation__item">
                <a class="navigation__item-link" href="{{ route('news_items.index') }}">Новости</a>
            </li>
            <li class="navigation__item">
                <a class="navigation__item-link" href="{{ route('news_items.fetch') }}" target="_blank">
                    Поставить задачу на загрузку новых новостей
                </a>
            </li>
        </ul>
    </nav>
</div>

<div class="wrapper">
    @yield('content')
</div>

</body>
</html>

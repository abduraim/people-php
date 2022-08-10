<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewsItem\UpdateRequest;
use App\Http\Resources\NewsItem\CollectionResource;
use App\Http\Resources\NewsItem\ItemResource;
use App\Jobs\FetchNewsJob;
use App\Models\NewsItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class NewsItemController extends Controller
{
    /**
     * Список новостей
     */
    public function index(): AnonymousResourceCollection
    {
        return CollectionResource::collection(NewsItem::paginate());
    }

    /**
     * Определенная новость
     */
    public function show(NewsItem $newsItem): ItemResource
    {
        return ItemResource::make($newsItem);
    }

    /**
     * Обновление новости
     *
     * @throws \Throwable
     */
    public function update(UpdateRequest $request, NewsItem $newsItem): Response
    {
        $newsItem->updateOrFail($request->validated());

        return response()->noContent(200);
    }

    /**
     * Поставка задачи на загрузку новых новостей
     *
     * @return JsonResponse
     */
    public function fetch(): JsonResponse
    {
        FetchNewsJob::dispatch(15);

        return response()->json([
            'data' => 'Задача на загрузку новостей поставлена в очередь'
        ]);
    }
}

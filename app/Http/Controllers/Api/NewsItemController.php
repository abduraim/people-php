<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewsItem\UpdateRequest;
use App\Http\Resources\NewsItem\CollectionResource;
use App\Http\Resources\NewsItem\ItemResource;
use App\Http\Resources\NewsItemResource;
use App\Jobs\FetchNewsJob;
use App\Models\NewsItem;

class NewsItemController extends Controller
{
    public function index()
    {
        return CollectionResource::collection(NewsItem::paginate());
    }

    public function show(NewsItem $newsItem)
    {
        return ItemResource::make($newsItem);
    }

    public function update(UpdateRequest $request, NewsItem $newsItem)
    {
        $newsItem->updateOrFail($request->validated());

        return response()->noContent();
    }

    public function fetch()
    {
        FetchNewsJob::dispatch(15);
    }
}

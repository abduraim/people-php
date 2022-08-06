<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewsItem\UpdateRequest;
use App\Http\Resources\NewsItemResource;
use App\Models\NewsItem;

class NewsItemController extends Controller
{
    public function index()
    {
        return NewsItemResource::collection(NewsItem::paginate());
    }

    public function update(UpdateRequest $request, NewsItem $newsItem)
    {
        $newsItem->updateOrFail($request->validated());

        return response()->noContent();
    }
}

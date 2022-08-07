<?php

namespace App\Http\Resources\NewsItem;

use App\Models\NewsItem;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

/**
 * @mixin NewsItem
 */
class CollectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => Str::limit($this->text, 200),
            'rating' => $this->rating,
            'link' => env('APP_URL') . '/news_items/' . $this->id,
        ];
    }
}

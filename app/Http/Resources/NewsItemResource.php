<?php

namespace App\Http\Resources;

use App\Models\NewsItem;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin NewsItem
 */
class NewsItemResource extends JsonResource
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
            'rating' => $this->rating,
            'link' => env('APP_URL') . '/news_items/' . $this->id,
        ];
    }
}

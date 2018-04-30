<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use App\Category;

class Movie extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        $category_name = Category::where('id', $this->category_id)->pluck('name')[0];
        return [
            'id' => $this->id,
            'category_id' => $this->category_id,
            'category_name' => $category_name,
            'title' => $this->title,
            'year' => $this->year,
            'url' => $this->url,
            'youtubeId' => $this->youtubeId,
            'image' => $this->image
        ];
    }
}

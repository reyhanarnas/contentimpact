<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'title'         => $this->title,
            'slug'          => $this->slug,
            'excerpt'       => $this->excerpt,
            'content'       => $this->content,
            'cover_image'   => $this->cover_image_url,
            'views'         => $this->views,
            'reading_time'  => $this->reading_time . ' min read',
            'published_at'  => $this->published_at?->toDateTimeString(),
            'author' => [
                'name'   => $this->author?->name,
                'avatar' => $this->author?->avatar_url,
            ],
            'category' => [
                'id'   => $this->category?->id,
                'name' => $this->category?->name,
                'slug' => $this->category?->slug,
            ],
        ];
    }
}

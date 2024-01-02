<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class AdminProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->resource->id,
            'name'              => $this->resource->name,
            'shortDesc'         => $this->resource->short_description,
            'desc'              => $this->resource->description,
            'price'             => $this->resource->price,
            'image'             => url(Storage::url('products/images/' . $this->resource->image)),
            'category'          => $this->resource->category->name,
            'featured'          => $this->resource->featured,
            'createdAt'         => $this->resource->created_at,
            'createdAtHuman'    => $this->resource->created_at->diffForHumans(),
            'updatedAt'         => $this->resource->updated_at,
            'updatedAtHuman'    => $this->resource->updated_at->diffForHumans(),
        ];
    }
}

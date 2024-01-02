<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProductResource extends JsonResource
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
            'slug'              => $this->resource->slug(),
            'shortDesc'         => $this->resource->short_description,
            'desc'              => $this->resource->description,
            'price'             => $this->resource->price,
            'image'             => url(Storage::url('products/images/' . $this->resource->image)),
            'category'          => $this->resource->category->name,
            'featured'          => $this->resource->featured,
            'variations'        => $this->when($this->resource->variations, ProductVariationResource::collection($this->resource->variations), [])
        ];
    }
}

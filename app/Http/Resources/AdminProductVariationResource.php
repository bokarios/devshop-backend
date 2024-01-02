<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class AdminProductVariationResource extends JsonResource
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
            'product'           => $this->resource->product->name,
            'price'             => $this->resource->price,
            'image'             => url(Storage::url('products/images/' . $this->resource->image)),
            'color'             => ['name' => $this->resource->color->name, 'value' => $this->resource->color->value],
            'sizes'             => json_decode($this->resource->sizes),
            'createdAt'         => $this->resource->created_at,
            'createdAtHuman'    => $this->resource->created_at->diffForHumans(),
            'updatedAt'         => $this->resource->updated_at,
            'updatedAtHuman'    => $this->resource->updated_at->diffForHumans(),
        ];
    }
}

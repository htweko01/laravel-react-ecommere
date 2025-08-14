<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

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
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'department' => new DepartmentResource($this->whenLoaded('department')),
            'stock' => $this->stock,
            'variants' => ProductVariantResource::collection($this->whenLoaded('variants')),
            'media' => MediaResource::collection($this->media),
            'created_at' => $this->created_at,  
            'updated_at' => $this->updated_at,
        ];
    }
}

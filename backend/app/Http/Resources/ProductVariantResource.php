<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantResource extends JsonResource
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
            'product_id' => $this->product_id,
            'price' => $this->price,
            'stock' => $this->stock,
            // 'attributes' => $this->whenLoaded('attributes', function () {
            //     return $this->attributes->map(function ($attribute) {
            //         return [
            //             'id' => $attribute->id,
            //             'name' => $attribute->name,
            //             'value' => $attribute pivot ? $attribute->pivot->value : null,
            //         ];
            //     });
            // }),
            'sku' => $this->sku,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

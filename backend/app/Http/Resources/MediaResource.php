<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'size' => $this->humanReadableSize,
            'name' => $this->name,
            'filename' => $this->file_name,
            'mime' => $this->mime_type,
            'order' => $this->order_column,
            'url' => $this->getFullUrl(),
            // 'urls' => [
            //     'full' => $this->getFullUrl(),
            //     'conversions' => collect($this->generated_conversions)
            //                         ->map(fn($value, $key) => $this->getFullUrl($key)),
            //     'responsive' => $this->getResponsiveImageUrls()
            // ],
            'custom_data' => $this->custom_properties,
        ];
    }
}

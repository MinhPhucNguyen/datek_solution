<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'sku' => $this->sku,
            'brand' => [
                'brand_id' => $this->brand->id,
                'name' => $this->brand->brand_name
            ],
            'categories' => $this->categories,
            'price' => $this->price,
            'product_type' => $this->productType,
            'description' => $this->description,
            'quantity' => $this->quantity,
            'status' => $this->status,
            'product_images' => $this->productImages,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'order_id' => $this->order_id,
            'user_id' => $this->user_id,
            'user_name' => $this->user->name,
            'address' => $this->shippingAddress->address,
            'order_total_price' => $this->order_total_price,
            'order_status' => $this->order_status,
            'order_date' => $this->order_date,
        ];
    }
}

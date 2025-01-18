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
            'user_name' => $this->user->lastname . ' ' . $this->user->firstname,
            'address' => $this->shippingAddress->address,
            'order_total_price' => $this->order_total_price,
            'order_status' => $this->order_status,
            'order_date' => $this->order_date,
            'payment_method' => $this->payment_method ?? "",
            'order_details' => $this->orderDetails->map(function ($orderDetail) {
                return [
                    'product_id' => $orderDetail->product_id,
                    'product_name' => $orderDetail->product->name,
                    'product_image' => $orderDetail->product->productImages->first()->image_url,
                    'product_price' => $orderDetail->product->price,
                    'order_detail_quantity' => $orderDetail->order_detail_quantity,
                ];
            }),
        ];
    }
}

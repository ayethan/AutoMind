<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuotationProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'product_id' => $this->pivot->product_id,
            'quantity' => $this->pivot->quantity,
            'price' => $this->pivot->price,
            'discount' => $this->pivot->discount,
            'total' => $this->pivot->total,
            'product' => new ProductResource($this) // Assuming ProductResource exists or will be created
        ];
    }
}

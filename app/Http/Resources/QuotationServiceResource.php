<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuotationServiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'service_id' => $this->pivot->service_id,
            'quantity' => $this->pivot->quantity,
            'price' => $this->pivot->price,
            'discount' => $this->pivot->discount,
            'total' => $this->pivot->total,
            'service' => new ServiceResource($this) // Assuming ServiceResource exists or will be created
        ];
    }
}

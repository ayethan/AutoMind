<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuotationResource extends JsonResource
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
            'customer_id' => $this->customer_id,
            'customer' => new CustomerResource($this->whenLoaded('customer')), // Assuming CustomerResource exists
            'user_id' => $this->user_id,
            'user' => new UserResource($this->whenLoaded('user')), // Assuming UserResource exists
            'quotation_date' => $this->quotation_date,
            'expiration_date' => $this->expiration_date,
            'status' => $this->status,
            'title' => $this->title,
            'notes' => $this->notes,
            'subtotal' => $this->subtotal,
            'discount_amount' => $this->discount_amount,
            'tax_amount' => $this->tax_amount,
            'total_amount' => $this->total_amount,
            'converted_sale_id' => $this->converted_sale_id,
            'converted_sale' => new SaleResource($this->whenLoaded('convertedSale')), // Assuming SaleResource exists
            'products' => QuotationProductResource::collection($this->whenLoaded('products')), // Will create this
            'services' => QuotationServiceResource::collection($this->whenLoaded('services')), // Will create this
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

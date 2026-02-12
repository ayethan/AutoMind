<?php

namespace App\Http\Resources;

use App\Http\Resources\ProductResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "product" => new ProductResource($this->product),
            "sell_price" => $this->sell_price,
            "buy_price" => $this->buy_price,
            "qty" => $this->qty,
            "total" => $this->total,
            "remark" => $this->remark
        ];
    }
}
 
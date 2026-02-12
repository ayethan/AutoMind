<?php

namespace App\Http\Resources;

use App\Http\Resources\ServiceResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleServiceResource extends JsonResource
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
            "service" => new ServiceResource($this->service),
            "price" => $this->price,
            "qty" => $this->qty,
            "total" => $this->total,
            "remark" => $this->remark
        ];
    }
}

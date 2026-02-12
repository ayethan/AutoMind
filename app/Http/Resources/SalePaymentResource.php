<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SalePaymentResource extends JsonResource
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
            "amount" => $this->amount,
            "date" => $this->date,
            "sale" => $this->whenLoaded('sale'),
            "remark" => $this->remark,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at
        ];
    }
}

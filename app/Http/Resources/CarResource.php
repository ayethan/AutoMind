<?php

namespace App\Http\Resources;

use App\Http\Resources\CarMadeResource;
use App\Http\Resources\CustomerResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CarResource extends JsonResource
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
            "car_no" => $this->car_no,
            "model" => $this->model,
            "car_made_id" => $this->car_made_id,
            "customer_id" => $this->customer_id,
            "car_made" => new CarMadeResource($this->whenLoaded('car_made')),
            "customer" => new CustomerResource($this->whenLoaded('customer')),
        ];
    }
}

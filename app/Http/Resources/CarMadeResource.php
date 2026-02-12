<?php

namespace App\Http\Resources;

use App\Http\Resources\CarResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CarMadeResource extends JsonResource
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
            "cars" => CarResource::collection($this->whenLoaded("cars"))
        ];
    }
}

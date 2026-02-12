<?php

namespace App\Http\Resources;

use App\Http\Resources\ProductPurchaseResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            "code" => $this->code,
            "buy_price" => $this->buy_price,
            "sell_price" => $this->sell_price,
            "stock" => $this->stock,
            "remark" => $this->remark,
            "product_purchase" => ProductPurchaseResource::collection($this->whenLoaded('product_purchases')),
            "category_id" => $this->category_id,
            "category" => new CategoryResource($this->whenLoaded("category")),
            "sub_category" => new SubCategoryResource($this->whenLoaded("sub_category")),
            "sub_category_id" => $this->sub_category_id
        ];
    }
}

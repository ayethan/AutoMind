<?php

namespace App\Http\Resources;

use App\Http\Resources\UserResource;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\SaleProductResource;
use App\Http\Resources\SaleServiceResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\SaleExternalProductResource;

class SaleResource extends JsonResource
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
            "is_taxi" => boolval($this->is_taxi),
            "date" => $this->date,
            "discount" => $this->discount,
            "sub_total" => $this->sub_total,
            "tax" => $this->tax,
            "total" => $this->total,
            "remark" => $this->remark,
            "status" => $this->status,
            "status_name" => config('tinyerp.sale-status')[$this->status],
            "is_paid" => boolval($this->is_paid),
            "job_done_by" => $this->job_done_by,
            "mileage" => $this->mileage,
            
            "car_id" => $this->car_id,
            "customer_id" => $this->customer_id,
            
            "customer" => new CustomerResource($this->customer),
            "user" => new UserResource($this->user),
            "sale_products" => SaleProductResource::collection($this->whenLoaded('sale_products')),
            "sale_external_products" => SaleExternalProductResource::collection($this->whenLoaded('sale_external_products')),
            "sale_services" => SaleServiceResource::collection($this->whenLoaded('sale_services')),
            "expenses" => ExpenseResource::collection($this->whenLoaded('expenses')),
            "payments" => SalePaymentResource::collection($this->whenLoaded('payments')),
            "car" => new CarResource($this->whenLoaded('car')),
            
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at
        ];
    }
}

<?php

namespace App\Http\Resources;

use App\Http\Resources\ExpenseTypeResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseResource extends JsonResource
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
            'id' => $this->id,
            'expense_type_id' => $this->expense_type_id,
            'expense_type' => new ExpenseTypeResource($this->expense_type),
            'date' => $this->date,
            'amount' => $this->amount,
            'remark' => $this->remark
        ];
    }
}

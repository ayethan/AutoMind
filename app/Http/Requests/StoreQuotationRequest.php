<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuotationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_id' => 'required|exists:customers,id',
            'user_id' => 'sometimes|exists:users,id',
            'quotation_date' => 'sometimes|date',
            'expiration_date' => 'nullable|date|after_or_equal:quotation_date',
            'status' => 'sometimes|in:draft,sent,accepted,rejected,converted,expired',
            'title' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'products' => 'nullable|array',
            'products.*.product_id' => 'required_with:products|exists:products,id',
            'products.*.quantity' => 'required_with:products|integer|min:1',
            'products.*.price' => 'nullable|numeric|min:0',
            'products.*.discount' => 'nullable|numeric|min:0',
            'services' => 'nullable|array',
            'services.*.service_id' => 'required_with:services|exists:services,id',
            'services.*.quantity' => 'required_with:services|integer|min:1',
            'services.*.price' => 'nullable|numeric|min:0',
            'services.*.discount' => 'nullable|numeric|min:0',
        ];
    }
}

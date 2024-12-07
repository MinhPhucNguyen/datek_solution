<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'brand_id' => 'required | sometimes',
            'name' => 'required | sometimes',
            'sku' => 'required | sometimes',
            'quantity' => 'required | sometimes',
            'price' => 'required | sometimes',
            'category_ids' => 'required | sometimes',
            'product_type_id' => 'required | sometimes',
        ];
    }
}

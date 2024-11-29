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
            'sub_category_id' => 'required',
            'brand_id' => 'required',
            'name' => 'required',
            'sku' => 'required',
            'quantity' => 'required',
            'price' => 'required',
            'product_type_id' => 'required',
            'status' => 'required',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
}

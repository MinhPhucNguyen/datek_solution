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
            'quantity' => 'required | sometimes | integer | min:1',
            'price' => 'required | sometimes',
            'category_ids' => 'required | sometimes',
        ];
    }

    public function messages()
    {
        return [
            'brand_id.required' => '*Vui lòng chọn hãng của sản phẩm',
            'name.required' => '*Vui lòng nhập tên của sản phẩm',
            'sku.required' => '*Vui lòng nhập mã sản phẩm',
            'quantity.required' => '*Vui lòng nhập số lượng sản phẩm',
            'quantity.integer' => '*Số lượng sản phẩm phải là số nguyên',
            'quantity.min' => '*Số lượng sản phẩm phải lớn hơn 0',
            'price.required' => '*Vui lòng nhập giá sản phẩm',
            'category_ids.required' => '*Vui lòng chọn danh mục cho sản phẩm',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserFormRequest extends FormRequest
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
        $id = $this->route('id');
        $isUpdate = $this->getMethod() == 'PUT' || $this->getMethod() == 'PATCH';

        $rules = [
            'firstname' => [
                $isUpdate ? 'sometimes' : 'required',
                'string',
                'regex:/^[\p{L} ]+$/u', 
            ],
            'lastname' => [
                $isUpdate ? 'sometimes' : 'required',
                'string',
                'regex:/^[\p{L} ]+$/u', 
            ],
            'address' => [
                $isUpdate ? 'sometimes' : 'required',
                'string',
                'regex:/^[\d\p{L}\s.,\-\/()]+$/u',
                'max:150',
            ],
            'role_as' => [
                $isUpdate ? 'sometimes' : 'required',
            ]
        ];

        if ($isUpdate) {
            $rules['email'] =  [
                $isUpdate ? 'sometimes' : 'required',
                'email',
                Rule::unique('users', 'email')->ignore($id)
            ];
            $rules['phone'] = [
                $isUpdate ? 'sometimes' : 'required',
                'min:10',
                'max:10',
                Rule::unique('users', 'phone')->ignore($id)
            ];
            $rules['password'] = 'sometimes|nullable|string|min:8|regex:/^(?=.{10,}$)(?=.*?[a-z])(?=.*?[A-Z])(?=.*?[0-9])(?=.*?\W).*$/|unique:users';
            $rules['confirm_password'] = 'sometimes|nullable|string|same:password';
        } else {
            $rules['email'] =  [
                'required',
                'string',
                'email',
                'unique:users,email'
            ];
            $rules['phone'] = [
                'required',
                'min:10',
                'max:10',
                'unique:users,phone'
            ];
            $rules['password'] = 'required|string|min:8|regex:/^(?=.{10,}$)(?=.*?[a-z])(?=.*?[A-Z])(?=.*?[0-9])(?=.*?\W).*$/|unique:users';
            $rules['confirm_password'] = 'required|same:password';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'firstname.required' => '*Vui lòng nhập tên',
            'firstname.string' => '*Tên phải là ký tự',
            'firstname.regex' => '*Tên không được chứa số hoặc ký tự đặc biệt',

            'lastname.required' => '*Vui lòng nhập họ',
            'lastname.string' => '*Họ phải là ký tự',
            'lastname.regex' => '*Họ không được chứa số hoặc ký tự đặc biệt',

            'email.required' => '*Vui lòng nhập email',
            'email.email' => '*Email không hợp lệ',
            'email.unique' => '*Email đã tồn tại',

            'phone.required' => '*Vui lòng nhập số điện thoại',
            'phone.min' => '*Số điện thoại phải có 10 số',
            'phone.max' => '*Số điện thoại phải có 10 số',

            'address.required' => '*Vui lòng nhập địa chỉ',
            'address.regex' => '*Địa chỉ không hợp lệ',
            'address.max' => '*Địa chỉ không được quá 150 ký tự',

            'role_as.required' => "*Vui lòng chọn vai trò",

            'password.required' => '*Vui lòng nhập mật khẩu',
            'password.min' => '*Mật khẩu phải có ít nhất 8 ký tự',
            'password.unique' => '*Mật khẩu đã tồn tại',
            'password.regex' => '*Mật khẩu phải chứa ít nhất 1 chữ hoa, 1 chữ thường, 1 số và 1 ký tự đặc biệt',

            'confirm_password.required' => '*Vui lòng nhập lại mật khẩu',
            'confirm_password.same' => '*Mật khẩu không khớp',
        ];
    }
}

<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'firstname' => [
                'required',
                'string',
                'regex:/^[a-zA-Z\s]+$/'
            ],
            'lastname' => [
                'required',
                'string',
                'regex:/^[a-zA-Z\s]+$/'
            ],
            'email' => [
                'required',
                'string',
                'email',
                'unique:users,email',
            ],
            'password' => [
                'required',
                'min:8',
                'regex:/^(?=.{10,}$)(?=.*?[a-z])(?=.*?[A-Z])(?=.*?[0-9])(?=.*?\W).*$/',
                'unique:users,password',
            ],
            'confirm_password' => [
                'required',
                'string',
                'same:password',
            ]
        ];
    }

    public function messages()
    {
        return [
            'firstname.required' => '*Vui lòng nhập tên của bạn',
            'firstname.string' => '*Tên phải là ký tự',
            'firstname.regex' => '*Tên không chứa ký tự đặc biệt hoặc số',
            'lastname.required' => '*Vui lòng nhập họ của bạn',
            'lastname.string' => '*Họ phải là ký tự',
            'lastname.regex' => '*Họ không được chứa ký tự đặc biệt',
            'email.required' => '*Vui lòng nhập email',
            'email.email' => '*Email không hợp lệ.',
            'email.unique' => '*Email đã tồn tại. ',
            'password.required' => '*Vui lòng nhập mật khẩu',
            'password.min' => '*Mật khẩu phải có độ dài ít nhất là 8 ký tự',
            'password.unique' => '*Mật khẩu đã tồn tại.',
            'password.regex' => '*Mật khẩu phải có ít nhất 1 ký tự hoa, 1 ký tự thường, 1 số và 1 ký tự đặc biệt',
            'confirm_password.required' => '*Vui lòng nhập lại mật khẩu',
            'confirm_password.same' => '*Mật khẩu nhập lại không khớp',
        ];
    }
}

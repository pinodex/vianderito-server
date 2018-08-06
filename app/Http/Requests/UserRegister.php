<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRegister extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'username' => [
                'required',
                'unique:users,username',
                'regex:/^[A-Za-z0-9]+(?:[ _-][A-Za-z0-9]+)*$/u'
            ],
            'password' => 'required',
            'email_address' => [
                'required_without_all:phone_number',
                'email',
                'unique:users,email_address'
            ],
            'phone_number' => [
                'required_without_all:email_address',
                'regex:/^(09|\+639)\d{9}$/',
                'unique:users,phone_number'
            ]
        ];
    }
}

<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProfile extends FormRequest
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
        $user = Auth::guard('api')->user();

        return [
            'name' => 'required',
            'username' => [
                'required',
                'unique:users,username,' . $user->id,
                'regex:/^[A-Za-z0-9]+(?:[ _-][A-Za-z0-9]+)*$/u'
            ],
            'email_address' => [
                'required_without_all:phone_number',
                'email',
                'unique:users,email_address,' . $user->id
            ],
            'phone_number' => [
                'required_without_all:email_address',
                'regex:/^(09|\+639)\d{9}$/',
                'unique:users,phone_number,' . $user->id
            ]
        ];
    }
}

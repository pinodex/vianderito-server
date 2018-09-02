<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveUser extends FormRequest
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
        $model = $this->route()->parameter('model');

        $rules = [
            'name' => 'required',
            'username' => [
                'required',
                'unique:users,username',
                'regex:/^[A-Za-z0-9]+(?:[ _-][A-Za-z0-9]+)*$/u'
            ],
            'email_address' => [
                'nullable',
                'email',
                'unique:users,email_address'
            ],
            'phone_number' => [
                'nullable',
                'regex:/^(09|\+639)\d{9}$/',
                'unique:users,phone_number'
            ]
        ];

        if ($model) {
            $rules['username'] = [
                'required',
                'unique:users,username,' . $model->id,
                'regex:/^[A-Za-z0-9]+(?:[ _-][A-Za-z0-9]+)*$/u'
            ];

            $rules['email_address'] = [
                'nullable',
                'email',
                'unique:users,email_address,' . $model->id
            ];

            $rules['phone_number'] = [
                'nullable',
                'regex:/^(09|\+639)\d{9}$/',
                'unique:users,phone_number,' . $model->id
            ];
        }

        return $rules;
    }
}

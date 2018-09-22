<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveAccount extends FormRequest
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
            'first_name'    => 'required|regex:/^[\pL\s\-]+$/u',
            'middle_name'   => 'nullable|regex:/^[\pL\s\-]+$/u',
            'last_name'     => 'required|regex:/^[\pL\s\-]+$/u',
            'username'      => [
                'required',
                'unique:accounts',
                'regex:/^[A-Za-z0-9]+(?:[ _-][A-Za-z0-9]+)*$/u'
            ],
            'email' => [
                'email',
                'unique:accounts,email'
            ],
            'department_id'      => 'required|exists:departments,id'
        ];

        if ($model) {
            $rules['username'] = [
                'required',
                'unique:accounts,username,' . $model->id,
                'regex:/^[A-Za-z0-9]+(?:[ _-][A-Za-z0-9]+)*$/u'
            ];

            $rules['email'] = [
                'email',
                'unique:accounts,email,' . $model->id
            ];
        }

        return $rules;
    }
}

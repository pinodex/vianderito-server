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

        if ($model) {
            return [
                'first_name'    => 'required|regex:/^[\pL\s\-]+$/u',
                'middle_name'   => 'regex:/^[\pL\s\-]+$/u',
                'last_name'     => 'required|regex:/^[\pL\s\-]+$/u',
                'username'      => [
                    'required',
                    'unique:accounts,username,' . $model->id,
                    'regex:/^[A-Za-z0-9]+(?:[ _-][A-Za-z0-9]+)*$/u'
                ],
                'group_id'      => 'required|exists:groups,id'
            ];
        }

        return [
            'first_name'    => 'required|regex:/^[\pL\s\-]+$/u',
            'middle_name'   => 'regex:/^[\pL\s\-]+$/u',
            'last_name'     => 'required|regex:/^[\pL\s\-]+$/u',
            'username'      => [
                'required',
                'unique:accounts',
                'regex:/^[A-Za-z0-9]+(?:[ _-][A-Za-z0-9]+)*$/u'
            ],
            'group_id'      => 'required|exists:groups,id'
        ];
    }
}

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
                'first_name'    => 'required',
                'last_name'     => 'required',
                'username'      => 'required|unique:accounts,username,' . $model->id
            ];
        }

        return [
            'first_name'    => 'required',
            'last_name'     => 'required',
            'username'      => 'required|unique:accounts'
        ];
    }
}
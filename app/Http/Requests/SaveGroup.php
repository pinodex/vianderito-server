<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveDepartment extends FormRequest
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
            'name'    => 'required|unique:departments,name'
        ];

        if ($model) {
            $rules['name'] = 'required|unique:departments,name,' . $model->id;
        }

        return $rules;
    }
}

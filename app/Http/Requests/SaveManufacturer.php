<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveSupplier extends FormRequest
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
            'name'    => 'required|unique:suppliers,name',
            'code'    => 'required|numeric|unique:suppliers,code'
        ];

        if ($model) {
            $rules['name'] = 'required|unique:suppliers,name,' . $model->id;
            $rules['code'] = 'required|numeric|unique:suppliers,code,' . $model->id;
        }

        return $rules;
    }
}

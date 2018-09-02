<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveManufacturer extends FormRequest
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
            'name'    => 'required|unique:manufacturers,name',
            'code'    => 'required|numeric|unique:manufacturers,code'
        ];

        if ($model) {
            $rules['name'] = 'required|unique:manufacturers,name,' . $model->id;
            $rules['code'] = 'required|numeric|unique:manufacturers,code,' . $model->id;
        }

        return $rules;
    }
}

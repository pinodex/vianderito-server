<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveProduct extends FormRequest
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
            'name'              => 'required|unique:products,name',
            'supplier_id'       => 'required|exists:suppliers,id',
            'category_id'       => 'required|exists:categories,id',
            'upc'               => 'required|numeric|unique:products,upc',
            'floor'             => 'required|numeric|min:1',
            'ceiling'           => 'required|numeric|gte:floor'
        ];

        if ($model) {
            $rules['name'] = 'required|unique:products,name,' . $model->id;
            $rules['upc'] = 'required|numeric|unique:products,upc,' . $model->id;
        }

        return $rules;
    }
}

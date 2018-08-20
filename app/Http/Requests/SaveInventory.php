<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveInventory extends FormRequest
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
                'product_id'        => 'exists:products,id',
                'eid'               => 'required|numeric|unique:inventories,eid,' . $model->id,
                'stocks'            => 'required|numeric',
                'price'             => 'required|regex:/^\d*(\.\d{1,2})?$/',
                
                'batch_date'        => [
                    'required',
                    'date',
                    'before_or_equal:expiration_date'
                ],

                'expiration_date'   => 'required|date|after_or_equal:batch_date'
            ];
        }

        return [
            'product_id'        => 'exists:products,id',
            'eid'               => 'required|numeric|unique:inventories,eid',
            'stocks'            => 'required|numeric',
            'price'             => 'required|regex:/^\d*(\.\d{1,2})?$/',
            
            'batch_date'        => [
                'required',
                'date',
                'before_or_equal:expiration_date'
            ],

            'expiration_date'   => 'required|date|after_or_equal:batch_date'
        ];
    }
}
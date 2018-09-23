<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveCoupon extends FormRequest
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
                'code'    => 'required|unique:coupons,code,' . $model->id,
                'discount_price'    => [
                    'nullable',
                    'required_without_all:discount_percentage',
                    'regex:/^\d*(\.\d{1,2})?$/'
                ],
                'discount_percentage'    => [
                    'nullable',
                    'required_without_all:discount_price',
                    'numeric',
                    'between:0,100'
                ],
                'discount_floor_price' => [
                    'nullable',
                    'regex:/^\d*(\.\d{1,2})?$/'
                ],
                'discount_ceiling_price' => [
                    'nullable',
                    'regex:/^\d*(\.\d{1,2})?$/'
                ],
                'quantity'          => 'required|numeric|min:1',
                'validity_start'    => 'required|date|before_or_equal:validity_end',
                'validity_end'      => 'required|date|after_or_equal:validity_start'
            ];
        }

        return [
            'code'              => 'required|unique:coupons,code',
            'discount_price'    => [
                'nullable',
                'required_without_all:discount_percentage',
                'regex:/^\d*(\.\d{1,2})?$/'
            ],
            'discount_percentage'    => [
                'nullable',
                'required_without_all:discount_price',
                'numeric',
                'between:0,100'
            ],
            'discount_floor_price' => [
                'nullable',
                'regex:/^\d*(\.\d{1,2})?$/'
            ],
            'discount_ceiling_price' => [
                'nullable',
                'regex:/^\d*(\.\d{1,2})?$/'
            ],
            'quantity'          => 'required|numeric|min:1',
            'validity_start'    => 'required|date|before_or_equal:validity_end',
            'validity_end'      => 'required|date|after_or_equal:validity_start'
        ];
    }
}

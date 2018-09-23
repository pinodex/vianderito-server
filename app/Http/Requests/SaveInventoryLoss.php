<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveInventoryLoss extends FormRequest
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
        $inventory = $this->route()->parameter('inventory');
        $model = $this->route()->parameter('model');

        $maxUnits = $inventory->stocks;

        if ($model) {
            $maxUnits += $model->units;
        }

        return [
            'units' => 'required|numeric|min:1|max:' . $maxUnits,
            'remarks' => 'required'
        ];
    }
}

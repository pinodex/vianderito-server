<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;

class SaveUser extends FormRequest
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

        if (!$model) {
            $model = Auth::guard('web')->user();
        }

        if ($model) {
            return [
                'name'          => 'required',
                'username'      => 'nullable|alpha_dash|unique:users,username,' . $model->id,
                'email_address' => 'required|email|unique:users,email_address,' . $model->id,
                'phone_number'  => 'nullable|numeric|digits_between:11,12'
            ];            
        }

        return [
            'name'          => 'required',
            'username'      => 'nullable|alpha_dash|unique:users',
            'email_address' => 'required|email|unique:users',
            'phone_number'  => 'nullable|numeric|digits_between:11,12'
        ];
    }
}

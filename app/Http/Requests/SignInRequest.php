<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SignInRequest extends FormRequest
{
   
    public function rules()
    {
        return [
            'email' => 'required|email',
            
            'password' => 'required|min:8',
        ];
    }
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
    
        throw new \Illuminate\Validation\ValidationException($validator,response()->json($validator->errors(), 422));
    }

}
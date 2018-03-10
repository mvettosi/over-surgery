<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterFormRequest extends FormRequest {
    public function authorize() {
        return true;
    }
    public function rules() {
        return [
            'name' => 'required|string',
            'surname' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required|string|max:600',
            'postcode' => 'required|string|max:255',
            'document_type' => 'in:identity_card,passport',
            'document_id' => 'required|string|unique:users|max:255',
            'birthdate' => 'required',
        ];
    }
}

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
            'password' => 'required|string|min:6|max:10',
        ];
    }

    protected function temp(array $data) {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone' => 'required|numeric|phone',
            'address' => 'required|string|max:600',
            'postcode' => 'required|string|max:255',
            'document_type' => 'in:identity_card,passport',
            'document_id' => 'required|string|max:255',
            'birthdate' => 'required|date',
        ]);
    }
}

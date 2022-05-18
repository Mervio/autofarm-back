<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
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
        return [
            'name'          => 'required|string|max:255',
            'email'         => 'required|string|email|max:255|unique:users',
            'gender'        => 'required|string|max:1|in:M,F',
            'date_of_birth' => 'required|date|date_format:Y-m-d|before:today',
            'password'      => ['required', 'confirmed', 'string', Password::min(8)->letters()->mixedCase()->numbers()->symbols()]
        ];
    }
}

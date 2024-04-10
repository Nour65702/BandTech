<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'between:4,10'
            ],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => [
                'required',
                'email',
                'unique:users,email'
            ],
            'password' => [
                'required',
                'string',
                'confirmed', // Ensure the password field has a matching field named password_confirmation
                'regex:/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*\W)(?!.* ).{8,30}$/' // Add your regex pattern here
            ],
            'type' => ['required', 'string', 'in:normal,gold,silver'],
            'avatar' => [
                'required',
                'image',
                'dimensions:max_width:3840,max_height:2160',
                'mimes:png,jpg,gif',
                'max:2765'
            ]
        ];
    }
}

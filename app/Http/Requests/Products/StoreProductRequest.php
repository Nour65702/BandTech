<?php

namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'user_id' => ['required', 'exists:users,id'],
            'name' => ['required', 'string', 'between:4,10'],
            'description' => ['nullable'],
            'price' => ['required', 'numeric'],
            'slug' => ['required', 'unique:products'],
            'image' => [
                'image',
                'dimensions:max_width=3840,max_height=2160',
                'mimes:png,jpg,gif',
                'max:2765'
            ]
        ];
    }
}

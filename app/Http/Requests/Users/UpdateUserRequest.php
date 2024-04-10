<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    protected int $user_id;

    public function __construct() {
        $this->user_id = request()->user()->id;
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user_id == request()->segment(4);
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
                'string',
                'between:4,10'
            ],
            'email' => [
                'email',
                "unique:users,email,{$this->user_id}"
            ],
            'avatar' => [
                'image',
                'dimensions:max_width:3840,max_height:2160',
                'mimes:png,jpg,gif',
                'max:2765'
            ]
        ];
    }
}

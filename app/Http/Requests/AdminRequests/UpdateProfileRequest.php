<?php

namespace App\Http\Requests\AdminRequests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
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
            'name' => ['required', 'max:255'],
            'email' => ['required',  Rule::unique('users', 'email')->ignore(auth()->id())],
            'user_name' => ['required', Rule::unique('users', 'userName')->ignore(auth()->id())],
            'image' => ['nullable', 'string'],
            'password' => ['nullable', 'min:8'],
        ];
    }
}

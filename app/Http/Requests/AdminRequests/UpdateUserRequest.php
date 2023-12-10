<?php

namespace App\Http\Requests\AdminRequests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
        $userId = $this->user_id ?? null;

        return [
            'name' => ['required', 'max:255'],
            'email' => ['required',  Rule::unique('users', 'email')->ignore($userId)],
            'user_name' => ['required', Rule::unique('users', 'userName')->ignore($userId)],
            'image' => ['nullable', 'string'],
            'password' => ['required', 'min:8', 'confirmed'],
            'type' => ['required', Rule::in([1, 2])],
            'role' => ['required_if:type,1','exists:roles,name'],
        ];
    }
}

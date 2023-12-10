<?php

namespace App\Http\Requests\AdminRequests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
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
            'ar_name' => ['nullable', 'max:255'],
            'description' => ['nullable', 'string'],
            'ar_description' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
            'ar_address' => ['nullable', 'string'],
            'email' => ['nullable', 'string','unique:companies'],
            'phone_number' => ['nullable', 'string','unique:companies'],
            'long' => ['nullable'],
            'lat' => ['nullable'],
            'social_media' => ['nullable'],
            'active' => ['required', 'boolean']
        ];
    }
}

<?php

namespace App\Http\Requests\AdminRequests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCompanyRequest extends FormRequest
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
            'description' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
            'email' => ['nullable', 'string', Rule::unique('companies')->ignore($this->company_id)],
            'phone_number' => ['nullable', 'string',Rule::unique('companies')->ignore($this->company_id)],
            'long' => ['nullable'],
            'lat' => ['nullable'],
            'social_media' => ['nullable'],
            'active' => ['required', 'boolean']
        ];
    }
}

<?php

namespace App\Http\Requests\AdminRequests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmployeeRequest extends FormRequest
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
            'address' => ['nullable', 'string'],
            'email' => ['nullable', 'string', Rule::unique('employees')->ignore($this->employee_id)],
            'number' => ['nullable', 'integer',Rule::unique('employees')->ignore($this->employee_id)],
            'company_id' => ['required', 'exists:companies,id'],
        ];
    }
}

<?php

namespace App\Http\Requests\AdminRequests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
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
    /*'name', 'email', 'address' , 'number','company_id' */
    public function rules()
    {
        return [
            'name' => ['required', 'max:255'],
            'ar_name' => ['required', 'max:255'],
            'address' => ['nullable', 'string'],
            'ar_address' => ['nullable', 'string'],
            'email' => ['required', 'string','unique:employee-companies'],
            'number' => ['required', 'string','unique:employee-companies'],
            'company_id' => ['required', 'exists:companies,id'],
        ];
    }
}

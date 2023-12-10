<?php

namespace App\Http\Requests\AdminRequests;

use Illuminate\Foundation\Http\FormRequest;

class AdRequest extends FormRequest
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
            'title' => ['required', 'max:255'],
            'ar_title' => ['nullable', 'max:255'],
            'description' => ['required', 'string'],
            'ar_description' => ['nullable', 'string'],
            'url' => ['nullable', 'url'],
            'image' => ['nullable','string'],
            'video' =>  ['nullable','string'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date'],
            'active' => ['required', 'boolean'],
            'company_id' => ['required', 'exists:companies,id'],

        ];
    }
}

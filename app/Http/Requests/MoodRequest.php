<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MoodRequest extends FormRequest
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
            'name' => ['required', 'string'],
            'ar_name' => ['required', 'max:255'],
            'image' => ['nullable', 'image', 'max:1024'],
        ];
    }
}

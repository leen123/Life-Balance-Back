<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BudgesRequest extends FormRequest
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
            'ar_name' => ['required', 'max:255'],
            'is_from_section' => ['required', 'boolean'],
            'points' => ['required', 'integer'],
            'image' => ['nullable', 'image', 'max:1024'],
            'count_of_badges' => ['required', 'integer'],
            'is_grand_master' => ['required', 'boolean'],
            'section_id' => ['required', 'exists:sections,id'],
            'badges_id' => ['required', 'exists:budges,id'],


        ];
    }
}

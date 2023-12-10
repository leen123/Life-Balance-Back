<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HabitRequest extends FormRequest
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
    /**name','active_date','repetition_type','repetition_number','doHabits','date_type','op_points' */
    public function rules()
    {
        return [
            'name' => ['required', 'max:255'],
            'ar_name' => ['required', 'max:255'],
            'active_date' => ['required', 'date'],
            'repetition_type' => ['required', 'integer'],
            'repetition_number' => ['required', 'integer'],
            'op_points' => ['required', 'integer'],


        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JournalRequest extends FormRequest
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
    /**iconType','nameType','moodImage','title','subtitle','description','date','dayDate',
'monthDate','yearDate','hoursDate','minutesDate','secondsDate' */
    public function rules()
    {
        return [
            'iconType' => ['required', 'string'],
            'nameType' => ['required', 'string'],
            'moodImage' => ['nullable', 'string'],
            'title' => ['required', 'string'],
            'ar_title' => ['required', 'string'],
            'subtitle' => ['nullable', 'string'],
            'ar_subtitle' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'ar_description' => ['nullable', 'string'],
            'date' => ['required', 'date'],
            'dayDate' => ['nullable', 'numeric'],
            'monthDate' => ['nullable', 'numeric'],
            'yearDate' => ['nullable', 'numeric'],
            'hoursDate' => ['nullable', 'numeric'],
            'minutesDate' => ['nullable', 'numeric'],
            'secondsDate' => ['nullable', 'numeric'],


        ];
    }
}

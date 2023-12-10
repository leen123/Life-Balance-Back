<?php

namespace App\Http\Requests\AdminRequests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCouponRequest extends FormRequest
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
            'code' => ['required', Rule::unique('coupons')->ignore($this->coupon_id)],
            'type' => ['required', Rule::in(['fixed', 'percentage'])],
            'value' => ['required', 'numeric'],
            'max_uses' => ['nullable', 'integer'],
            'points' => ['required', 'integer'],
            'active' => ['required', 'boolean'],
            'starts_at' => ['required', 'date'],
            'ends_at'=> ['nullable', 'date'],
            'company_id' => ['required', 'exists:companies,id'],
        ];
    }
}

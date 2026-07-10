<?php

namespace App\Filament\Admin\Resources\VoucherResource\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateVoucherRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
			'code' => 'required',
			'name' => 'required',
			'type' => 'required',
			'value' => 'required',
			'max_discount' => 'required',
			'min_order_amount' => 'required',
			'usage_limit' => 'required',
			'used_count' => 'required',
			'per_customer_limit' => 'required',
			'starts_at' => 'required',
			'ends_at' => 'required',
			'is_active' => 'required'
		];
    }
}

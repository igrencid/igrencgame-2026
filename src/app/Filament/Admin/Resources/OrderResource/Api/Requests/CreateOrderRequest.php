<?php

namespace App\Filament\Admin\Resources\OrderResource\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
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
			'customer_id' => 'required',
			'invoice_number' => 'required',
			'game_id' => 'required',
			'game_product_id' => 'required',
			'payment_gateway_id' => 'required',
			'voucher_id' => 'required',
			'voucher_code' => 'required',
			'discount_amount' => 'required',
			'game_name' => 'required',
			'product_name' => 'required',
			'customer_inputs' => 'required',
			'customer_name' => 'required',
			'customer_email' => 'required',
			'customer_phone' => 'required',
			'product_price' => 'required',
			'admin_fee' => 'required',
			'total_amount' => 'required',
			'status' => 'required',
			'paid_at' => 'required',
			'invoice_email_sent_at' => 'required',
			'expired_at' => 'required',
			'api_provider_id' => 'required'
		];
    }
}

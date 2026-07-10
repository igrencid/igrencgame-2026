<?php

namespace App\Filament\Admin\Resources\CustomerResource\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCustomerRequest extends FormRequest
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
			'name' => 'required',
			'email' => 'required',
			'email_verified_at' => 'required',
			'google_id' => 'required',
			'phone' => 'required',
			'avatar_url' => 'required',
			'password' => 'required',
			'accepts_marketing' => 'required',
			'remember_token' => 'required'
		];
    }
}

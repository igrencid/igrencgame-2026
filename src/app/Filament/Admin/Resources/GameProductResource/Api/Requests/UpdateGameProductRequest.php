<?php

namespace App\Filament\Admin\Resources\GameProductResource\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGameProductRequest extends FormRequest
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
			'game_id' => 'required',
			'name' => 'required',
			'code' => 'required',
			'base_price' => 'required',
			'selling_price' => 'required',
			'description' => 'required|string',
			'is_active' => 'required',
			'sort_order' => 'required'
		];
    }
}

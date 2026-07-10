<?php

namespace App\Filament\Admin\Resources\GameResource\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateGameRequest extends FormRequest
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
			'slug' => 'required',
			'category' => 'required',
			'badge' => 'required',
			'image_path' => 'required',
			'banner_path' => 'required',
			'description' => 'required|string',
			'is_active' => 'required',
			'sort_order' => 'required'
		];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class CategoryRequest extends FormRequest
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
			'name' => 'required|regex:/^[\w]+$/',
			'icon' => [
				'required',
				function ($attribute, $value, $fail) {
					$icon = str_replace('bi bi-', '', $value);
					if (!DB::table('icons')->where('name', $icon)->exists()) {
						$fail($attribute . ' is invalid.');
					}
				},
			]
		];
	}

	// /^bi bi-/
}
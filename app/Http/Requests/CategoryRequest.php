<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
				'regex:/^(fa-solid|fa-regular|fa-brands)\s+[a-z0-9-]+$/i'
			]
		];
	}

	public function messages(): array
	{
		return [
			'name.required' => 'Harap masukkan nama kategori!',
			'icon.required' => 'Harap masukkan icon!',
			'name.regex' => 'Nama kategori hanya boleh terdiri dari satu kata tanpa spasi & tanpa simbol apapun!',
			'icon.regex' => 'Harap input icon berdasarkan class pada font awesome!'
		];
	}
}

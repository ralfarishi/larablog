<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
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
			'title' => 'required',
			'content' => 'required',
			'image' => 'mimes:png,jpg,jpeg,webp|max:500',
			'tags' => 'required',
			'active' => 'required',
			'allowed_comment' => 'required',
			'category_id' => 'required|exists:categories,id'
		];
	}
}

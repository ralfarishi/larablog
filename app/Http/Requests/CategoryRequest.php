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
		$iconClasses = DB::table('icons')->pluck('name')->toArray();

		$pattern = '/^bi bi-(' . implode('|', $iconClasses) . ')$/';

		return [
			'name' => 'required|regex:/^[\w]+$/',
			'icon' => [
				'required',
				'regex:' . $pattern
			]
		];
	}

	// /^bi bi-/
}

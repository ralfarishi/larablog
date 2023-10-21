<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
			'name' => 'required|string|unique:users,name',
			'email' => 'required|email|unique:users,email',
			'password' => 'required|min:5'
		];
	}

	public function messages(): array
	{
		return [
			'name.required' => 'Harap mengisi nama!',
			'name.unique' => 'Nama sudah digunakan. Coba nama lain.',
			'email.required' => 'Harap mengisi email!',
			'email.unique' => 'Email sudah digunakan. Coba email lain.',
			'password.required' => 'Harap mengisi password!',
			'password.min' => 'Password minimal 5 karakter!',
		];
	}
}

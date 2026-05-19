<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
  /** Public registration — always allowed (guest route). */
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    return [
      'name' => ['required', 'string', 'max:255', 'unique:users,name'],
      'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
      'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
    ];
  }

  public function messages(): array
  {
    return [
      'name.unique' => 'This username is already taken.',
      'email.unique' => 'An account with this email already exists.',
      'password.confirmed' => 'Passwords do not match.',
    ];
  }
}

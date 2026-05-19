<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
{
  public function authorize(): bool
  {
    return $this->user()?->role === 'admin';
  }

  public function rules(): array
  {
    return [
      'name' => ['required', 'string', 'max:255'],
      'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
      'role' => ['required', 'in:admin,writter,reader'],
      'display_picture' => ['nullable', 'sometimes', 'image', 'mimes:png,jpg,jpeg,webp', 'max:500'],
      'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
    ];
  }
}

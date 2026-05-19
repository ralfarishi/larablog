<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
  public function authorize(): bool
  {
    return $this->user()?->role === 'admin' || $this->user()?->slug === $this->route('user');
  }

  public function rules(): array
  {
    return [
      'name' => ['required', 'string', 'max:255'],
      'email' => ['required', 'email', 'max:255'],
      'role' => ['sometimes', 'in:admin,writter,reader'],
      'display_picture' => ['nullable', 'sometimes', 'image', 'mimes:png,jpg,jpeg,webp', 'max:500'],
      'password' => ['nullable', 'sometimes', Password::min(8)],
      'default-image' => ['nullable', 'boolean'],
    ];
  }
}

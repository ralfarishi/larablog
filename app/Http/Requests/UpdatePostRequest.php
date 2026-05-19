<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
{
  public function authorize(): bool
  {
    return $this->user()?->role !== null;
  }

  public function rules(): array
  {
    return [
      'title' => ['required', 'string', 'max:255'],
      'content' => ['required', 'string'],
      'image' => ['nullable', 'sometimes', 'image', 'mimes:png,jpg,jpeg,webp', 'max:500'],
      'tags' => ['required', 'string'],
      'status' => ['required', 'in:draft,published,hidden'],
      'allowed_comment' => ['required', 'boolean'],
      'category_id' => ['required', 'exists:categories,id'],
    ];
  }
}

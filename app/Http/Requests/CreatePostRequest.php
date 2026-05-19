<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePostRequest extends FormRequest
{
  public function authorize(): bool
  {
    return $this->user()?->role !== null;
  }

  public function rules(): array
  {
    return [
      'title' => ['required', 'string', 'max:255', 'unique:posts,title'],
      'content' => ['required', 'string'],
      'image' => ['nullable', 'sometimes', 'image', 'mimes:png,jpg,jpeg,webp', 'max:1024'],
      'tags' => ['required', 'string'],
      'allowed_comment' => ['required', 'boolean'],
      'status' => ['required', 'in:draft,published'],
      'category_id' => ['required', 'exists:categories,id'],
    ];
  }

  public function messages(): array
  {
    return [
      'image.required' => 'An image is required.',
      'tags.required' => 'Add at least one tag.',
    ];
  }
}

<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Services\TagService;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Str;

class ArticleForm extends Component
{
  use WithFileUploads;

  public ?Post $post = null;
  public string $title = '';
  public string $content = '';
  public $image;
  public string $tags = '';
  public $allowed_comment = true;
  public string $status = 'draft';
  public ?int $category_id = null;

  public array $availableTags = [];

  protected function rules()
  {
    return [
      'title' => [
        'required',
        'string',
        'max:255',
        'unique:posts,title,' . ($this->post?->id ?? 'NULL'),
      ],
      'content' => ['required', 'string'],
      'image' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:1024'],
      'tags' => ['required', 'string'],
      'allowed_comment' => ['required', 'boolean'],
      'status' => ['required', 'in:draft,published'],
      'category_id' => ['required', 'exists:categories,id'],
    ];
  }

  public function mount(?Post $post = null)
  {
    $this->post = $post;
    $this->availableTags = Tag::pluck('name')->all();

    if ($this->post) {
      $this->title = $this->post->title;
      $this->content = $this->post->content;
      $this->tags = $this->post->tags instanceof \Illuminate\Support\Collection
        ? $this->post->tags->pluck('name')->implode(',')
        : (string) $this->post->tags;
      $this->allowed_comment = (bool) $this->post->allowed_comment;
      $this->status = $this->post->status;
      $this->category_id = $this->post->category_id;
    } else {
      $this->category_id = Category::first()?->id;
    }
  }

  public function save(TagService $tagService)
  {
    // Pre-process boolean to avoid validation failure if it's a string "1" or "0"
    $this->allowed_comment = filter_var($this->allowed_comment, FILTER_VALIDATE_BOOLEAN);

    $this->validate();

    try {
      $contentLength = strlen($this->content);
      Log::info('Attempting to save article', [
        'title' => $this->title,
        'content_length' => $contentLength,
        'category_id' => $this->category_id,
      ]);

      $data = [
        'title' => $this->title,
        'slug' => Str::slug($this->title),
        'content' => $this->content,
        'allowed_comment' => (bool) $this->allowed_comment,
        'status' => $this->status,
        'tags' => $this->tags,
        'category_id' => $this->category_id,
        'user_id' => auth()->id(),
      ];

      if ($this->post) {
        $this->post->update($data);
        $post = $this->post;
        $message = 'Article has been updated.';
      } else {
        $post = Post::create($data);
        $message = 'Article has been published.';
      }

      if ($this->image) {
        $post->clearMediaCollection('cover');
        $post->addMedia($this->image->getRealPath())->toMediaCollection('cover');
      }

      $tagService->sync($post, $this->tags);

      \Illuminate\Support\Facades\Cache::forget('sidebar_data');

      session()->flash('success', $message);
      return to_route('article.index');
    } catch (\Exception $e) {
      Log::error('Article Save Failure', [
        'error' => $e->getMessage(),
        'user_id' => auth()->id(),
        'data' => $this->all(),
      ]);

      Context::add('last_error', $e->getMessage());

      session()->flash('danger', 'Failed to save article: ' . $e->getMessage());
    }
  }

  public function render()
  {
    return view('livewire.admin.article-form', [
      'categories' => Category::all(),
    ]);
  }
}

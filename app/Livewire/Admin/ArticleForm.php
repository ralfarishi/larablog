<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Services\TagService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

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

  public string $editorMode = 'write';

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
    // Cache the tags list to avoid querying every time the form loads
    $this->availableTags = Cache::remember(
      'article_form_tags',
      3600,
      fn() => Tag::orderBy('name')->pluck('name')->all(),
    );

    if ($this->post) {
      $this->title = $this->post->title;
      $this->content = $this->post->content;
      // Always load from the pivot relation — avoids the legacy string column ambiguity
      $this->tags = $this->post->tags()->pluck('name')->implode(',');
      $this->allowed_comment = (bool) $this->post->allowed_comment;
      $this->status = $this->post->status;
      $this->category_id = $this->post->category_id;
    } else {
      $this->category_id = Category::select('id')->first()?->id;
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
        'category_id' => $this->category_id,
        'user_id' => auth()->id(),
        // 'tags' is intentionally excluded — managed exclusively by TagService below
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

      Cache::forget('sidebar_data');
      Cache::forget('article_form_tags'); // Tags list may have changed

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
    // Cache categories list; only re-queried every 30 min or when cleared
    return view('livewire.admin.article-form', [
      'categories' => Cache::remember(
        'all_categories',
        1800,
        fn() => Category::select('id', 'name')->orderBy('name')->get(),
      ),
    ]);
  }
}

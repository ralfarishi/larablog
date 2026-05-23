<?php

declare(strict_types=1);

namespace App\Livewire\Home;

use App\Models\Post;
use Livewire\Attributes\Url;
use Livewire\Component;

class SearchComponent extends Component
{
  #[Url(as: 'q')]
  public string $query = '';

  public function mount()
  {
    // Auto-hydrated from URL query parameter 'q' via the #[Url] attribute
  }

  public function render()
  {
    $results = [];

    if (strlen($this->query) >= 2) {
      $results = Post::search($this->query)
        ->query(fn($query) => $query->with(['category', 'user', 'media']))
        ->where('status', 'published')
        ->take(10)
        ->get();
    }

    return view('livewire.home.search-component', [
      'results' => $results,
    ]);
  }
}

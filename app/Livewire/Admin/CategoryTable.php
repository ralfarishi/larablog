<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\Category;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryTable extends Component
{
  use WithPagination;

  public string $search = '';
  public string $new_category_name = '';
  public ?int $editingCategoryId = null;
  public string $editingCategoryName = '';

  public function updatingSearch()
  {
    $this->resetPage();
  }

  public function createCategory()
  {
    $this->validate([
      'new_category_name' => 'required|string|max:255|unique:categories,name',
    ]);

    \App\Models\Category::create([
      'name' => $this->new_category_name,
      'slug' => \Illuminate\Support\Str::slug($this->new_category_name),
    ]);

    $this->new_category_name = '';
    session()->flash('success', 'Category successfully added.');
    $this->dispatch('category-created');
  }

  public function startEditing(int $id, string $name)
  {
    $this->editingCategoryId = $id;
    $this->editingCategoryName = $name;
  }

  public function cancelEditing()
  {
    $this->editingCategoryId = null;
    $this->editingCategoryName = '';
  }

  public function updateCategory()
  {
    $this->validate([
      'editingCategoryName' =>
        'required|string|max:255|unique:categories,name,' . $this->editingCategoryId,
    ]);

    $category = Category::findOrFail($this->editingCategoryId);
    $category->update([
      'name' => $this->editingCategoryName,
      'slug' => \Illuminate\Support\Str::slug($this->editingCategoryName),
    ]);

    $this->cancelEditing();
    session()->flash('success', 'Category updated successfully.');
  }

  #[On('deleteConfirmed')]
  public function delete(int $id)
  {
    $category = Category::findOrFail($id);

    if ($category->posts()->count() > 0) {
      session()->flash('warning', 'Cannot delete category with associated articles.');
      return;
    }

    $category->delete();
    session()->flash('danger', 'Category has been deleted.');
  }

  public function render()
  {
    $categories = Category::query()
      ->withCount('posts')
      ->when($this->search, function ($query) {
        $query
          ->where('name', 'like', '%' . $this->search . '%')
          ->orWhere('slug', 'like', '%' . $this->search . '%');
      })
      ->latest()
      ->paginate(10);

    return view('livewire.admin.category-table', [
      'categories' => $categories,
    ]);
  }
}

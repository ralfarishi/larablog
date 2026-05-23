<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\Comment;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class CommentTable extends Component
{
  use WithPagination;

  public string $search = '';

  public function updatingSearch()
  {
    $this->resetPage();
  }

  #[On('deleteConfirmed')]
  public function delete(int $id)
  {
    $comment = Comment::findOrFail($id);
    $comment->delete();
    session()->flash('danger', 'Comment has been deleted.');
  }

  public function toggleStatus(int $id)
  {
    $comment = Comment::findOrFail($id);
    $comment->active = !$comment->active;
    $comment->save();

    $status = $comment->active ? 'visible' : 'hidden';
    $this->dispatch('toast', message: "Comment is now {$status}.", type: 'success');
  }

  public function render()
  {
    $comments = Comment::query()
      ->with(['user.media', 'post'])
      ->when($this->search, function ($query) {
        // Both conditions must be inside a single closure to prevent the OR
        // from leaking outside the search guard and returning all comments.
        $query->where(function ($q) {
          $q->whereHas('user', function ($u) {
            $u->where('name', 'like', '%' . $this->search . '%');
          })->orWhere('content', 'like', '%' . $this->search . '%');
        });
      })
      ->latest()
      ->paginate(10);

    return view('livewire.admin.comment-table', [
      'comments' => $comments,
    ]);
  }
}

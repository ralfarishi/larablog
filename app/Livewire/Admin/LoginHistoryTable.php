<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\LoginHistory;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class LoginHistoryTable extends Component
{
  use WithPagination;

  public string $search = '';

  public function updatingSearch(): void
  {
    $this->resetPage();
  }

  #[On('clearHistoryConfirmed')]
  public function clearHistory(): void
  {
    $dataCount = LoginHistory::count();

    if ($dataCount <= 10) {
      LoginHistory::truncate();
      $this->dispatch(
        'toast',
        message: 'All login history data has been deleted!',
        type: 'success',
      );
    } else {
      LoginHistory::orderBy('id', 'asc')
        ->limit($dataCount - 10)
        ->delete();
      $this->dispatch(
        'toast',
        message: 'The oldest login history entries have been deleted, keeping the last 10.',
        type: 'success',
      );
    }

    $this->dispatch('refreshStats');
  }

  public function render()
  {
    $history = LoginHistory::query()
      ->when($this->search, function ($q) {
        $q->where('email', 'like', '%' . $this->search . '%')
          ->orWhere('ip_address', 'like', '%' . $this->search . '%')
          ->orWhere('user_agent', 'like', '%' . $this->search . '%');
      })
      ->latest()
      ->paginate(10);

    return view('livewire.admin.login-history-table', [
      'history' => $history,
    ]);
  }
}

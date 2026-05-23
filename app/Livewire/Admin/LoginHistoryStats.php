<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\LoginHistory;
use Livewire\Attributes\On;
use Livewire\Component;

class LoginHistoryStats extends Component
{
  public int $totalData = 0;

  public int $loginSuccess = 0;

  public int $loginFailed = 0;

  public function mount(): void
  {
    $this->loadStats();
  }

  #[On('refreshStats')]
  public function loadStats(): void
  {
    $stats = LoginHistory::selectRaw(
      'COUNT(*) as total,
             SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as success_count,
             SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as failed_count',
    )->first();

    $this->totalData = (int) $stats->total;
    $this->loginSuccess = (int) $stats->success_count;
    $this->loginFailed = (int) $stats->failed_count;
  }

  public function render()
  {
    return view('livewire.admin.login-history-stats');
  }
}

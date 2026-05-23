<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use Livewire\Attributes\On;
use Livewire\Component;

class DeleteConfirmationModal extends Component
{
  public bool $show = false;

  public string $title = 'Confirm Deletion';

  public string $message = 'Are you sure you want to delete this record? This action is permanent and cannot be undone.';

  public ?string $action = null;

  public $targetId = null;

  public string $componentId = '';

  #[On('open-confirm-modal')]
  public function open(
    string $componentId,
    string $action,
    $id,
    string $title = 'Confirm Deletion',
    string $message = '',
  ): void {
    $this->componentId = $componentId;
    $this->action = $action;
    $this->targetId = $id;
    $this->title = $title;
    if ($message) {
      $this->message = $message;
    }
    $this->show = true;
  }

  public function confirm(): void
  {
    if ($this->componentId && $this->action) {
      $this->dispatch($this->action, $this->targetId)->to($this->componentId);
    }
    $this->close();
  }

  public function close(): void
  {
    $this->show = false;
    $this->reset(['title', 'message', 'action', 'targetId', 'componentId']);
  }

  public function render()
  {
    return view('livewire.admin.delete-confirmation-modal');
  }
}

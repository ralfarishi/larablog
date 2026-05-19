<div x-data="{ open: false }" class="relative">
  <!-- Trigger Button -->
  <button
    @click="open = !open"
    @click.outside="open = false"
    class="bg-card ring-border text-foreground hover:text-primary hover:ring-primary/50 relative flex h-10 w-10 items-center justify-center rounded-full ring-1 transition-all focus:outline-none"
    :class="open ? 'ring-primary text-primary' : ''"
  >
    <i class="ph ph-bell text-xl"></i>
    @if ($this->unreadCount > 0)
      <span
        class="bg-primary text-primary-foreground absolute top-0 right-0 flex h-4 w-4 translate-x-1/4 -translate-y-1/4 items-center justify-center rounded-full text-[10px] font-black shadow-sm"
      >
        {{ $this->unreadCount }}
      </span>
    @endif
  </button>

  <!-- Dropdown -->
  <div
    x-show="open"
    x-cloak
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 scale-95 translate-y-2"
    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100 scale-100 translate-y-0"
    x-transition:leave-end="opacity-0 scale-95 translate-y-2"
    class="bg-card ring-border absolute right-0 z-50 mt-4 w-88 overflow-hidden rounded-[2.5rem] shadow-2xl ring-1 focus:outline-none"
  >
    <!-- Header -->
    <div class="border-border bg-muted/30 flex items-center justify-between border-b px-6 py-4">
      <h6 class="text-foreground text-xs font-black tracking-widest uppercase">Notifications</h6>
      @if ($this->unreadCount > 0)
        <button
          wire:click="markAllRead"
          class="text-primary text-[10px] font-bold tracking-wider uppercase hover:underline"
        >
          Mark all read
        </button>
      @endif
    </div>

    <!-- Notification List -->
    <div class="max-h-[320px] overflow-y-auto">
      @forelse ($this->notifications as $notif)
        <div
          wire:key="notif-{{ $notif->id }}"
          @class ([
            'group border-border/50 hover:bg-muted/30 relative border-b transition-all',
            'opacity-50' => $notif->read_at
          ])
        >
          <!-- Delete Button -->
          <button
            wire:click.prevent="deleteNotif('{{ $notif->id }}')"
            class="text-muted-foreground absolute top-1/2 left-3 z-10 -translate-y-1/2 p-2 opacity-0 transition-all group-hover:opacity-100 hover:text-red-500"
            title="Delete"
          >
            <i class="ph ph-trash text-lg"></i>
          </button>

          <!-- Content -->
          <a
            href="#"
            wire:click.prevent="markAsRead('{{ $notif->id }}')"
            class="block p-4 pr-6 pl-12"
          >
            <div class="flex items-start gap-4">
              <div
                class="bg-primary/10 text-primary flex h-10 w-10 shrink-0 items-center justify-center rounded-full"
              >
                <i
                  @class ([
                    'ph text-xl',
                    'ph-chat-circle-text' => $notif->type === 'App\\Notifications\\CommentReceived',
                    'ph-user-plus' => $notif->type === 'App\\Notifications\\NewUserRegistered'
                  ])
                ></i>
              </div>
              <div class="min-w-0 flex-1">
                <p class="text-muted-foreground mb-1 text-[10px] font-bold tracking-wider uppercase">
                  {{ $notif->created_at->diffForHumans() }}
                </p>
                <p
                  @class ([
                    'text-foreground line-clamp-2 text-sm leading-snug font-bold',
                    'font-normal' => $notif->read_at
                  ])
                >
                  @php
                    $data = $notif->data;
                    $name = $data['commenter_name'] ?? ($data['user_name'] ?? 'Someone');
                    if (isset($data['post_title'])) {
                      $message = "$name commented on \"{$data['post_title']}\"";
                    } elseif (
                      ($data['message'] ?? '') === 'just created an account' ||
                      str_contains($notif->type, 'UserRegistered')
                    ) {
                      $message = "$name just joined";
                    } else {
                      $message = "$name " . ($data['message'] ?? 'sent you a notification');
                    }
                  @endphp
                  {{ $message }}
                </p>
              </div>
              @if (!$notif->read_at)
                <div class="bg-primary mt-2 h-2 w-2 shrink-0 rounded-full"></div>
              @endif
            </div>
          </a>
        </div>
      @empty
        <div class="p-8 text-center">
          <div
            class="bg-muted mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full"
          >
            <i class="ph ph-bell-slash text-muted-foreground text-2xl"></i>
          </div>
          <p class="text-muted-foreground text-sm font-semibold">No notifications yet</p>
        </div>
      @endforelse
    </div>

    <!-- Footer -->
    @if ($this->notifications->count() > 0)
      <div class="bg-muted/30 border-border flex justify-center gap-2 border-t p-3">
        <button
          wire:click="markAllRead"
          class="text-foreground bg-background border-border hover:border-primary hover:text-primary flex-1 rounded-xl border px-4 py-2.5 text-xs font-black transition-all"
        >
          <i class="ph ph-check-circle mr-1"></i> Read All
        </button>
        <button
          wire:click="clearAll"
          class="flex-1 rounded-xl bg-red-50 px-4 py-2.5 text-xs font-black text-red-500 transition-all hover:bg-red-500 hover:text-white"
        >
          <i class="ph ph-trash mr-1"></i> Clear
        </button>
      </div>
    @endif
  </div>
</div>

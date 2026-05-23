<div
  class="bg-card border-border overflow-hidden rounded-3xl border shadow-sm transition-all duration-300"
>
  <!-- Table Header & Search -->
  <div
    class="border-border flex flex-col items-center justify-between gap-4 border-b p-6 md:flex-row"
  >
    <h3 class="text-muted-foreground text-sm font-black tracking-widest uppercase">
      Comments <span class="text-primary">Management</span>
    </h3>

    <div class="relative w-full md:w-80">
      <span class="text-muted-foreground absolute inset-y-0 left-5 flex items-center">
        <i class="ph ph-magnifying-glass text-lg"></i>
      </span>
      <input
        type="text"
        wire:model.live.debounce.300ms="search"
        class="border-border bg-muted/30 focus:border-primary focus:bg-background focus:ring-primary/10 w-full rounded-2xl py-2.5 pr-5 pl-12 text-sm font-medium transition-all focus:ring-4"
        placeholder="Search comments..."
      />
    </div>
  </div>

  <!-- Table Content -->
  <div class="overflow-x-auto">
    <table class="w-full border-collapse text-left">
      <thead>
        <tr class="bg-muted/30">
          <th
            class="text-muted-foreground hidden px-6 py-4 text-xs font-black tracking-widest uppercase sm:table-cell"
          >
            #
          </th>
          <th class="text-muted-foreground px-6 py-4 text-xs font-black tracking-widest uppercase">
            User
          </th>
          <th class="text-muted-foreground px-6 py-4 text-xs font-black tracking-widest uppercase">
            Comment
          </th>
          <th
            class="text-muted-foreground hidden px-6 py-4 text-xs font-black tracking-widest uppercase md:table-cell"
          >
            Post
          </th>
          <th
            class="text-muted-foreground hidden px-6 py-4 text-center text-xs font-black tracking-widest uppercase md:table-cell"
          >
            Date
          </th>
          <th
            class="text-muted-foreground px-6 py-4 text-center text-xs font-black tracking-widest uppercase"
          >
            Status
          </th>
          <th
            class="text-muted-foreground px-6 py-4 text-right text-xs font-black tracking-widest uppercase"
          >
            Actions
          </th>
        </tr>
      </thead>
      <tbody class="divide-border divide-y">
        @forelse ($comments as $index => $comment)
          <tr
            wire:key="comment-{{ $comment->id }}"
            class="group hover:bg-muted/20 transition-colors"
          >
            <td class="text-muted-foreground hidden px-6 py-4 text-sm font-bold sm:table-cell">
              {{ $comments->firstItem() + $index }}
            </td>
            <td class="px-6 py-4">
              <div class="flex items-center gap-3">
                <div class="border-background size-8 overflow-hidden rounded-full border">
                  <img
                    src="{{ $comment->user->profile_picture_url }}"
                    alt="{{ $comment->user->name }}"
                    loading="lazy"
                    class="size-full object-cover"
                  />
                </div>
                <span class="text-foreground text-sm font-medium">{{ $comment->user->name }}</span>
              </div>
            </td>
            <td class="px-6 py-4">
              <p class="text-muted-foreground line-clamp-2 max-w-xs text-sm">
                {{ $comment->content }}
              </p>
            </td>
            <td class="hidden px-6 py-4 md:table-cell">
              @if ($comment->post)
                <a
                  href="{{ route('post', $comment->post->slug) }}"
                  target="_blank"
                  class="text-primary text-sm font-medium hover:underline"
                >
                  {{ $comment->post->title }}
                </a>
              @else
                <span class="text-muted-foreground text-sm">-</span>
              @endif
            </td>
            <td
              class="text-muted-foreground hidden px-6 py-4 text-center text-xs font-medium md:table-cell"
            >
              {{
                $comment->created_at->format(
                  'M d, Y H:i:s',
                )
              }}
            </td>
            <td class="px-6 py-4 text-center">
              <span
                @class ([
                  'inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[10px] font-black tracking-widest uppercase',
                  'bg-green-100 text-green-700' => $comment->active,
                  'bg-muted text-muted-foreground' => !$comment->active
                ])
              >
                <i
                  @class ([
                    'ph text-xs',
                    'ph-eye' => $comment->active,
                    'ph-eye-closed' => !$comment->active
                  ])
                ></i>
                {{
                  $comment->active
                    ? 'Visible'
                    : 'Hidden'
                }}
              </span>
            </td>
            <td class="px-6 py-4 text-right">
              <div
                class="flex justify-end gap-2 opacity-100 transition-opacity md:opacity-0 md:group-hover:opacity-100"
              >
                <button
                  wire:click="toggleStatus({{ $comment->id }})"
                  wire:loading.attr="disabled"
                  wire:target="toggleStatus({{ $comment->id }})"
                  type="button"
                  class="bg-muted text-foreground hover:bg-primary hover:text-primary-foreground flex size-9 items-center justify-center rounded-xl transition-all"
                  title="{{ $comment->active ? 'Hide Comment' : 'Show Comment' }}"
                >
                  <i class="ph {{ $comment->active ? 'ph-eye-closed' : 'ph-eye' }} text-lg"></i>
                </button>
                <button
                  type="button"
                  wire:click="$dispatch('open-confirm-modal', { componentId: '{{ $this->getName() }}', action: 'deleteConfirmed', id: {{ $comment->id }}, title: 'Delete Comment', message: 'Are you sure you want to delete this comment? This action cannot be undone.' })"
                  class="bg-muted text-foreground hover:bg-destructive hover:text-destructive-foreground flex size-9 items-center justify-center rounded-xl transition-all"
                  title="Delete"
                >
                  <i class="ph ph-trash text-lg"></i>
                </button>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="px-6 py-12 text-center">
              <div class="flex flex-col items-center gap-2">
                <i class="ph ph-chat-circle-text text-muted-foreground text-4xl opacity-20"></i>
                <span class="text-muted-foreground text-sm font-bold">No comments found.</span>
              </div>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <!-- Table Footer / Pagination -->
  <div class="border-border border-t p-6">{{ $comments->links() }}</div>
</div>

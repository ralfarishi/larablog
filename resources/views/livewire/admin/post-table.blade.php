<div
  class="bg-card border-border relative overflow-hidden rounded-3xl border shadow-sm transition-all duration-300"
>
  <!-- Table Header & Search -->
  <div
    class="border-border flex flex-col items-center justify-between gap-4 border-b p-6 md:flex-row"
  >
    <h3 class="text-muted-foreground text-sm font-black tracking-widest uppercase">
      Article <span class="text-primary">Library</span>
    </h3>

    <div class="relative w-full md:w-80">
      <span class="text-muted-foreground absolute inset-y-0 left-5 flex items-center">
        <i class="ph ph-magnifying-glass text-lg"></i>
      </span>
      <input
        type="text"
        wire:model.live.debounce.300ms="search"
        class="border-border bg-muted/30 focus:border-primary focus:bg-background focus:ring-primary/10 w-full rounded-2xl py-2.5 pr-5 pl-12 text-sm font-medium transition-all focus:ring-4"
        placeholder="Search by title or author..."
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
            Article
          </th>
          <th
            class="text-muted-foreground hidden px-6 py-4 text-xs font-black tracking-widest uppercase md:table-cell"
          >
            Category
          </th>
          <th
            class="text-muted-foreground hidden px-6 py-4 text-xs font-black tracking-widest uppercase lg:table-cell"
          >
            Tags
          </th>
          <th
            class="text-muted-foreground hidden px-6 py-4 text-center text-xs font-black tracking-widest uppercase sm:table-cell"
          >
            Engagement
          </th>
          <th class="text-muted-foreground px-6 py-4 text-xs font-black tracking-widest uppercase">
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
        @forelse ($posts as $index => $post)
          <tr wire:key="post-{{ $post->id }}" class="group hover:bg-muted/20 transition-colors">
            <td class="text-muted-foreground hidden px-6 py-4 text-sm font-bold sm:table-cell">
              {{ $posts->firstItem() + $index }}
            </td>
            <td class="px-6 py-4">
              <div class="flex flex-col">
                <a
                  href="{{ $post->status === 'published' ? route('post', $post->slug) : route('preview', $post->slug) }}"
                  target="_blank"
                  class="text-foreground hover:text-primary line-clamp-1 text-sm font-black transition-colors"
                >
                  {{ $post->title }}
                </a>
                <span class="text-muted-foreground text-[10px] font-bold tracking-wider uppercase">
                  By {{ $post->user->name }} • {{ $post->created_at->format('M d, Y H:i:s') }}
                </span>
              </div>
            </td>
            <td class="hidden px-6 py-4 md:table-cell">
              <span
                class="bg-primary/10 text-primary w-fit rounded-lg px-2 py-0.5 text-[10px] font-black tracking-widest uppercase"
              >
                {{ $post->category->name }}
              </span>
            </td>
            <td class="hidden px-6 py-4 lg:table-cell">
              <div class="flex flex-wrap gap-1">
                @foreach ($post->getRelation('tags')->take(2) as $tag)
                  <span class="text-muted-foreground text-[9px] font-bold">#{{ $tag->name }}</span>
                @endforeach
                @if ($post->getRelation('tags')->count() > 2)
                  <span class="text-muted-foreground text-[9px] font-bold"
                    >+{{ $post->getRelation('tags')->count() - 2 }}</span
                  >
                @endif
              </div>
            </td>
            <td class="hidden px-6 py-4 text-center sm:table-cell">
              <div class="text-muted-foreground flex items-center justify-center gap-4">
                <div class="flex flex-col items-center">
                  <i class="ph ph-chat-circle text-lg"></i>
                  <span class="text-[10px] font-black">{{ $post->comments_count }}</span>
                </div>
                <div class="flex flex-col items-center">
                  <i class="ph ph-bookmarks text-lg"></i>
                  <span class="text-[10px] font-black">{{ $post->bookmarks_count ?? 0 }}</span>
                </div>
              </div>
            </td>
            <td class="px-6 py-4">
              <button
                wire:click="toggleStatus('{{ $post->slug }}')"
                wire:loading.attr="disabled"
                wire:target="toggleStatus('{{ $post->slug }}')"
                type="button"
                @class ([
                  'inline-flex cursor-pointer items-center gap-1.5 rounded-full px-2.5 py-1 text-[10px] font-black tracking-widest uppercase transition-all hover:opacity-80 active:scale-95 disabled:opacity-50',
                  'bg-green-100 text-green-700' => $post->status === 'published',
                  'bg-yellow-100 text-yellow-700' => $post->status === 'draft'
                ])
                title="Click to toggle status"
              >
                <i
                  @class ([
                    'ph text-xs',
                    'ph-check-circle' => $post->status === 'published',
                    'ph-clock' => $post->status === 'draft'
                  ])
                ></i>
                {{ $post->status }}
              </button>
            </td>
            <td class="px-6 py-4 text-right">
              <div
                class="flex justify-end gap-2 opacity-100 transition-opacity md:opacity-0 md:group-hover:opacity-100"
              >
                <a
                  href="{{ $post->status === 'published' ? route('post', $post->slug) : route('preview', $post->slug) }}"
                  target="_blank"
                  class="bg-muted text-foreground hover:bg-primary hover:text-primary-foreground flex size-9 items-center justify-center rounded-xl transition-all"
                  title="Preview"
                >
                  <i class="ph ph-eye text-lg"></i>
                </a>
                <a
                  href="{{ route('article.edit', $post->slug) }}"
                  class="bg-muted text-foreground hover:bg-primary hover:text-primary-foreground flex size-9 items-center justify-center rounded-xl transition-all"
                >
                  <i class="ph ph-note-pencil text-lg"></i>
                </a>
                <button
                  type="button"
                  wire:click="$dispatch('open-confirm-modal', { componentId: '{{ $this->getName() }}', action: 'deleteConfirmed', id: '{{ $post->slug }}', title: 'Delete Article', message: 'Are you sure you want to delete this article? This will remove all associated content and comments.' })"
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
                <i class="ph ph-article text-muted-foreground text-4xl opacity-20"></i>
                <span class="text-muted-foreground text-sm font-bold">No articles found.</span>
              </div>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <!-- Table Footer / Pagination -->
  <div class="border-border border-t p-6">{{ $posts->links() }}</div>
</div>

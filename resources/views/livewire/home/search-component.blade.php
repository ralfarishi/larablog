<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
  <!-- Search Header -->
  <header class="mx-auto mb-20 max-w-3xl text-center">
    <h1 class="text-foreground mb-10 text-4xl font-black tracking-tight md:text-6xl">Discovery.</h1>

    <div class="group relative">
      <div class="pointer-events-none absolute inset-y-0 left-6 flex items-center">
        <i
          class="ph-bold ph-magnifying-glass text-muted-foreground group-focus-within:text-primary text-2xl transition-colors"
        ></i>
      </div>
      <input
        type="text"
        wire:model.live.debounce.500ms="query"
        class="bg-card border-border text-foreground placeholder:text-muted-foreground/40 focus:border-primary focus:ring-primary/5 shadow-primary/5 w-full rounded-[2.5rem] border-2 py-6 pr-8 pl-16 text-xl font-bold shadow-xl transition-all focus:ring-8 focus:outline-none"
        placeholder="Search stories, topics, or writers..."
        autofocus
      />

      <div wire:loading.flex class="absolute inset-y-0 right-8 flex items-center">
        <i class="ph-bold ph-circle-notch text-primary animate-spin text-2xl"></i>
      </div>
    </div>
  </header>

  <!-- Results Section -->
  <div class="mx-auto max-w-5xl">
    <!-- Results Grid -->
    @if ($results && count($results) > 0)
      <div class="grid grid-cols-1 gap-10">
        @foreach ($results as $post)
          <article
            class="bg-card ring-border group flex flex-col overflow-hidden rounded-[2.5rem] shadow-sm ring-1 transition-all duration-500 hover:-translate-y-1 hover:shadow-2xl md:flex-row"
          >
            <div class="bg-muted/50 aspect-video w-full overflow-hidden md:aspect-auto md:w-1/3">
              <img
                src="{{ $post->image_url }}"
                class="h-full w-full object-contain transition-transform duration-700 group-hover:scale-110"
              />
            </div>
            <div class="flex flex-1 flex-col justify-center p-8 md:p-10">
              <div class="mb-4 flex items-center gap-3">
                <span
                  class="bg-primary/10 text-primary rounded-full px-3 py-1 text-[10px] font-black tracking-widest uppercase"
                >
                  {{ $post->category?->name }}
                </span>
                <span class="text-muted-foreground text-[10px] font-bold tracking-widest uppercase">
                  {{
                    $post->created_at->format(
                      'M d, Y',
                    )
                  }}
                </span>
              </div>
              <h2
                class="text-foreground group-hover:text-primary mb-4 text-2xl leading-tight font-black transition-colors md:text-3xl"
              >
                <a href="{{ route('post', $post->slug) }}">
                  {!!
                    str_ireplace(
                      $query,
                      "<mark>$query</mark>",
                      $post->title,
                    )
                  !!}
                </a>
              </h2>
              <p class="text-muted-foreground mb-6 line-clamp-2 font-medium">
                {!!
                  str_ireplace(
                    $query,
                    "<mark>$query</mark>",
                    str(strip_tags((string) getParagraphTagOnly($post->content)))->limit(100),
                  )
                !!}
              </p>
              <div class="flex items-center gap-3">
                <span class="text-foreground text-xs font-black">
                  By <span>{{ $post->user?->name }}</span>
                </span>
              </div>
            </div>
          </article>
        @endforeach
      </div>
    @elseif (strlen($query) >= 2)
      <div class="py-20 text-center">
        <div
          class="bg-muted mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-[2.5rem]"
        >
          <i class="ph ph-detective text-muted-foreground text-4xl"></i>
        </div>
        <h3 class="text-foreground mb-2 text-2xl font-black">No matches found</h3>
        <p class="text-muted-foreground font-medium">Try adjusting your keywords or search for a broader topic.</p>
      </div>
    @else
      <div class="py-20 text-center opacity-40">
        <i class="ph ph-sparkle mb-4 text-6xl"></i>
        <p class="text-lg font-black tracking-[0.4em] uppercase">Awaiting Input</p>
      </div>
    @endif
  </div>
</div>

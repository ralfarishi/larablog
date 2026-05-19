<div class="bg-card ring-border mb-12 rounded-3xl p-6 shadow-sm ring-1 md:p-8">
  <div class="grid grid-cols-1 gap-8 md:grid-cols-3 items-start">
    <!-- Search Box -->
    <div class="w-full">
      <h3 class="text-muted-foreground mb-3 text-sm font-bold tracking-wider uppercase">
        Discover
      </h3>
      <form action="{{ route('search') }}" method="GET" id="search-form" class="group relative">
        <input
          type="text"
          name="q"
          placeholder="Search articles..."
          class="border-border bg-background text-foreground focus-visible:border-primary focus-visible:ring-primary/20 h-12 w-full rounded-xl border-2 pr-12 pl-5 font-medium transition-all focus-visible:ring-4 focus-visible:outline-none"
        />
        <button
          type="submit"
          class="bg-primary/10 text-primary hover:bg-primary hover:text-primary-foreground absolute top-1.5 right-2 bottom-1.5 flex w-9 items-center justify-center rounded-lg transition-colors"
        >
          <i class="ph ph-magnifying-glass text-lg font-bold"></i>
        </button>
      </form>
    </div>

    <!-- Categories Widget -->
    <div class="w-full">
      <h3 class="text-muted-foreground mb-3 text-sm font-bold tracking-wider uppercase">
        Topics
      </h3>
      <div class="flex flex-wrap gap-2">
        @foreach ($categories as $category)
          <a
            href="{{ route('categories', strtolower($category->name)) }}"
            class="bg-muted text-foreground hover:bg-primary hover:text-primary-foreground inline-flex items-center rounded-xl border border-transparent px-4 py-2 text-sm font-bold transition-all hover:-translate-y-0.5 hover:shadow-md"
          >
            {{ $category->name }}
            <span
              class="bg-background/50 ml-2 rounded-full px-2 py-0.5 text-xs"
              >{{ $category->posts_count }}</span
            >
          </a>
        @endforeach
      </div>
    </div>

    <!-- Tags Widget -->
    <div class="w-full">
      <h3 class="text-muted-foreground mb-3 text-sm font-bold tracking-wider uppercase">
        Trending Tags
      </h3>
      <div class="flex flex-wrap gap-2">
        @if (!$tags->isEmpty())
          @foreach ($tags->take(8) as $tag)
            <a
              href="{{ route('post-by-tag', $tag->slug) }}"
              class="text-muted-foreground hover:text-foreground ring-border hover:ring-primary hover:bg-primary/5 inline-flex items-center rounded-lg px-3 py-1.5 text-xs font-semibold ring-1 transition-all"
            >
              #{{ ucwords($tag->name) }}
            </a>
          @endforeach
        @endif
      </div>
    </div>
  </div>
</div>

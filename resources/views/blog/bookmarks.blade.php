@extends ('layouts.templates')

@section ('page-title', 'My Bookmarks')

@section ('content-id')
  <section class="bg-background min-h-screen pb-24">
    {{-- Page Header --}}
    <header class="mx-auto max-w-7xl px-4 pt-20 pb-12 sm:px-6 sm:pt-28 sm:pb-14 lg:px-8">
      <div class="flex flex-col justify-between gap-6 sm:flex-row sm:items-center">
        <div>
          <p class="text-primary mb-2 text-xs font-black tracking-[0.2em] uppercase">Personal Collection</p>
          <h1 class="text-foreground text-4xl font-black tracking-tight sm:text-5xl">
            My Bookmarks
          </h1>
          <p class="text-muted-foreground mt-2 font-medium">Articles you saved for later.</p>
        </div>
        <a
          href="{{ route('home') }}"
          class="bg-card ring-border text-muted-foreground hover:ring-primary hover:text-primary inline-flex items-center gap-2 rounded-full px-6 py-3 text-sm font-bold ring-1 transition-all duration-300 active:scale-95"
        >
          <i class="ph ph-arrow-left"></i> Back to Journal
        </a>
      </div>
    </header>

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      @if ($bookmarks->isEmpty())
        {{-- Empty State --}}
        <div class="flex flex-col items-center justify-center py-32 text-center">
          <div class="bg-muted mb-6 flex h-24 w-24 items-center justify-center rounded-[2rem]">
            <i class="ph ph-bookmark-simple text-muted-foreground/40 text-5xl"></i>
          </div>
          <h2 class="text-foreground mb-2 text-2xl font-black">No bookmarks yet</h2>
          <p class="text-muted-foreground mb-8 max-w-sm font-medium">When you find an article you love, tap the bookmark button to save it here.</p>
          <a
            href="{{ route('home') }}"
            class="bg-primary text-primary-foreground hover:shadow-primary/25 inline-flex items-center gap-2 rounded-full px-8 py-4 text-sm font-bold transition-all duration-300 hover:-translate-y-1 hover:shadow-xl active:scale-95"
          >
            <i class="ph ph-article text-lg"></i> Browse Articles
          </a>
        </div>
      @else
        {{-- Bookmarks Grid --}}
        <div class="mb-12 grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
          @foreach ($bookmarks as $bookmark)
            @if ($bookmark->post)
              @php $post = $bookmark->post; @endphp
              <article
                class="bg-card ring-border hover:shadow-primary/5 group relative flex flex-col overflow-hidden rounded-[2.5rem] shadow-sm ring-1 transition-all duration-300 hover:-translate-y-2 hover:shadow-lg"
              >
                {{-- Cover Image --}}
                <a
                  href="{{ route('post', $post->slug) }}"
                  class="relative block h-52 overflow-hidden"
                >
                  <img
                    src="{{ $post->image_url }}"
                    alt="{{ $post->title }}"
                    class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                  />
                  <div
                    class="absolute inset-0 bg-black/10 opacity-0 transition-opacity group-hover:opacity-100"
                  ></div>
                </a>

                <div class="flex flex-1 flex-col p-6">
                  {{-- Category --}}
                  @if ($post->category)
                    <div class="mb-3">
                      <a
                        href="{{ route('categories', strtolower($post->category->name)) }}"
                        class="bg-primary/10 text-primary hover:bg-primary hover:text-primary-foreground inline-flex items-center rounded-full px-3 py-1 text-[10px] font-black tracking-widest uppercase transition-colors"
                      >
                        {{ $post->category->name }}
                      </a>
                    </div>
                  @endif

                  {{-- Title --}}
                  <h2
                    class="text-foreground group-hover:text-primary mb-3 line-clamp-2 text-lg leading-snug font-bold transition-colors"
                  >
                    <a
                      href="{{ route('post', $post->slug) }}"
                      class="before:absolute before:inset-0"
                    >
                      {{ $post->title }}
                    </a>
                  </h2>

                  {{-- Meta --}}
                  <div
                    class="border-border/50 mt-auto flex items-center justify-between border-t pt-4"
                  >
                    <span
                      class="text-muted-foreground text-xs font-bold"
                      >{{ $post->user->name }}</span
                    >
                    <div class="text-muted-foreground flex items-center gap-3 text-xs font-bold">
                      <span class="flex items-center gap-1">
                        <i class="ph ph-chat-circle"></i> {{ $post->comments_count }}
                      </span>
                      <time
                        datetime="{{ $post->created_at }}"
                        >{{ $post->created_at->format('M d') }}</time
                      >
                    </div>
                  </div>
                </div>
              </article>
            @endif
          @endforeach
        </div>
        {{-- Pagination --}}
        @if ($bookmarks->hasPages())
          <div class="flex justify-center">{{ $bookmarks->links() }}</div>
        @endif
      @endif
    </div>
  </section>
@endsection

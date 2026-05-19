@extends ('layouts.templates')

@section ('page_css')
  <link
    rel="stylesheet"
    type="text/css"
    href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css"
  />
@endsection

@section ('page-title', $post->title)

@section ('content-id')
  <section class="bg-background min-h-screen pb-20">
    <!-- Article Header -->
    <header
      class="mx-auto max-w-4xl px-4 pt-20 pb-12 text-center sm:px-6 sm:pt-32 sm:pb-16 lg:px-8"
    >
      <div class="mb-8 flex flex-wrap items-center justify-center gap-3">
        <a
          href="{{ route('categories', strtolower($post->category->name)) }}"
          class="bg-primary/10 text-primary hover:bg-primary hover:text-primary-foreground inline-flex items-center rounded-full px-4 py-1.5 text-xs font-bold tracking-widest uppercase transition-colors"
        >
          {{ $post->category->name }}
        </a>
        <time
          datetime="{{ $post->created_at }}"
          class="text-muted-foreground text-sm font-semibold tracking-widest uppercase"
          >{{
            $post->created_at->format(
              'M d, Y',
            )
          }}</time
        >
      </div>

      <h1
        class="text-foreground mb-10 font-sans text-4xl leading-tight font-black tracking-tight sm:text-5xl md:text-6xl"
      >
        {{ $post->title }}
      </h1>

      <div class="flex items-center justify-center gap-4">
        <img
          src="{{ $post->user->profile_picture_url }}"
          alt="{{ $post->user->name }}"
          class="ring-border h-14 w-14 rounded-full object-cover shadow-sm ring-2"
        />
        <div class="text-left">
          <div class="text-foreground text-lg font-bold">
            <a
              href="{{ route('post-by-user', $post->user->slug) }}"
              class="hover:text-primary transition-colors"
              >{{ $post->user->name }}</a
            >
          </div>
          <div class="text-muted-foreground text-sm font-medium">Author</div>
        </div>
      </div>

      {{-- Bookmark Button --}}
      <livewire:blog.bookmark-toggle :post="$post" />
    </header>

    <!-- Hero Image -->
    <div class="mx-auto mb-16 max-w-6xl px-4 sm:mb-24 sm:px-6 lg:px-8">
      <div
        class="ring-border bg-muted/50 relative aspect-video w-full overflow-hidden rounded-4xl shadow-2xl ring-1 sm:rounded-[3rem] md:aspect-21/9"
      >
        @if ($post->image_url && !str_contains($post->image_url, 'placehold.co'))
          <img src="{{ $post->image_url }}" alt="" class="h-full w-full object-contain" />
        @else
          <div class="flex h-full w-full items-center justify-center">
            <i class="ph ph-image-square text-muted-foreground/20 text-7xl"></i>
          </div>
        @endif
      </div>
    </div>

    <!-- Main Content -->
    <article class="mx-auto mb-24 max-w-3xl px-4 sm:px-6 lg:px-8">
      <div
        class="prose prose-lg dark:prose-invert prose-headings:font-black prose-headings:tracking-tight prose-a:text-primary prose-a:font-bold prose-a:no-underline hover:prose-a:underline prose-p:text-foreground/90 prose-p:leading-relaxed prose-p:mb-8 prose-img:rounded-[2.5rem] prose-img:shadow-2xl prose-img:w-full prose-li:text-foreground/90 prose-li:mb-2 prose-blockquote:border-primary prose-blockquote:bg-primary/5 prose-blockquote:text-lg prose-blockquote:font-medium prose-blockquote:py-6 prose-blockquote:px-8 prose-blockquote:rounded-3xl prose-code:text-primary prose-code:bg-primary/10 prose-code:px-2 prose-code:py-1.5 prose-code:rounded-xl prose-code:before:hidden prose-code:after:hidden prose-pre:bg-card prose-pre:ring-1 prose-pre:ring-border prose-pre:text-foreground prose-pre:rounded-2xl prose-strong:font-black max-w-none"
      >
        {!!
          \App\Support\ContentRenderer::render(
            $post->content,
          )
        !!}
      </div>
    </article>

    <!-- Comments Section -->
    <div class="mx-auto mb-24 max-w-3xl px-4 sm:px-6 lg:px-8">
      <livewire:blog.post-comments :post="$post" />
    </div>

    <!-- Related Posts -->
    @if ($relatedPosts->isNotEmpty())
      <div class="mx-auto mt-24 max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="border-border mb-10 flex items-center justify-between border-b pb-6">
          <h3 class="text-foreground text-3xl font-black">Read Next</h3>
        </div>
        <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
          @foreach ($relatedPosts as $relatedPost)
            <article
              class="bg-card ring-border group relative flex flex-col overflow-hidden rounded-[2.5rem] shadow-sm ring-1 transition-all duration-300 hover:-translate-y-2 hover:shadow-lg"
            >
              <a
                href="{{ route('post', $relatedPost->slug) }}"
                class="relative block h-48 overflow-hidden"
              >
                <img
                  src="{{ $relatedPost->image_url }}"
                  alt="{{ $relatedPost->title }}"
                  class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                />
                <div
                  class="absolute inset-0 bg-black/10 opacity-0 transition-opacity group-hover:opacity-100"
                ></div>
              </a>
              <div class="flex flex-1 flex-col p-6">
                @if ($relatedPost->category)
                  <a
                    href="{{ route('categories', strtolower($relatedPost->category->name)) }}"
                    class="bg-primary/10 text-primary hover:bg-primary hover:text-primary-foreground mb-3 inline-flex items-center self-start rounded-full px-3 py-1 text-[10px] font-black tracking-widest uppercase transition-colors"
                  >
                    {{ $relatedPost->category->name }}
                  </a>
                @endif
                <h4
                  class="text-foreground group-hover:text-primary mb-3 line-clamp-2 text-lg leading-snug font-bold transition-colors"
                >
                  <a
                    href="{{ route('post', $relatedPost->slug) }}"
                    class="before:absolute before:inset-0"
                    >{{ $relatedPost->title }}</a
                  >
                </h4>
                <div class="mt-auto flex items-center justify-between pt-4">
                  <time
                    class="text-muted-foreground text-xs font-semibold tracking-wider uppercase"
                    datetime="{{ $relatedPost->created_at }}"
                  >
                    {{
                      $relatedPost->created_at->format(
                        'M d, Y',
                      )
                    }}
                  </time>
                  <span class="text-muted-foreground flex items-center gap-1 text-xs font-bold">
                    <i class="ph ph-chat-circle"></i> {{ $relatedPost->comments_count }}
                  </span>
                </div>
              </div>
            </article>
          @endforeach
        </div>
      </div>
    @endif
  </section>
@endsection

@section ('page_scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // ── Summernote prose table styling (vanilla JS) ──────────────
      document
        .querySelectorAll('.prose table')
        .forEach((el) =>
          el.classList.add(
            'min-w-full',
            'divide-y',
            'divide-border',
            'border',
            'border-border',
            'rounded-xl',
            'mt-6',
            'mb-6',
            'overflow-hidden',
          ),
        );
      document
        .querySelectorAll('.prose th')
        .forEach((el) =>
          el.classList.add(
            'bg-muted',
            'px-4',
            'py-3',
            'text-left',
            'text-xs',
            'font-bold',
            'text-foreground',
            'uppercase',
            'tracking-wider',
            'border-b',
            'border-border',
          ),
        );
      document
        .querySelectorAll('.prose td')
        .forEach((el) =>
          el.classList.add(
            'px-4',
            'py-3',
            'whitespace-nowrap',
            'text-sm',
            'text-muted-foreground',
            'border-b',
            'border-border',
          ),
        );
    });

    // ── Toast integration for Livewire ────────────────────────────
    window.addEventListener('toast', (event) => {
      if (typeof Toastify !== 'undefined') {
        Toastify({
          text: event.detail.message,
          duration: 2500,
          gravity: 'bottom',
          position: 'right',
          style: { background: 'oklch(65% 0.15 45)', borderRadius: '1rem', fontWeight: 'bold' },
        }).showToast();
      }
    });
  </script>
@endsection

@include ('includes.toast')

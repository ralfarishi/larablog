@extends ('layouts.templates')

@section ('page_css')
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/gh/highlightjs/cdn-release@11.8.0/build/styles/atom-one-dark.min.css"
  />
  <style>
    .prose img {
      @apply ring-border mx-auto my-20 h-auto max-w-full rounded-[3rem] shadow-2xl ring-1;
    }
    .prose pre {
      @apply border-border my-12 overflow-x-auto rounded-4xl border bg-[#282c34] shadow-2xl !important;
    }
    .prose blockquote {
      @apply bg-muted/30 text-foreground relative overflow-hidden rounded-[2.5rem] border-l-0 px-12 py-10 font-medium italic;
    }
    .prose blockquote::before {
      content: '“';
      @apply text-primary/10 absolute -top-4 -left-2 font-serif text-8xl;
    }
  </style>
@endsection

@php
  $hideNavbar = true;
  $hideFooter = true;
@endphp

@section ('page-title')
  Preview: {{ $post->title }}
@endsection

@section ('content-id')
  <section
    class="bg-background selection:bg-primary/20 relative flex min-h-screen flex-col overflow-x-hidden"
  >
    <!-- PREVIEW Banner (Fixed Top) -->
    <div
      class="bg-foreground text-background bg-opacity-95 fixed top-0 left-0 z-200 flex w-full items-center justify-between border-b border-white/5 px-8 py-4 shadow-2xl backdrop-blur-md"
    >
      <div class="flex items-center gap-6">
        <div class="flex items-center gap-3">
          <div class="bg-primary h-2 w-2 animate-pulse rounded-full"></div>
          <span class="text-[10px] font-black tracking-[0.4em] uppercase">Draft Simulation</span>
        </div>
        <div class="bg-background/20 hidden h-4 w-px sm:block"></div>
        <p class="hidden text-[11px] font-bold opacity-60 md:block">You are currently previewing an unpublished story</p>
      </div>
      <div class="flex items-center gap-4">
        <a
          href="{{ route('article.edit', $post) }}"
          class="border-background/20 hover:bg-background hover:text-foreground rounded-full border px-6 py-2 text-[10px] font-black tracking-widest uppercase transition-all"
        >
          Edit Draft
        </a>
        <a
          href="{{ route('article.index') }}"
          class="bg-primary text-primary-foreground hover:bg-primary-hover rounded-full px-6 py-2 text-[10px] font-black tracking-widest uppercase transition-all"
        >
          Exit Preview
        </a>
      </div>
    </div>

    <!-- Content Wrapper -->
    <div
      class="relative z-10 mx-auto w-full max-w-7xl px-6 pt-20 pb-20 lg:px-12 lg:pt-24"
      x-data="{
        init() {
          this.$nextTick(() => {
            if (window.motion && window.motion.animate) {
              window.motion.animate(
                '.reveal-element',
                { opacity: [0, 1], y: [30, 0] },
                { delay: window.motion.stagger(0.1), duration: 0.8, easing: [0.22, 1, 0.36, 1] },
              );
            } else {
              // Fallback: Show all elements immediately if motion library is unavailable
              document.querySelectorAll('.reveal-element').forEach((el) => {
                el.classList.remove('opacity-0');
                el.style.opacity = '1';
              });
            }
          });
        },
      }"
    >
      <!-- Editorial Header -->
      <header class="relative z-20 mx-auto mb-16 max-w-5xl lg:mb-24">
        <div class="flex flex-col items-start">
          <div class="reveal-element mb-8 flex items-center gap-6 opacity-0">
            <span
              class="bg-primary/10 text-primary ring-primary/20 inline-flex items-center rounded-full px-6 py-2.5 text-[10px] font-black tracking-[0.2em] uppercase ring-1"
            >
              {{
                $post->category->name ??
                  'Uncategorized'
              }}
            </span>
            <span class="text-muted-foreground text-[10px] font-black tracking-[0.2em] uppercase">
              {{
                $post->created_at->format(
                  'F d, Y',
                )
              }}
            </span>
          </div>

          <h1
            class="text-foreground reveal-element mb-12 w-full text-5xl leading-[0.95] font-black tracking-tighter wrap-break-word opacity-0 sm:text-6xl md:text-7xl lg:text-7xl xl:text-8xl"
          >
            {{ $post->title }}
          </h1>

          <div class="reveal-element flex items-center gap-8 opacity-0">
            <img
              src="{{ $post->user->profile_picture_url }}"
              alt="{{ $post->user->name }}"
              class="ring-background h-14 w-14 rounded-full object-cover shadow-xl ring-4"
            />
            <div class="space-y-1">
              <div class="text-muted-foreground text-[10px] font-black tracking-[0.3em] uppercase">
                Written By
              </div>
              <div class="text-foreground text-lg leading-none font-black tracking-tight">
                {{ $post->user->name }}
              </div>
            </div>
          </div>
        </div>
      </header>

      <!-- Cinematic Hero Image -->
      <div class="reveal-element mb-24 overflow-hidden opacity-0 lg:mb-32 lg:overflow-visible">
        <div class="group relative">
          <div
            class="bg-primary/5 pointer-events-none absolute -inset-10 rounded-[5rem] opacity-40 blur-[80px]"
          ></div>
          <div
            class="ring-border bg-muted/50 relative aspect-16/10 w-full overflow-hidden rounded-[3rem] shadow-[0_48px_96px_-16px_rgba(0,0,0,0.12)] ring-1 md:aspect-21/9"
          >
            @if ($post->image_url && !str_contains($post->image_url, 'placehold.co'))
              <img
                src="{{ $post->image_url }}"
                alt="{{ $post->title }}"
                class="h-full w-full object-contain transition-transform duration-2000 ease-out group-hover:scale-105"
                loading="lazy"
              />
            @else
              <div class="bg-muted flex h-full w-full items-center justify-center">
                <i class="ph ph-image-square text-muted-foreground/10 text-9xl"></i>
              </div>
            @endif
            <div
              class="pointer-events-none absolute inset-0 bg-linear-to-t from-black/20 via-transparent to-transparent"
            ></div>
          </div>
        </div>
      </div>

      <!-- Article Layout -->
      <div class="grid grid-cols-1 items-start gap-12 lg:grid-cols-12 lg:gap-16">
        <!-- Side Information -->
        <aside class="reveal-element space-y-12 opacity-0 lg:sticky lg:top-32 lg:col-span-4">
          <div class="grid grid-cols-2 gap-8 lg:grid-cols-1">
            <div class="space-y-2">
              <h4 class="text-muted-foreground text-[10px] font-black tracking-[0.3em] uppercase">
                Reading Time
              </h4>
              <p class="text-foreground text-3xl font-black">{{
                ceil(
                  str_word_count(strip_tags($post->content)) / 200,
                )
              }} <span class="text-sm font-bold italic opacity-40">min.</span></p>
            </div>
            <div class="space-y-2">
              <h4 class="text-muted-foreground text-[10px] font-black tracking-[0.3em] uppercase">
                Complexity
              </h4>
              <p class="text-foreground text-2xl font-black">
                {{
                  str_word_count(strip_tags($post->content)) > 1000
                    ? 'Deep'
                    : (str_word_count(strip_tags($post->content)) > 500
                      ? 'Moderate'
                      : 'Quick')
                }}
              </p>
            </div>
          </div>

          <div class="border-border space-y-8 border-t pt-10">
            <div class="space-y-4">
              <h4 class="text-muted-foreground text-[10px] font-black tracking-[0.3em] uppercase">
                Story Tags
              </h4>
              <div class="flex flex-wrap gap-2">
                @if ($tags->isNotEmpty())
                  @foreach ($tags as $tag)
                    <span
                      class="bg-muted/40 text-muted-foreground ring-border/50 hover:bg-background hover:text-primary cursor-default rounded-xl px-4 py-2 text-[10px] font-black tracking-widest uppercase ring-1 transition-all"
                    >
                      #{{ $tag->name }}
                    </span>
                  @endforeach
                @endif
              </div>
            </div>

            <div
              class="bg-primary/5 border-primary/10 group relative overflow-hidden rounded-[2.5rem] border p-8"
            >
              <i
                class="ph ph-sparkle text-primary/10 absolute -top-4 -right-4 rotate-12 text-5xl"
              ></i>
              <p class="text-primary/80 relative z-10 text-xs leading-relaxed font-bold italic">High-fidelity simulation. Review for clarity and flow.</p>
            </div>
          </div>
        </aside>

        <!-- Main Body -->
        <main class="reveal-element min-w-0 wrap-break-word opacity-0 lg:col-span-8">
          <div
            class="prose prose-lg dark:prose-invert prose-headings:font-black prose-headings:tracking-tight prose-a:text-primary prose-a:font-bold prose-a:no-underline hover:prose-a:underline prose-p:text-foreground/90 prose-p:leading-relaxed prose-p:mb-8 prose-img:rounded-[2.5rem] prose-img:shadow-2xl prose-img:w-full prose-li:text-foreground/90 prose-li:mb-2 prose-blockquote:border-primary prose-blockquote:bg-primary/5 prose-blockquote:text-lg prose-blockquote:font-medium prose-blockquote:py-6 prose-blockquote:px-8 prose-blockquote:rounded-3xl prose-code:text-primary prose-code:bg-primary/10 prose-code:px-2 prose-code:py-1.5 prose-code:rounded-xl prose-code:before:hidden prose-code:after:hidden prose-pre:bg-card prose-pre:ring-1 prose-pre:ring-border prose-pre:text-foreground prose-pre:rounded-2xl prose-strong:font-black max-w-none overflow-hidden"
          >
            {!!
              \App\Support\ContentRenderer::render(
                $post->content,
              )
            !!}
          </div>

          <!-- Footer Call-to-Action -->
          <div
            class="bg-card border-border relative mt-32 overflow-hidden rounded-[3.5rem] border p-8 text-center shadow-2xl lg:p-14"
          >
            <div class="bg-primary absolute top-0 left-0 h-1.5 w-full"></div>
            <div class="relative z-10">
              <div
                class="bg-muted mx-auto mb-6 flex h-14 w-14 items-center justify-center rounded-2xl"
              >
                <i class="ph-bold ph-paper-plane-tilt text-primary text-xl"></i>
              </div>
              <h3 class="text-foreground mb-4 text-3xl font-black tracking-tight">
                Ready to publish?
              </h3>
              <p class="text-muted-foreground mx-auto mb-10 max-w-md text-base leading-relaxed font-medium">Return to dashboard to make it live.</p>
              <div class="flex flex-col items-center justify-center gap-4 sm:flex-row">
                <a
                  href="{{ route('article.index') }}"
                  class="bg-primary text-primary-foreground shadow-primary/20 w-full rounded-2xl px-10 py-4 text-sm font-black shadow-lg transition-all hover:-translate-y-1 hover:shadow-xl active:scale-95 sm:w-auto"
                >
                  Yes, publish
                </a>
                <a
                  href="{{ route('article.edit', $post) }}"
                  class="bg-muted text-foreground hover:bg-muted/80 w-full rounded-2xl px-10 py-4 text-sm font-black transition-all active:scale-95 sm:w-auto"
                >
                  Needs tweaks
                </a>
              </div>
            </div>
          </div>
        </main>
      </div>
    </div>
  </section>
@endsection

@section ('page_scripts')
  <script src="https://cdn.jsdelivr.net/gh/highlightjs/cdn-release@11.8.0/build/highlight.min.js"></script>
  <script>
    hljs.highlightAll();
  </script>
@endsection

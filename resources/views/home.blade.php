@extends ('layouts.templates')

@section ('page-title', 'Home')

@section ('content-id')
  <section class="bg-background min-h-screen py-12 sm:py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <!-- Editorial Header -->
      <header
        class="border-border mb-12 flex flex-col justify-between border-b pb-8 text-center md:mb-20 md:flex-row md:items-end md:text-left"
      >
        <div>
          <h1 class="text-foreground font-sans text-5xl font-black tracking-tight md:text-7xl">
            The Journal.
          </h1>
          <p class="text-muted-foreground mt-4 max-w-2xl text-xl font-medium">Insights, stories, and thoughts on design, technology, and modern life.</p>
        </div>
        <div class="text-muted-foreground mt-6 text-sm font-bold tracking-widest uppercase md:mt-0">
          Updated {{ now()->format('M d, Y') }}
        </div>
      </header>

      <!-- Horizontal Discover Bar -->
      @include ('partials._sidebar', ['categories' => $categories, 'tags' => $tags])

      <!-- Bento Grid Layout -->
      <div
        class="posts-list"
        x-data="{
          init() {
            this.$nextTick(() => {
              if (window.motion && window.motion.animate) {
                window.motion.animate(
                  '.animate-item',
                  { opacity: [0, 1], y: [40, 0] },
                  { delay: window.motion.stagger(0.15), duration: 0.8, easing: [0.22, 1, 0.36, 1] },
                );
              } else {
                document.querySelectorAll('.animate-item').forEach((el) => {
                  el.classList.remove('opacity-0');
                  el.style.opacity = '1';
                });
              }
            });
          },
        }"
      >
        @if (count($posts) > 0)
          <!-- Featured Post (First Post) -->
          @php $featuredPost = $posts->first(); @endphp
          <div class="animate-item mb-12 opacity-0 md:mb-16">
            <article
              class="group bg-card ring-border relative flex h-auto flex-col overflow-hidden rounded-[2.5rem] shadow-xl ring-1 transition-all duration-500 hover:shadow-2xl md:h-[500px] md:flex-row"
            >
              <div class="bg-muted/50 relative h-64 w-full overflow-hidden md:h-full md:w-3/5">
                @if ($featuredPost->image_url && !str_contains($featuredPost->image_url, 'placehold.co'))
                  <img
                    src="{{ $featuredPost->image_url }}"
                    alt=""
                    class="h-full w-full object-contain transition-transform duration-700 group-hover:scale-105"
                    loading="lazy"
                  />
                @else
                  <div class="bg-muted flex h-full w-full items-center justify-center">
                    <i class="ph ph-image-square text-muted-foreground/20 text-6xl"></i>
                  </div>
                @endif
                <div
                  class="absolute inset-0 bg-linear-to-t from-black/20 via-transparent to-transparent md:hidden"
                ></div>
              </div>

              <div
                class="bg-card relative flex w-full flex-col justify-center p-8 md:w-2/5 md:bg-transparent md:p-12"
              >
                <div class="mb-6 flex items-center gap-3">
                  <span
                    class="bg-primary text-primary-foreground rounded-full px-3 py-1 text-xs font-bold tracking-wider uppercase shadow-sm"
                    >Featured</span
                  >
                  <time
                    datetime="{{ $featuredPost->created_at }}"
                    class="text-muted-foreground text-sm font-semibold"
                    >{{
                      formatDate(
                        $featuredPost->created_at,
                      )
                    }}</time
                  >
                </div>

                <h2
                  class="text-foreground group-hover:text-primary mb-6 text-3xl leading-tight font-bold transition-colors md:text-4xl"
                >
                  <a
                    href="{{ route('post', $featuredPost->slug) }}"
                    class="before:absolute before:inset-0"
                    >{{ $featuredPost->title }}</a
                  >
                </h2>

                @php $pTag = getParagraphTagOnly($featuredPost->content); @endphp
                <p class="text-muted-foreground mb-8 line-clamp-3 text-base">
                  {!!
                    str(strip_tags((string) $pTag))->limit(
                      200,
                    )
                  !!}
                </p>

                <div class="relative z-10 mt-auto flex items-center gap-4">
                  <img
                    src="{{ $featuredPost->user->profile_picture_url }}"
                    alt=""
                    class="ring-background h-12 w-12 rounded-full object-cover shadow-sm ring-2"
                  />
                  <div>
                    <div class="text-foreground font-bold">
                      <a
                        href="{{ route('post-by-user', $featuredPost->user->slug) }}"
                        class="hover:text-primary transition-colors"
                        >{{ $featuredPost->user->name }}</a
                      >
                    </div>
                    <div class="text-muted-foreground text-xs font-medium">Author</div>
                  </div>
                </div>
              </div>
            </article>
          </div>
          <!-- 3-Column Masonry Grid for Remaining Posts -->
          <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($posts->getCollection()->skip(1) as $post)
              <x-ui.post-card :post="$post" class="animate-item opacity-0" />
            @endforeach
          </div>
        @else
          <div
            class="text-muted-foreground bg-card border-border flex flex-col items-center justify-center rounded-3xl border border-dashed py-24 text-center"
          >
            <i class="ph ph-files mb-4 text-6xl opacity-20"></i>
            <h3 class="text-foreground mb-2 text-xl font-bold">No articles yet</h3>
            <p>Check back later for new content.</p>
          </div>
        @endif
      </div>

      <x-ui.pagination :paginator="$posts" />
    </div>
  </section>
@endsection

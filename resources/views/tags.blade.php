@extends ('layouts.templates')

@section ('page-title')
  Tags
@endsection

@section ('content-id')
  <section class="bg-background min-h-screen py-12 sm:py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <!-- Editorial Header -->
      <header
        class="border-border mb-12 flex flex-col justify-between border-b pb-8 text-center md:mb-20 md:flex-row md:items-end md:text-left"
      >
        <div>
          <div
            class="text-primary mb-3 flex items-center gap-2 text-sm font-bold tracking-widest uppercase"
          >
            <i class="ph ph-hash text-lg"></i>
            Tag
          </div>
          <h1 class="text-foreground font-sans text-5xl font-black tracking-tight md:text-7xl">
            #{{ ucwords($selectedTag) }}
          </h1>
        </div>
        <div
          class="text-muted-foreground bg-muted mt-6 rounded-xl px-4 py-2 text-sm font-bold tracking-widest uppercase md:mt-0"
        >
          {{ $posts->total() }} articles found
        </div>
      </header>

      <!-- Horizontal Discover Bar -->
      @include ('partials._sidebar', ['categories' => $categories, 'tags' => $tags])

      <!-- Bento Grid Layout -->
      <div class="posts-list">
        <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
          @if (count($posts) > 0)
            @foreach ($posts as $post)
              <x-ui.post-card :post="$post" />
            @endforeach
          @else
            <div
              class="text-muted-foreground bg-card border-border col-span-full flex flex-col items-center justify-center rounded-3xl border border-dashed py-24 text-center"
            >
              <i class="ph ph-files mb-4 text-6xl opacity-20"></i>
              <h3 class="text-foreground mb-2 text-xl font-bold">No articles found for this tag</h3>
              <p>Explore our other topics above.</p>
            </div>
          @endif
        </div>
      </div>

      <x-ui.pagination :paginator="$posts" />
    </div>
  </section>
@endsection

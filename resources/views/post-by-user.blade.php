@extends ('layouts.templates')

@section ('page-title', $user->name . '\'s Posts')

@section ('content-id')
  <section class="bg-background min-h-screen py-12 sm:py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <!-- Editorial Header -->
      <header
        class="border-border mb-12 flex flex-col justify-between border-b pb-8 text-center md:mb-20 md:flex-row md:items-end md:text-left"
      >
        <div class="flex flex-col items-center gap-6 md:flex-row md:items-end md:gap-8">
          <div
            class="ring-background border-border h-24 w-24 shrink-0 overflow-hidden rounded-full border shadow-lg ring-4 md:h-32 md:w-32"
          >
            @if (filter_var($user->display_picture, FILTER_VALIDATE_URL))
              <img
                src="{{ $user->display_picture }}"
                alt="{{ $user->name }}"
                class="h-full w-full object-cover"
              />
            @else
              <img
                src="{{ $user->profile_picture_url }}"
                alt="{{ $user->name }}"
                class="h-full w-full object-cover"
              />
            @endif
          </div>
          <div>
            <div class="text-primary mb-2 text-sm font-bold tracking-widest uppercase">Author</div>
            <h1 class="text-foreground font-sans text-4xl font-black tracking-tight md:text-6xl">
              {{ $user->name }}
            </h1>
          </div>
        </div>
      </header>

      <!-- Horizontal Discover Bar -->
      @include ('partials._sidebar', ['categories' => $categories, 'tags' => $tags])

      <!-- Bento Grid Layout -->
      <div class="posts-list">
        <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
          @if (count($posts) > 0)
            @foreach ($posts as $post)
              <x-ui.post-card :post="$post" :show-category="true" />
            @endforeach
          @else
            <div
              class="text-muted-foreground bg-card border-border col-span-full flex flex-col items-center justify-center rounded-3xl border border-dashed py-24 text-center"
            >
              <i class="ph ph-user mb-4 text-6xl opacity-20"></i>
              <h3 class="text-foreground mb-2 text-xl font-bold">
                No articles found from this author
              </h3>
              <p>Check back later for new content.</p>
            </div>
          @endif
        </div>
      </div>

      <x-ui.pagination :paginator="$posts" />
    </div>
  </section>
@endsection

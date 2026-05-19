@extends ('layouts.admin_v2.template')

@section ('page-title', 'Dashboard Overview')

@section ('content')
  <div
    x-data="{ loaded: false }"
    x-init="setTimeout(() => (loaded = true), 100)"
    x-bind:class="loaded ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
    class="space-y-10 transition-all duration-500 ease-out"
  >
    {{-- Welcome + CTA --}}
    <div class="flex flex-col justify-between gap-6 md:flex-row md:items-center">
      <div>
        <h3 class="text-foreground text-3xl font-black tracking-tight">
          Welcome back, {{ auth()->user()->name }}!
        </h3>
        <p class="text-muted-foreground mt-1 font-medium">Here's what's happening with your Journal today.</p>
      </div>
      <div class="flex items-center gap-3">
        <a
          href="{{ route('article.create') }}"
          class="bg-primary text-primary-foreground hover:shadow-primary/25 inline-flex items-center gap-2 rounded-full px-6 py-3 text-sm font-bold transition-all duration-300 hover:-translate-y-1 hover:shadow-xl active:scale-95"
        >
          <i class="ph ph-plus-circle text-lg"></i>
          Create New Post
        </a>
      </div>
    </div>

    {{-- Idle Drafts Alert --}}
    @if ($idleDraftsCount > 0)
      <div
        class="flex items-center gap-4 rounded-2xl border border-amber-500/20 bg-amber-500/10 p-5 text-amber-600 dark:text-amber-400"
      >
        <i class="ph ph-warning-circle shrink-0 text-2xl"></i>
        <div class="flex-1">
          <span class="font-bold"
            >{{ $idleDraftsCount }} draft{{ $idleDraftsCount > 1 ? 's have' : ' has' }} been sitting
            unpublished for over 7 days.</span
          >
          <span class="ml-1 text-sm opacity-80">Don't let great content go unread.</span>
        </div>
        <a
          href="{{ route('article.index') }}"
          class="shrink-0 rounded-xl bg-amber-500 px-4 py-2 text-xs font-black tracking-widest text-white uppercase transition-colors hover:bg-amber-600"
        >
          Review
        </a>
      </div>
    @endif

    <livewire:admin.dashboard-stats />

    {{-- Recent Posts + Quick Actions --}}
    <div class="grid grid-cols-1 gap-8 pt-4 lg:grid-cols-3">
      {{-- Recent Posts --}}
      <div class="bg-card ring-border rounded-[2.5rem] p-8 shadow-sm ring-1 lg:col-span-2">
        <div class="mb-6 flex items-center justify-between">
          <h4 class="text-foreground text-lg font-black">Recent Articles</h4>
          <a
            href="{{ route('article.index') }}"
            class="text-primary text-xs font-bold tracking-widest uppercase hover:underline"
          >
            View All
          </a>
        </div>

        @if ($recentPosts->isEmpty())
          <div
            class="text-muted-foreground flex flex-col items-center justify-center py-12 text-center"
          >
            <i class="ph ph-files mb-3 text-5xl opacity-20"></i>
            <p class="font-semibold">No articles yet. Start writing!</p>
          </div>
        @else
          <ul class="space-y-3">
            @foreach ($recentPosts as $post)
              @php
                $statusColor = match ($post->status) {
                  'published' => 'bg-green-500/10 text-green-500',
                  'hidden' => 'bg-red-500/10 text-red-500',
                  default => 'bg-amber-500/10 text-amber-500',
                };
              @endphp
              <li
                class="hover:bg-muted/50 group flex items-center gap-4 rounded-2xl p-4 transition-colors"
              >
                <div class="min-w-0 flex-1">
                  <a
                    href="{{ route('article.edit', $post) }}"
                    class="text-foreground group-hover:text-primary line-clamp-1 text-sm leading-snug font-bold transition-colors"
                  >
                    {{ $post->title }}
                  </a>
                  <div class="mt-1 flex items-center gap-3">
                    <time class="text-muted-foreground text-xs font-medium">{{
                      $post->created_at->format(
                        'M d, Y',
                      )
                    }}</time>
                    @if ($post->category)
                      <span class="text-muted-foreground text-xs"
                        >· {{ $post->category->name }}</span
                      >
                    @endif
                    <span class="text-muted-foreground text-xs"
                      >· <i class="ph ph-chat-circle"></i> {{ $post->comments_count }}</span
                    >
                  </div>
                </div>
                <span
                  class="shrink-0 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest {{ $statusColor }}"
                >
                  {{ $post->status }}
                </span>
                <a
                  href="{{ route('article.edit', $post) }}"
                  class="bg-muted hover:bg-primary hover:text-primary-foreground flex h-8 w-8 shrink-0 items-center justify-center rounded-xl opacity-0 transition-all group-hover:opacity-100"
                  title="Edit"
                >
                  <i class="ph ph-pencil-simple text-sm"></i>
                </a>
              </li>
            @endforeach
          </ul>
        @endif
      </div>

      {{-- Quick Actions --}}
      <div class="bg-card ring-border rounded-[2.5rem] p-8 shadow-sm ring-1">
        <h4 class="text-foreground mb-6 text-lg font-black">Quick Actions</h4>
        <ul class="space-y-3">
          <li>
            <a
              href="{{ route('article.create') }}"
              class="bg-muted hover:bg-primary hover:text-primary-foreground group hover:shadow-primary/10 flex items-center gap-4 rounded-2xl p-4 transition-all duration-300 hover:shadow-lg"
            >
              <i
                class="ph ph-pencil-line text-2xl transition-transform duration-300 group-hover:scale-110"
              ></i>
              <span class="text-sm font-bold">Write New Article</span>
            </a>
          </li>
          @if ($isAdmin)
            <li>
              <a
                href="{{ route('analytics.index') }}"
                class="bg-muted hover:bg-primary hover:text-primary-foreground group hover:shadow-primary/10 flex items-center gap-4 rounded-2xl p-4 transition-all duration-300 hover:shadow-lg"
              >
                <i
                  class="ph ph-chart-bar text-2xl transition-transform duration-300 group-hover:scale-110"
                ></i>
                <span class="text-sm font-bold">View Analytics</span>
              </a>
            </li>
          @endif
          <li>
            <a
              href="{{ route('user.edit', auth()->user()->slug) }}"
              class="bg-muted hover:bg-primary hover:text-primary-foreground group hover:shadow-primary/10 flex items-center gap-4 rounded-2xl p-4 transition-all duration-300 hover:shadow-lg"
            >
              <i
                class="ph ph-user-focus text-2xl transition-transform duration-300 group-hover:scale-110"
              ></i>
              <span class="text-sm font-bold">Update Profile</span>
            </a>
          </li>
          <li>
            <a
              href="/"
              class="bg-muted hover:bg-primary hover:text-primary-foreground group hover:shadow-primary/10 flex items-center gap-4 rounded-2xl p-4 transition-all duration-300 hover:shadow-lg"
            >
              <i
                class="ph ph-browser text-2xl transition-transform duration-300 group-hover:scale-110"
              ></i>
              <span class="text-sm font-bold">View Public Site</span>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </div>
@endsection

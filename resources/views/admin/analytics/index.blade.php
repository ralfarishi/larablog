@extends ('layouts.admin_v2.template')

@section ('page-title', 'Analytics')

@section ('content')
  <div
    x-data="{ loaded: false }"
    x-init="setTimeout(() => (loaded = true), 100)"
    x-bind:class="loaded ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
    class="space-y-10 transition-all duration-500 ease-out"
  >
    {{-- Page Header --}}
    <div class="flex flex-col justify-between gap-6 sm:flex-row sm:items-center">
      <div>
        <nav class="mb-2 flex" aria-label="Breadcrumb">
          <ol
            class="text-muted-foreground flex items-center space-x-2 text-xs font-bold tracking-widest uppercase"
          >
            <li>
              <a href="{{ route('dashboard') }}" class="hover:text-primary transition-colors"
                >Dashboard</a
              >
            </li>
            <li><i class="ph ph-caret-right text-[10px]"></i></li>
            <li class="text-foreground">Analytics</li>
          </ol>
        </nav>
        <h3 class="text-foreground text-3xl font-black tracking-tight">
          Platform <span class="text-primary">Analytics</span>
        </h3>
        <p class="text-muted-foreground mt-1 font-medium">Performance overview — last 8 weeks.</p>
      </div>
      <a
        href="{{ route('dashboard') }}"
        class="bg-muted text-foreground hover:bg-muted/70 inline-flex items-center gap-2 rounded-full px-6 py-3 text-sm font-bold transition-all duration-300 active:scale-95"
      >
        <i class="ph ph-arrow-left"></i> Back
      </a>
    </div>

    {{-- Stat Cards --}}
    @php
      $deltaLabel = fn(int $d) => $d > 0 ? "+{$d} this week" : ($d < 0 ? "{$d} this week" : 'No change');
      $deltaColor = fn(int $d) => $d > 0
        ? 'text-green-500 bg-green-500/10'
        : ($d < 0
          ? 'text-red-500 bg-red-500/10'
          : 'text-muted-foreground bg-muted');
    @endphp

    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
      {{-- Published Posts --}}
      <div
        class="bg-card ring-border hover:shadow-primary/5 group cursor-default rounded-[2.5rem] p-8 shadow-sm ring-1 transition-all duration-300 ease-out hover:-translate-y-1 hover:shadow-lg"
      >
        <div class="mb-6 flex items-start justify-between">
          <div
            class="flex h-14 w-14 items-center justify-center rounded-3xl bg-indigo-500/10 text-indigo-500 transition-transform duration-300 group-hover:scale-110"
          >
            <i class="ph ph-newspaper text-3xl"></i>
          </div>
          <span
            class="text-xs font-bold px-2 py-1 rounded-lg {{ $deltaColor($publishedThisWeek - $publishedLastWeek) }}"
          >
            {{
              $deltaLabel(
                $publishedThisWeek - $publishedLastWeek,
              )
            }}
          </span>
        </div>
        <h4 class="text-muted-foreground mb-1 text-sm font-bold tracking-widest uppercase">
          Published Posts
        </h4>
        <div class="text-foreground text-4xl font-black">{{ $totalPublished }}</div>
      </div>

      {{-- Total Comments --}}
      <div
        class="bg-card ring-border hover:shadow-primary/5 group cursor-default rounded-[2.5rem] p-8 shadow-sm ring-1 transition-all duration-300 ease-out hover:-translate-y-1 hover:shadow-lg"
      >
        <div class="mb-6 flex items-start justify-between">
          <div
            class="flex h-14 w-14 items-center justify-center rounded-3xl bg-amber-500/10 text-amber-500 transition-transform duration-300 group-hover:scale-110"
          >
            <i class="ph ph-chat-circle-dots text-3xl"></i>
          </div>
          <span
            class="text-xs font-bold px-2 py-1 rounded-lg {{ $deltaColor($commentsThisWeek - $commentsLastWeek) }}"
          >
            {{
              $deltaLabel(
                $commentsThisWeek - $commentsLastWeek,
              )
            }}
          </span>
        </div>
        <h4 class="text-muted-foreground mb-1 text-sm font-bold tracking-widest uppercase">
          Total Comments
        </h4>
        <div class="text-foreground text-4xl font-black">{{ $totalComments }}</div>
      </div>

      {{-- New Users --}}
      <div
        class="bg-card ring-border hover:shadow-primary/5 group cursor-default rounded-[2.5rem] p-8 shadow-sm ring-1 transition-all duration-300 ease-out hover:-translate-y-1 hover:shadow-lg"
      >
        <div class="mb-6 flex items-start justify-between">
          <div
            class="flex h-14 w-14 items-center justify-center rounded-3xl bg-emerald-500/10 text-emerald-500 transition-transform duration-300 group-hover:scale-110"
          >
            <i class="ph ph-user-plus text-3xl"></i>
          </div>
          <span
            class="text-xs font-bold px-2 py-1 rounded-lg {{ $deltaColor($newUsersThisWeek - $newUsersPrevWeek) }}"
          >
            {{
              $deltaLabel(
                $newUsersThisWeek - $newUsersPrevWeek,
              )
            }}
          </span>
        </div>
        <h4 class="text-muted-foreground mb-1 text-sm font-bold tracking-widest uppercase">
          New Users
        </h4>
        <div class="text-foreground text-4xl font-black">{{ $newUsersThisWeek }}</div>
        <p class="text-muted-foreground mt-1 text-xs font-medium">this week</p>
      </div>

      {{-- Idle Drafts --}}
      <div
        class="bg-card ring-border hover:shadow-primary/5 group cursor-default rounded-[2.5rem] p-8 shadow-sm ring-1 transition-all duration-300 ease-out hover:-translate-y-1 hover:shadow-lg"
      >
        <div class="mb-6 flex items-start justify-between">
          <div
            class="flex h-14 w-14 items-center justify-center rounded-3xl bg-amber-500/10 text-amber-500 transition-transform duration-300 group-hover:scale-110"
          >
            <i class="ph ph-clock-countdown text-3xl"></i>
          </div>
        </div>
        <h4 class="text-muted-foreground mb-1 text-sm font-bold tracking-widest uppercase">
          Idle Drafts
        </h4>
        <div class="text-foreground text-4xl font-black">{{ $idleDrafts->count() }}</div>
        <p class="text-muted-foreground mt-1 text-xs font-medium">unpublished 7+ days</p>
      </div>

      {{-- Average Reading Time --}}
      <div
        class="bg-card ring-border hover:shadow-primary/5 group cursor-default rounded-[2.5rem] p-8 shadow-sm ring-1 transition-all duration-300 ease-out hover:-translate-y-1 hover:shadow-lg"
      >
        <div class="mb-6 flex items-start justify-between">
          <div
            class="flex h-14 w-14 items-center justify-center rounded-3xl bg-sky-500/10 text-sky-500 transition-transform duration-300 group-hover:scale-110"
          >
            <i class="ph ph-timer text-3xl"></i>
          </div>
        </div>
        <h4 class="text-muted-foreground mb-1 text-sm font-bold tracking-widest uppercase">
          Avg Reading Time
        </h4>
        <div class="text-foreground text-4xl font-black">
          {{ $avgReadingTime }} <span class="text-muted-foreground text-lg font-bold">mins</span>
        </div>
        <p class="text-muted-foreground mt-1 text-xs font-medium">per published post</p>
      </div>

      {{-- Average Engagement --}}
      <div
        class="bg-card ring-border hover:shadow-primary/5 group cursor-default rounded-[2.5rem] p-8 shadow-sm ring-1 transition-all duration-300 ease-out hover:-translate-y-1 hover:shadow-lg"
      >
        <div class="mb-6 flex items-start justify-between">
          <div
            class="flex h-14 w-14 items-center justify-center rounded-3xl bg-rose-500/10 text-rose-500 transition-transform duration-300 group-hover:scale-110"
          >
            <i class="ph ph-fire text-3xl"></i>
          </div>
        </div>
        <h4 class="text-muted-foreground mb-1 text-sm font-bold tracking-widest uppercase">
          Avg Engagement
        </h4>
        <div class="text-foreground text-4xl font-black">{{ $avgEngagement }}</div>
        <p class="text-muted-foreground mt-1 text-xs font-medium">(bookmarks * 2) + comments</p>
      </div>
    </div>

    {{-- Chart + Top Posts --}}
    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
      {{-- 8-Week Publications Chart --}}
      <div class="bg-card ring-border rounded-[2.5rem] p-8 shadow-sm ring-1 sm:p-10 lg:col-span-2">
        <h4 class="text-foreground mb-1 text-lg font-black">Publications per Week</h4>
        <p class="text-muted-foreground mb-8 text-xs font-bold tracking-widest uppercase">Last 8 weeks · Published only</p>
        <div class="relative h-64">
          <canvas id="weeklyChart"></canvas>
        </div>
      </div>

      {{-- Most Discussed --}}
      <div class="bg-card ring-border rounded-[2.5rem] p-8 shadow-sm ring-1 sm:p-10">
        <h4 class="text-foreground mb-1 text-lg font-black">Most Discussed</h4>
        <p class="text-muted-foreground mb-8 text-xs font-bold tracking-widest uppercase">Top 5 by comments</p>
        @if ($topCommented->isEmpty())
          <div class="flex flex-col items-center justify-center py-10 text-center">
            <i class="ph ph-files text-muted-foreground/20 mb-3 text-5xl"></i>
            <p class="text-muted-foreground text-sm font-semibold">No published posts yet.</p>
          </div>
        @else
          <ul class="space-y-4">
            @foreach ($topCommented as $index => $post)
              <li class="group flex items-center gap-3">
                <span
                  class="w-7 h-7 shrink-0 flex items-center justify-center rounded-full text-[10px] font-black
                            {{ $index === 0 ? 'bg-primary text-primary-foreground' : 'bg-muted text-muted-foreground' }}"
                >
                  {{ $index + 1 }}
                </span>
                <div class="min-w-0 flex-1">
                  <a
                    href="{{ route('article.edit', $post) }}"
                    class="text-foreground hover:text-primary line-clamp-1 text-sm font-bold transition-colors"
                  >
                    {{ $post->title }}
                  </a>
                </div>
                <span
                  class="text-muted-foreground flex shrink-0 items-center gap-1 text-xs font-black"
                >
                  <i class="ph ph-chat-circle"></i>{{ $post->comments_count }}
                </span>
              </li>
            @endforeach
          </ul>
        @endif
      </div>
    </div>

    {{-- Bottom Grid: Idle Drafts & Activity Feed --}}
    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
      {{-- Idle Drafts Table --}}
      <div class="lg:col-span-2">
        <div
          class="bg-card ring-border flex h-full flex-col justify-between rounded-[2.5rem] p-8 shadow-sm ring-1 sm:p-10"
        >
          <div>
            <div class="mb-8 flex items-center justify-between">
              <div>
                <h4 class="text-foreground text-lg font-black">Idle Drafts</h4>
                <p class="text-muted-foreground mt-1 text-xs font-bold tracking-widest uppercase">Unpublished for 7+ days</p>
              </div>
              @if ($idleDrafts->isNotEmpty())
                <span
                  class="rounded-xl bg-amber-500/10 px-3 py-1.5 text-[10px] font-black tracking-[0.2em] text-amber-500 uppercase"
                >
                  Needs Attention
                </span>
              @endif
            </div>
            @if ($idleDrafts->isEmpty())
              <div class="flex flex-col items-center justify-center py-16 text-center">
                <div
                  class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-emerald-500/10 text-emerald-500"
                >
                  <i class="ph ph-check-circle text-4xl"></i>
                </div>
                <p class="text-foreground text-lg font-bold">All Clean!</p>
                <p class="text-muted-foreground mt-1 text-sm">No idle drafts requiring immediate attention.</p>
              </div>
            @else
              <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                  <thead>
                    <tr class="border-border border-b text-left">
                      <th
                        class="text-muted-foreground px-4 pb-4 text-xs font-black tracking-widest uppercase"
                      >
                        Title
                      </th>
                      <th
                        class="text-muted-foreground hidden px-4 pb-4 text-xs font-black tracking-widest uppercase sm:table-cell"
                      >
                        Author
                      </th>
                      <th
                        class="text-muted-foreground hidden px-4 pb-4 text-xs font-black tracking-widest uppercase sm:table-cell"
                      >
                        Last Updated
                      </th>
                      <th class="px-4 pb-4"></th>
                    </tr>
                  </thead>
                  <tbody class="divide-border/50 divide-y">
                    @foreach ($idleDrafts as $draft)
                      <tr class="group hover:bg-muted/30 transition-colors">
                        <td class="text-foreground px-4 py-4 text-sm font-medium">
                          <span class="line-clamp-1">{{ $draft->title }}</span>
                        </td>
                        <td
                          class="text-muted-foreground hidden px-4 py-4 text-sm font-medium sm:table-cell"
                        >
                          {{ $draft->user->name ?? '—' }}
                        </td>
                        <td
                          class="text-muted-foreground hidden px-4 py-4 text-sm font-medium sm:table-cell"
                        >
                          <time
                            datetime="{{ $draft->updated_at }}"
                            >{{ $draft->updated_at->diffForHumans() }}</time
                          >
                        </td>
                        <td class="px-4 py-4 text-right">
                          <a
                            href="{{ route('article.edit', $draft) }}"
                            class="bg-primary/10 text-primary hover:bg-primary hover:text-primary-foreground inline-flex items-center gap-1.5 rounded-xl px-4 py-2 text-xs font-black tracking-widest uppercase transition-all duration-300 active:scale-95"
                          >
                            <i class="ph ph-pencil-simple"></i> Edit
                          </a>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            @endif
          </div>
        </div>
      </div>

      {{-- Platform Activity Feed --}}
      <div class="bg-card ring-border rounded-[2.5rem] p-8 shadow-sm ring-1 sm:p-10">
        <h4 class="text-foreground mb-1 text-lg font-black">Platform Activity</h4>
        <p class="text-muted-foreground mb-8 text-xs font-bold tracking-widest uppercase">Recent events</p>
        @if ($activityFeed->isEmpty())
          <div class="flex flex-col items-center justify-center py-10 text-center">
            <i class="ph ph-clock-counter-clockwise text-muted-foreground/20 mb-3 text-5xl"></i>
            <p class="text-muted-foreground text-sm font-semibold">No recent activity.</p>
          </div>
        @else
          <div class="border-border/70 relative space-y-6 border-l pl-4">
            @foreach ($activityFeed as $activity)
              @php
                $classes = explode(' ', $activity['icon']);
                $iconClass = implode(' ', array_filter($classes, fn($c) => str_starts_with($c, 'ph')));
                $colorClasses = implode(' ', array_filter($classes, fn($c) => !str_starts_with($c, 'ph')));
              @endphp
              <div class="group relative flex items-start gap-4">
                {{-- Dot indicator overlapping the timeline line --}}
                <div
                  class="border-card bg-background absolute top-1.5 -left-[25px] flex h-4 w-4 items-center justify-center rounded-full border"
                >
                  <div
                    class="bg-primary/70 group-hover:bg-primary h-2 w-2 rounded-full transition-colors"
                  ></div>
                </div>

                {{-- Activity Icon --}}
                <div
                  class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $colorClasses }} transition-transform duration-300 group-hover:scale-110"
                >
                  <i class="{{ $iconClass }} text-lg"></i>
                </div>

                {{-- Activity Description --}}
                <div class="min-w-0 flex-1">
                  <div class="flex items-center justify-between gap-2">
                    <p class="text-foreground truncate text-sm font-bold">
                      {{ $activity['user'] }}
                    </p>
                    <time
                      class="text-muted-foreground shrink-0 text-[10px] font-bold tracking-wider uppercase"
                    >
                      {{
                        $activity['time']->diffForHumans(
                          null,
                          true,
                        )
                      }}
                    </time>
                  </div>
                  <p class="text-muted-foreground mt-1 text-xs leading-relaxed font-semibold">
                    {{ $activity['content'] }}
                  </p>
                  @if (!empty($activity['target']))
                    <p class="text-primary mt-0.5 truncate text-xs font-bold">
                      {{ $activity['target'] }}
                    </p>
                  @endif
                </div>
              </div>
            @endforeach
          </div>
        @endif
      </div>
    </div>
  </div>
@endsection

@section ('page_scripts')
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const root = getComputedStyle(document.documentElement);
      const primary = root.getPropertyValue('--primary').trim();
      const border = root.getPropertyValue('--border').trim();
      const muted = root.getPropertyValue('--muted-foreground').trim();

      const labels = @json ($chartLabels);
      const data = @json ($chartData);

      new Chart(document.getElementById('weeklyChart'), {
        type: 'bar',
        data: {
          labels,
          datasets: [
            {
              label: 'Published',
              data,
              backgroundColor: `oklch(from ${primary} l c h / 0.12)`,
              borderColor: `oklch(from ${primary} l c h / 0.85)`,
              borderWidth: 2,
              borderRadius: 10,
              borderSkipped: false,
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { display: false },
            tooltip: {
              backgroundColor: 'var(--card)',
              borderColor: 'var(--border)',
              borderWidth: 1,
              titleColor: 'var(--foreground)',
              bodyColor: 'var(--muted-foreground)',
              cornerRadius: 16,
              padding: 14,
              callbacks: {
                label: (ctx) => ` ${ctx.parsed.y} post${ctx.parsed.y !== 1 ? 's' : ''}`,
              },
            },
          },
          scales: {
            x: {
              grid: { color: `oklch(from ${border} l c h / 0.5)`, drawTicks: false },
              ticks: { color: muted, font: { weight: '700', size: 11 }, padding: 8 },
              border: { display: false },
            },
            y: {
              beginAtZero: true,
              grid: { color: `oklch(from ${border} l c h / 0.5)` },
              ticks: {
                color: muted,
                font: { weight: '700', size: 11 },
                stepSize: 1,
                precision: 0,
                padding: 8,
              },
              border: { display: false },
            },
          },
        },
      });
    });
  </script>
@endsection

@props ([
  'title',
  'breadcrumbs' => [], // [['label' => 'Dashboard', 'route' => 'dashboard'], ['label' => 'Articles']]
  'actionLabel' => null,
  'actionRoute' => null,
  'actionIcon' => 'ph-plus-bold'
])

<div class="flex flex-col justify-between gap-6 sm:flex-row sm:items-center">
  <div>
    @if (count($breadcrumbs) > 0)
      <nav class="mb-2 flex" aria-label="Breadcrumb">
        <ol
          class="text-muted-foreground flex items-center space-x-2 text-xs font-bold tracking-widest uppercase"
        >
          @foreach ($breadcrumbs as $crumb)
            @if (!$loop->last)
              <li>
                <a
                  href="{{ isset($crumb['route']) ? route($crumb['route']) : $crumb['url'] }}"
                  class="hover:text-primary transition-colors"
                  >{{ $crumb['label'] }}</a
                >
              </li>
              <li><i class="ph ph-caret-right text-[10px]"></i></li>
            @else
              <li class="text-foreground">{{ $crumb['label'] }}</li>
            @endif
          @endforeach
        </ol>
      </nav>
    @endif
    <h3 class="text-foreground text-3xl font-black tracking-tight">{!! $title !!}</h3>
  </div>

  @if ($actionLabel && $actionRoute)
    <a
      href="{{ route($actionRoute) }}"
      class="bg-foreground text-background inline-flex items-center gap-2 rounded-full px-8 py-4 text-sm font-bold shadow-sm transition-all hover:-translate-y-0.5 hover:shadow-lg"
    >
      <i class="{{ $actionIcon }}"></i>
      {{ $actionLabel }}
    </a>
  @endif

  {{ $slot }}
</div>

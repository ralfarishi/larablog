@props (['paginator'])

@if ($paginator->lastPage() > 1)
  <div class="mt-16 flex justify-center">
    <nav
      class="bg-card ring-border inline-flex items-center gap-2 rounded-2xl p-2 shadow-sm ring-1"
      aria-label="Pagination"
    >
      @for ($i = 1; $i <= $paginator->lastPage(); $i++)
        <a
          href="{{ $paginator->url($i) }}"
          class="inline-flex items-center justify-center w-12 h-12 rounded-xl text-sm font-bold transition-all {{ $i === $paginator->currentPage() ? 'bg-primary text-primary-foreground shadow-md' : 'text-foreground hover:bg-muted' }}"
        >
          {{ $i }}
        </a>
      @endfor
    </nav>
  </div>
@endif

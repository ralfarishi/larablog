@php
  if (!isset($scrollTo)) {
    $scrollTo = 'body';
  }

  $scrollIntoViewJsSnippet =
    $scrollTo !== false
      ? sprintf(
        '($el.closest(\'%s\') || document.querySelector(\'%s\')).scrollIntoView()',
        $scrollTo,
        $scrollTo,
      )
      : '';
@endphp

<div>
  @if ($paginator->hasPages())
    <nav
      role="navigation"
      aria-label="Pagination Navigation"
      class="flex w-full flex-col items-center justify-center gap-4"
    >
      {{-- Mobile Pagination --}}
      <div class="flex w-full items-center justify-between gap-4 sm:hidden">
        @if ($paginator->onFirstPage())
          <span
            class="bg-card border-border text-muted-foreground relative inline-flex flex-1 items-center justify-center rounded-2xl border px-4 py-2.5 text-xs font-black tracking-widest uppercase opacity-50 shadow-sm"
          >
            {!! __('pagination.previous') !!}
          </span>
        @else
          <button
            type="button"
            wire:click="previousPage('{{ $paginator->getPageName() }}')"
            x-on:click="{{ $scrollIntoViewJsSnippet }}"
            wire:loading.attr="disabled"
            class="bg-card border-border text-foreground hover:bg-muted/50 relative inline-flex flex-1 items-center justify-center rounded-2xl border px-4 py-2.5 text-xs font-black tracking-widest uppercase shadow-sm transition-colors active:scale-95"
          >
            {!! __('pagination.previous') !!}
          </button>
        @endif

        @if ($paginator->hasMorePages())
          <button
            type="button"
            wire:click="nextPage('{{ $paginator->getPageName() }}')"
            x-on:click="{{ $scrollIntoViewJsSnippet }}"
            wire:loading.attr="disabled"
            class="bg-card border-border text-foreground hover:bg-muted/50 relative inline-flex flex-1 items-center justify-center rounded-2xl border px-4 py-2.5 text-xs font-black tracking-widest uppercase shadow-sm transition-colors active:scale-95"
          >
            {!! __('pagination.next') !!}
          </button>
        @else
          <span
            class="bg-card border-border text-muted-foreground relative inline-flex flex-1 items-center justify-center rounded-2xl border px-4 py-2.5 text-xs font-black tracking-widest uppercase opacity-50 shadow-sm"
          >
            {!! __('pagination.next') !!}
          </span>
        @endif
      </div>

      {{-- Desktop Pagination --}}
      <div
        class="hidden sm:flex sm:w-full sm:flex-col sm:items-center sm:justify-between sm:gap-4 md:flex-row md:gap-0"
      >
        {{-- Left: Showing Results --}}
        <div class="flex flex-1 items-center justify-center md:justify-start">
          <p class="text-muted-foreground flex items-center gap-2 text-xs font-bold tracking-widest uppercase">
            <span>{!! __('Showing') !!}</span>
            <span
              class="bg-muted text-foreground inline-flex items-center justify-center rounded-lg px-2.5 py-1 leading-none font-black"
              >{{ $paginator->firstItem() }}</span
            >
            <span class="opacity-50">-</span>
            <span
              class="bg-muted text-foreground inline-flex items-center justify-center rounded-lg px-2.5 py-1 leading-none font-black"
              >{{ $paginator->lastItem() }}</span
            >
            <span class="opacity-50">{!! __('of') !!}</span>
            <span class="text-foreground font-black">{{ $paginator->total() }}</span>
          </p>
        </div>

        {{-- Right: Pagination Controls --}}
        <div class="flex flex-1 items-center justify-center md:justify-end">
          <span class="relative z-0 inline-flex flex-wrap items-center gap-2">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
              <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                <span
                  class="bg-card border-border text-muted-foreground relative inline-flex size-10 items-center justify-center rounded-xl border opacity-50 shadow-sm"
                  aria-hidden="true"
                >
                  <i class="ph ph-caret-left text-lg"></i>
                </span>
              </span>
            @else
              <button
                type="button"
                wire:click="previousPage('{{ $paginator->getPageName() }}')"
                x-on:click="{{ $scrollIntoViewJsSnippet }}"
                class="bg-card border-border text-foreground hover:bg-muted/50 hover:text-primary relative inline-flex size-10 items-center justify-center rounded-xl border shadow-sm transition-all active:scale-95"
                aria-label="{{ __('pagination.previous') }}"
              >
                <i class="ph ph-caret-left text-lg"></i>
              </button>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
              {{-- "Three Dots" Separator --}}
              @if (is_string($element))
                <span aria-disabled="true">
                  <span
                    class="text-muted-foreground relative inline-flex cursor-default items-center px-2 py-2 text-sm font-black"
                    >{{ $element }}</span
                  >
                </span>
              @endif
              {{-- Array Of Links --}}
              @if (is_array($element))
                @foreach ($element as $page => $url)
                  <span wire:key="paginator-{{ $paginator->getPageName() }}-page{{ $page }}">
                    @if ($page == $paginator->currentPage())
                      <span aria-current="page">
                        <span
                          class="bg-primary text-primary-foreground shadow-primary/20 relative inline-flex size-10 cursor-default items-center justify-center rounded-xl text-sm font-black shadow-lg"
                          >{{ $page }}</span
                        >
                      </span>
                    @else
                      <button
                        type="button"
                        wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                        x-on:click="{{ $scrollIntoViewJsSnippet }}"
                        class="text-muted-foreground hover:bg-muted/50 hover:text-foreground relative inline-flex size-10 items-center justify-center rounded-xl text-sm font-bold transition-all active:scale-95"
                        aria-label="{{ __('Go to page :page', ['page' => $page]) }}"
                      >
                        {{ $page }}
                      </button>
                    @endif
                  </span>
                @endforeach
              @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
              <button
                type="button"
                wire:click="nextPage('{{ $paginator->getPageName() }}')"
                x-on:click="{{ $scrollIntoViewJsSnippet }}"
                class="bg-card border-border text-foreground hover:bg-muted/50 hover:text-primary relative inline-flex size-10 items-center justify-center rounded-xl border shadow-sm transition-all active:scale-95"
                aria-label="{{ __('pagination.next') }}"
              >
                <i class="ph ph-caret-right text-lg"></i>
              </button>
            @else
              <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                <span
                  class="bg-card border-border text-muted-foreground relative inline-flex size-10 items-center justify-center rounded-xl border opacity-50 shadow-sm"
                  aria-hidden="true"
                >
                  <i class="ph ph-caret-right text-lg"></i>
                </span>
              </span>
            @endif
          </span>
        </div>
      </div>
    </nav>
  @endif
</div>

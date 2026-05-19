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
    <nav role="navigation" aria-label="Pagination Navigation" class="flex justify-between">
      <span>
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
          <span
            class="relative inline-flex cursor-default items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm leading-5 font-medium text-gray-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-600"
          >
            {!! __('pagination.previous') !!}
          </span>
        @else
          @if (method_exists($paginator, 'getCursorName'))
            <button
              type="button"
              dusk="previousPage"
              wire:key="cursor-{{ $paginator->getCursorName() }}-{{ $paginator->previousCursor()->encode() }}"
              wire:click="setPage('{{$paginator->previousCursor()->encode()}}','{{ $paginator->getCursorName() }}')"
              x-on:click="{{ $scrollIntoViewJsSnippet }}"
              wire:loading.attr="disabled"
              class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm leading-5 font-medium text-gray-700 ring-blue-300 transition duration-150 ease-in-out hover:text-gray-500 focus:border-blue-300 focus:ring focus:outline-none active:bg-gray-100 active:text-gray-700 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:focus:border-blue-700 dark:active:bg-gray-700 dark:active:text-gray-300"
            >
              {!! __('pagination.previous') !!}
            </button>
          @else
            <button
              type="button"
              wire:click="previousPage('{{ $paginator->getPageName() }}')"
              x-on:click="{{ $scrollIntoViewJsSnippet }}"
              wire:loading.attr="disabled"
              dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}"
              class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm leading-5 font-medium text-gray-700 ring-blue-300 transition duration-150 ease-in-out hover:text-gray-500 focus:border-blue-300 focus:ring focus:outline-none active:bg-gray-100 active:text-gray-700 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:focus:border-blue-700 dark:active:bg-gray-700 dark:active:text-gray-300"
            >
              {!! __('pagination.previous') !!}
            </button>
          @endif
        @endif
      </span>

      <span>
        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
          @if (method_exists($paginator, 'getCursorName'))
            <button
              type="button"
              dusk="nextPage"
              wire:key="cursor-{{ $paginator->getCursorName() }}-{{ $paginator->nextCursor()->encode() }}"
              wire:click="setPage('{{$paginator->nextCursor()->encode()}}','{{ $paginator->getCursorName() }}')"
              x-on:click="{{ $scrollIntoViewJsSnippet }}"
              wire:loading.attr="disabled"
              class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm leading-5 font-medium text-gray-700 ring-blue-300 transition duration-150 ease-in-out hover:text-gray-500 focus:border-blue-300 focus:ring focus:outline-none active:bg-gray-100 active:text-gray-700 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:focus:border-blue-700 dark:active:bg-gray-700 dark:active:text-gray-300"
            >
              {!! __('pagination.next') !!}
            </button>
          @else
            <button
              type="button"
              wire:click="nextPage('{{ $paginator->getPageName() }}')"
              x-on:click="{{ $scrollIntoViewJsSnippet }}"
              wire:loading.attr="disabled"
              dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}"
              class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm leading-5 font-medium text-gray-700 ring-blue-300 transition duration-150 ease-in-out hover:text-gray-500 focus:border-blue-300 focus:ring focus:outline-none active:bg-gray-100 active:text-gray-700 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:focus:border-blue-700 dark:active:bg-gray-700 dark:active:text-gray-300"
            >
              {!! __('pagination.next') !!}
            </button>
          @endif
        @else
          <span
            class="relative inline-flex cursor-default items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm leading-5 font-medium text-gray-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-600"
          >
            {!! __('pagination.next') !!}
          </span>
        @endif
      </span>
    </nav>
  @endif
</div>

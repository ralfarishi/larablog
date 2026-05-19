<div class="mt-8 flex justify-center">
  <button
    wire:click="toggle"
    @class ([
      'inline-flex items-center gap-2 px-6 py-3 rounded-full font-bold text-sm ring-1 transition-all duration-300 active:scale-95',
      'bg-primary text-primary-foreground ring-primary shadow-lg shadow-primary/20' => $isBookmarked,
      'bg-card text-muted-foreground ring-border hover:ring-primary hover:text-primary' => !$isBookmarked
    ])
  >
    <i
      wire:loading.remove
      wire:target="toggle"
      @class ([
        'ph text-lg',
        'ph-bookmark-simple-fill' => $isBookmarked,
        'ph-bookmark-simple' => !$isBookmarked
      ])
    ></i>
    <i wire:loading wire:target="toggle" class="ph ph-circle-notch animate-spin text-lg"></i>

    <span>{{
      $isBookmarked
        ? 'Bookmarked'
        : 'Save to Bookmarks'
    }}</span>
  </button>
</div>

<div class="space-y-6">
  <div class="flex items-center justify-between">
    <h4 class="text-muted-foreground text-xs font-black tracking-widest uppercase">
      Key Performance Indicators
    </h4>
    <div class="flex items-center gap-2">
      <select
        wire:model.live="period"
        class="bg-muted/50 border-border rounded-xl border px-3 py-1.5 text-[10px] font-black tracking-widest uppercase focus:ring-0"
      >
        <option value="this_week">Last 7 Days</option>
        <option value="this_month">Last 30 Days</option>
        <option value="all_time">All Time</option>
      </select>
    </div>
  </div>

  <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
    {{-- Articles --}}
    <div
      class="bg-card ring-border group rounded-[2.5rem] p-8 shadow-sm ring-1 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg"
    >
      <div class="mb-6 flex items-start justify-between">
        <div
          class="flex h-14 w-14 items-center justify-center rounded-3xl bg-indigo-500/10 text-indigo-500 transition-transform duration-300 group-hover:scale-110"
        >
          <i class="ph ph-newspaper text-3xl"></i>
        </div>
      </div>
      <h4 class="text-muted-foreground mb-1 text-sm font-bold tracking-widest uppercase">
        Articles
      </h4>
      <div class="text-foreground text-4xl font-black" wire:loading.class="opacity-50">
        {{ $stats['totalPosts'] }}
      </div>
    </div>

    {{-- Comments --}}
    <div
      class="bg-card ring-border group rounded-[2.5rem] p-8 shadow-sm ring-1 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg"
    >
      <div class="mb-6 flex items-start justify-between">
        <div
          class="flex h-14 w-14 items-center justify-center rounded-3xl bg-amber-500/10 text-amber-500 transition-transform duration-300 group-hover:scale-110"
        >
          <i class="ph ph-chat-circle-dots text-3xl"></i>
        </div>
      </div>
      <h4 class="text-muted-foreground mb-1 text-sm font-bold tracking-widest uppercase">
        Comments
      </h4>
      <div class="text-foreground text-4xl font-black" wire:loading.class="opacity-50">
        {{ $stats['totalComments'] }}
      </div>
    </div>

    @if ($stats['isAdmin'])
      {{-- Categories --}}
      <div
        class="bg-card ring-border group rounded-[2.5rem] p-8 shadow-sm ring-1 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg"
      >
        <div class="mb-6 flex items-start justify-between">
          <div
            class="flex h-14 w-14 items-center justify-center rounded-3xl bg-emerald-500/10 text-emerald-500 transition-transform duration-300 group-hover:scale-110"
          >
            <i class="ph ph-tag text-3xl"></i>
          </div>
        </div>
        <h4 class="text-muted-foreground mb-1 text-sm font-bold tracking-widest uppercase">
          Categories
        </h4>
        <div class="text-foreground text-4xl font-black" wire:loading.class="opacity-50">
          {{ $stats['totalCategories'] }}
        </div>
      </div>
      {{-- Users --}}
      <div
        class="bg-card ring-border group rounded-[2.5rem] p-8 shadow-sm ring-1 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg"
      >
        <div class="mb-6 flex items-start justify-between">
          <div
            class="flex h-14 w-14 items-center justify-center rounded-3xl bg-rose-500/10 text-rose-500 transition-transform duration-300 group-hover:scale-110"
          >
            <i class="ph ph-users-three text-3xl"></i>
          </div>
        </div>
        <h4 class="text-muted-foreground mb-1 text-sm font-bold tracking-widest uppercase">
          Users
        </h4>
        <div class="text-foreground text-4xl font-black" wire:loading.class="opacity-50">
          {{ $stats['totalUsers'] }}
        </div>
      </div>
    @endif
  </div>
</div>

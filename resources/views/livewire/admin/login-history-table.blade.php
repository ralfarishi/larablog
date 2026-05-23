<div class="bg-card ring-border overflow-hidden rounded-[2.5rem] shadow-sm ring-1">
  <div
    class="border-border bg-muted/30 flex flex-col justify-between gap-6 border-b p-8 md:flex-row md:items-center"
  >
    <div>
      <h4 class="text-foreground text-lg font-black">Session Logs</h4>
      <p class="text-muted-foreground mt-1 text-[10px] font-black tracking-[0.2em] uppercase">Monitor authentication attempts</p>
    </div>

    <div class="flex w-full flex-col gap-4 md:w-auto md:flex-row md:items-center">
      <!-- Custom Search Box -->
      <div class="group relative w-full md:w-64">
        <div class="pointer-events-none absolute inset-y-0 left-5 flex items-center">
          <i
            class="ph ph-magnifying-glass text-muted-foreground group-focus-within:text-primary transition-colors"
          ></i>
        </div>
        <input
          type="text"
          wire:model.live.debounce.300ms="search"
          class="bg-background border-border text-foreground placeholder:text-muted-foreground/50 focus:border-primary focus:ring-primary/10 w-full rounded-2xl border-2 py-3 pr-5 pl-12 text-sm font-bold transition-all focus:ring-4 focus:outline-none"
          placeholder="Search logs..."
        />
      </div>

      <button
        type="button"
        wire:click="$dispatch('open-confirm-modal', { componentId: '{{ $this->getName() }}', action: 'clearHistoryConfirmed', id: null, title: 'Clear History', message: 'Are you sure you want to permanently delete all login history logs? This action cannot be undone.' })"
        class="flex items-center gap-2 rounded-2xl bg-red-500/10 px-5 py-3 text-sm font-bold text-red-500 transition-all hover:bg-red-500 hover:text-white active:scale-95"
      >
        <i class="ph ph-trash text-lg"></i>
        Clear History
      </button>
    </div>
  </div>

  <div class="p-8">
    <div class="table-responsive">
      <table class="w-full text-left">
        <thead>
          <tr class="text-muted-foreground text-[10px] font-black tracking-widest uppercase">
            <th class="hidden px-4 py-4 sm:table-cell">#</th>
            <th class="px-4 py-4">Identity</th>
            <th class="hidden px-4 py-4 sm:table-cell">IP Address</th>
            <th class="px-4 py-4">Status</th>
            <th class="px-4 py-4">Login Time</th>
            <th class="hidden px-4 py-4 md:table-cell">Logout Time</th>
            <th class="hidden px-4 py-4 lg:table-cell">Activity</th>
          </tr>
        </thead>
        <tbody class="divide-border/50 divide-y">
          @forelse ($history as $row)
            <tr wire:key="{{ $row->id }}" class="group hover:bg-muted/10 transition-colors">
              <td class="text-muted-foreground/50 hidden px-4 py-5 text-sm font-bold sm:table-cell">
                {{
                  ($history->currentPage() - 1) * $history->perPage() +
                    $loop->iteration
                }}
              </td>
              <td class="px-4 py-5">
                <div class="text-foreground text-sm font-bold">{{ $row->email }}</div>
                <div
                  class="text-muted-foreground truncate text-[10px] font-medium"
                  style="max-width: 200px"
                  title="{{ $row->user_agent }}"
                >
                  {{ $row->user_agent }}
                </div>
              </td>
              <td class="hidden px-4 py-5 sm:table-cell">
                <code class="bg-muted text-foreground rounded px-2 py-1 font-mono text-xs">
                  {{ $row->ip_address }}
                </code>
              </td>
              <td class="px-4 py-5">
                <span
                  class="px-3 py-1 rounded-xl text-[10px] font-black uppercase tracking-[0.2em] {{ $row->status ? 'bg-green-500/10 text-green-500' : 'bg-red-500/10 text-red-500' }}"
                >
                  {{
                    $row->status
                      ? 'Success'
                      : 'Failed'
                  }}
                </span>
              </td>
              <td class="px-4 py-5">
                <span class="text-muted-foreground text-sm font-medium">
                  {{
                    $row->created_at->format(
                      'd-m-Y H:i:s',
                    )
                  }}
                </span>
              </td>
              <td class="hidden px-4 py-5 md:table-cell">
                <span class="text-muted-foreground text-sm font-medium">
                  {{
                    $row->logout_at
                      ? $row->logout_at->format('d-m-Y H:i:s')
                      : 'Active'
                  }}
                </span>
              </td>
              <td class="hidden px-4 py-5 lg:table-cell">
                <span class="text-foreground text-sm font-bold">{{ $row->activity ?? '—' }}</span>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="py-20 text-center">
                <div
                  class="bg-muted mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl"
                >
                  <i class="ph ph-shield-check text-muted-foreground text-3xl"></i>
                </div>
                <h3 class="text-foreground text-lg font-black">No history found</h3>
                <p class="text-muted-foreground text-sm font-medium">The log is currently empty.</p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="mt-8">{{ $history->links() }}</div>
  </div>
</div>

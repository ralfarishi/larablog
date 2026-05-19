<div
  class="bg-card border-border overflow-hidden rounded-3xl border shadow-sm transition-all duration-300"
>
  <!-- Table Header & Search -->
  <div
    class="border-border flex flex-col items-center justify-between gap-4 border-b p-6 md:flex-row"
  >
    <h3 class="text-muted-foreground text-sm font-black tracking-widest uppercase">
      User <span class="text-primary">Directory</span>
    </h3>

    <div class="relative w-full md:w-80">
      <span class="text-muted-foreground absolute inset-y-0 left-5 flex items-center">
        <i class="ph ph-magnifying-glass text-lg"></i>
      </span>
      <input
        type="text"
        wire:model.live.debounce.300ms="search"
        class="border-border bg-muted/30 focus:border-primary focus:bg-background focus:ring-primary/10 w-full rounded-2xl py-2.5 pr-5 pl-12 text-sm font-medium transition-all focus:ring-4"
        placeholder="Search by name or email..."
      />
    </div>
  </div>

  <!-- Table Content -->
  <div class="overflow-x-auto">
    <table class="w-full border-collapse text-left">
      <thead>
        <tr class="bg-muted/30">
          <th class="text-muted-foreground px-6 py-4 text-xs font-black tracking-widest uppercase">
            #
          </th>
          <th class="text-muted-foreground px-6 py-4 text-xs font-black tracking-widest uppercase">
            User
          </th>
          <th class="text-muted-foreground px-6 py-4 text-xs font-black tracking-widest uppercase">
            Email
          </th>
          <th
            class="text-muted-foreground px-6 py-4 text-center text-xs font-black tracking-widest uppercase"
          >
            Articles
          </th>
          <th
            class="text-muted-foreground px-6 py-4 text-right text-xs font-black tracking-widest uppercase"
          >
            Actions
          </th>
        </tr>
      </thead>
      <tbody class="divide-border divide-y">
        @forelse ($users as $index => $user)
          <tr class="group hover:bg-muted/20 transition-colors">
            <td class="text-muted-foreground px-6 py-4 text-sm font-bold">
              {{ $users->firstItem() + $index }}
            </td>
            <td class="px-6 py-4">
              <div class="flex items-center gap-4">
                <div
                  class="border-background size-10 overflow-hidden rounded-xl border-2 shadow-sm"
                >
                  <img src="{{ $user->profile_picture_url }}" class="size-full object-cover" />
                </div>
                <div class="flex flex-col">
                  <span class="text-foreground text-sm font-black">{{ $user->name }}</span>
                  <span
                    @class ([
                      'inline-flex w-fit rounded-lg px-2 py-0.5 text-[10px] font-black tracking-widest uppercase',
                      'bg-primary/10 text-primary' => $user->role === 'admin',
                      'bg-muted text-muted-foreground' => $user->role !== 'admin'
                    ])
                  >
                    {{ $user->role }}
                  </span>
                </div>
              </div>
            </td>
            <td class="text-foreground px-6 py-4 text-sm font-medium italic">{{ $user->email }}</td>
            <td class="px-6 py-4 text-center">
              <span
                class="bg-muted text-foreground inline-flex size-8 items-center justify-center rounded-lg text-xs font-black"
              >
                {{
                  $user->posts_count ??
                    $user->posts()->count()
                }}
              </span>
            </td>
            <td class="px-6 py-4 text-right">
              <div
                class="flex justify-end gap-2 opacity-100 transition-opacity md:opacity-0 md:group-hover:opacity-100"
              >
                <a
                  href="{{ route('user.edit', $user->slug) }}"
                  class="bg-muted text-foreground hover:bg-primary hover:text-primary-foreground flex size-9 items-center justify-center rounded-xl transition-all"
                >
                  <i class="ph ph-note-pencil text-lg"></i>
                </a>
                @if ($user->id !== auth()->id())
                  <button
                    type="button"
                    wire:click="$dispatch('open-confirm-modal', { componentId: '{{ $this->getName() }}', action: 'deleteConfirmed', id: '{{ $user->slug }}', title: 'Delete User', message: 'Are you sure you want to delete this user? This action will permanently remove their account and all associated data.' })"
                    class="bg-muted text-foreground hover:bg-destructive hover:text-destructive-foreground flex size-9 items-center justify-center rounded-xl transition-all"
                    title="Delete"
                  >
                    <i class="ph ph-trash text-lg"></i>
                  </button>
                @endif
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="px-6 py-12 text-center">
              <div class="flex flex-col items-center gap-2">
                <i class="ph ph-users-three text-muted-foreground text-4xl opacity-20"></i>
                <span class="text-muted-foreground text-sm font-bold">No users found.</span>
              </div>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <!-- Table Footer / Pagination -->
  <div class="border-border border-t p-6">{{ $users->links() }}</div>
</div>

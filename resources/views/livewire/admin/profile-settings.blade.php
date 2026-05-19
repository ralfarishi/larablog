<div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
  <!-- Left Column: Avatar Management -->
  <div class="lg:col-span-1">
    <div class="bg-card border-border rounded-3xl border p-8 shadow-sm">
      <div class="flex flex-col items-center">
        <div class="group relative inline-block">
          <div
            class="border-background size-40 overflow-hidden rounded-3xl border-4 shadow-2xl transition-transform duration-500 group-hover:scale-[1.02]"
          >
            @if ($photo)
              <img src="{{ $photo->temporaryUrl() }}" class="size-full object-cover" />
            @else
              <img src="{{ $user->profile_picture_url }}" class="size-full object-cover" />
            @endif

            <div
              wire:loading
              wire:target="photo"
              class="bg-background/80 absolute inset-0 z-10 grid place-items-center backdrop-blur-sm"
            >
              <div class="flex flex-col items-center">
                <div
                  class="border-primary h-10 w-10 animate-spin rounded-full border-4 border-t-transparent"
                ></div>
                <p class="text-primary mt-3 animate-pulse text-[10px] font-black tracking-widest uppercase">Uploading...</p>
              </div>
            </div>
          </div>

          <label
            for="photo"
            class="bg-primary text-primary-foreground absolute -right-2 -bottom-2 flex size-9 cursor-pointer items-center justify-center rounded-xl shadow-lg transition-all duration-300 hover:scale-110 active:scale-95"
          >
            <i class="ph ph-camera text-base"></i>
            <input type="file" id="photo" wire:model="photo" class="hidden" accept="image/*" />
          </label>
        </div>

        <div class="mt-10 w-full space-y-4">
          <div
            class="bg-muted/50 hover:bg-muted flex items-center justify-between rounded-3xl p-5 transition-colors"
          >
            <div class="flex items-center gap-4">
              <div
                class="bg-background flex size-12 items-center justify-center rounded-2xl shadow-sm"
              >
                <i class="ph ph-user-circle text-primary text-2xl"></i>
              </div>
              <span class="text-foreground text-sm font-black tracking-tight">Default Avatar</span>
            </div>
            <button
              type="button"
              wire:click="$toggle('use_ui_avatar')"
              @class ([
                'relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2',
                'bg-primary' => $use_ui_avatar,
                'bg-border' => !$use_ui_avatar
              ])
            >
              <span
                aria-hidden="true"
                @class ([
                  'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out',
                  'translate-x-5' => $use_ui_avatar,
                  'translate-x-0' => !$use_ui_avatar
                ])
              ></span>
            </button>
          </div>

          @error ('photo')
            <p class="text-destructive text-center text-xs font-bold">{{ $message }}</p>
          @enderror
        </div>
      </div>
    </div>
  </div>

  <!-- Right Column: Account Details -->
  <div class="lg:col-span-2">
    <form
      wire:submit="save"
      class="bg-card border-border space-y-8 rounded-3xl border p-8 shadow-sm"
    >
      <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
        <!-- Name Field -->
        <div class="space-y-2">
          <label
            for="name"
            class="text-muted-foreground text-[10px] font-black tracking-[0.2em] uppercase"
            >Full Name</label
          >
          <div class="relative">
            <span class="text-muted-foreground absolute inset-y-0 left-5 flex items-center">
              <i class="ph ph-user text-xl"></i>
            </span>
            <input
              type="text"
              id="name"
              wire:model="name"
              class="border-border bg-muted/30 focus:border-primary focus:bg-background focus:ring-primary/10 w-full rounded-2xl py-3.5 pr-5 pl-12 text-sm font-bold transition-all focus:ring-4"
              placeholder="John Doe"
            />
          </div>
          @error ('name')
            <span class="text-destructive text-xs font-bold">{{ $message }}</span>
          @enderror
        </div>

        <!-- Email Field -->
        <div class="space-y-2">
          <label
            for="email"
            class="text-muted-foreground text-[10px] font-black tracking-[0.2em] uppercase"
            >Email Address</label
          >
          <div class="relative">
            <span class="text-muted-foreground absolute inset-y-0 left-5 flex items-center">
              <i class="ph ph-envelope text-xl"></i>
            </span>
            <input
              type="email"
              id="email"
              wire:model="email"
              class="border-border bg-muted/30 focus:border-primary focus:bg-background focus:ring-primary/10 w-full rounded-2xl py-3.5 pr-5 pl-12 text-sm font-bold transition-all focus:ring-4"
              placeholder="john@example.com"
            />
          </div>
          @error ('email')
            <span class="text-destructive text-xs font-bold">{{ $message }}</span>
          @enderror
        </div>

        <!-- Role Field (Admin Only) -->
        @if (auth()->user()->role === 'admin')
          <div class="space-y-2">
            <label
              for="role"
              class="text-muted-foreground text-[10px] font-black tracking-[0.2em] uppercase"
              >Account Role</label
            >
            <div class="relative">
              <span class="text-muted-foreground absolute inset-y-0 left-5 flex items-center">
                <i class="ph ph-shield-check text-xl"></i>
              </span>
              <select
                id="role"
                wire:model="role"
                @disabled ($user->role === 'admin')
                class="border-border bg-muted/30 focus:border-primary focus:bg-background focus:ring-primary/10 w-full appearance-none rounded-2xl py-3.5 pr-5 pl-12 text-sm font-bold transition-all focus:ring-4 disabled:opacity-50"
              >
                <option value="reader">Reader</option>
                <option value="writter">Writer</option>
                @if ($user->role === 'admin')
                  <option value="admin">Administrator</option>
                @endif
              </select>
            </div>
            @error ('role')
              <span class="text-destructive text-xs font-bold">{{ $message }}</span>
            @enderror
          </div>
        @endif

        <!-- Password Field -->
        <div class="space-y-2">
          <label
            for="password"
            class="text-muted-foreground text-[10px] font-black tracking-[0.2em] uppercase"
            >New Password</label
          >
          <div class="relative">
            <span class="text-muted-foreground absolute inset-y-0 left-5 flex items-center">
              <i class="ph ph-lock text-xl"></i>
            </span>
            <input
              type="password"
              id="password"
              wire:model="password"
              class="border-border bg-muted/30 focus:border-primary focus:bg-background focus:ring-primary/10 w-full rounded-2xl py-3.5 pr-5 pl-12 text-sm font-bold transition-all focus:ring-4"
              placeholder="Leave blank to keep current"
            />
          </div>
          @error ('password')
            <span class="text-destructive text-xs font-bold">{{ $message }}</span>
          @enderror
        </div>
      </div>

      <div class="flex items-center justify-end pt-4">
        <button
          type="submit"
          wire:loading.attr="disabled"
          wire:target="save"
          class="group bg-primary text-primary-foreground relative flex items-center justify-center gap-3 overflow-hidden rounded-2xl px-8 py-4 text-sm font-black tracking-widest uppercase shadow-xl transition-all hover:scale-[1.02] active:scale-95 disabled:opacity-70"
        >
          <div wire:loading.remove wire:target="save" class="flex items-center gap-3">
            <span>Update Profile</span>
            <i class="ph ph-arrow-right text-lg transition-transform group-hover:translate-x-1"></i>
          </div>

          <div wire:loading.flex wire:target="save" class="flex items-center gap-3">
            <div
              class="h-5 w-5 animate-spin rounded-full border-2 border-white border-t-transparent"
            ></div>
            <span>Processing...</span>
          </div>
        </button>
      </div>
    </form>
  </div>
</div>

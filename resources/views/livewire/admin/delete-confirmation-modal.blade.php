<div
  x-data="{ show: @entangle('show') }"
  x-show="show"
  x-on:keydown.escape.window="show = false"
  class="fixed inset-0 z-[1000] overflow-y-auto"
  style="display: none"
>
  <!-- Backdrop -->
  <div
    x-show="show"
    x-transition:enter="ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="bg-background/95 fixed inset-0 backdrop-blur-2xl"
    @click="show = false"
  ></div>

  <!-- Modal -->
  <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
    <div
      x-show="show"
      x-transition:enter="ease-out duration-300"
      x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
      x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
      x-transition:leave="ease-in duration-200"
      x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
      x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
      class="bg-card ring-border relative inline-block transform overflow-hidden rounded-[2.5rem] text-left align-middle shadow-2xl ring-1 transition-all sm:my-8 sm:w-full sm:max-w-lg"
    >
      <div class="p-8 sm:p-10">
        <div class="mb-8 flex items-center gap-5">
          <div
            class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-red-500/10 text-red-500"
          >
            <i class="ph ph-trash text-3xl"></i>
          </div>
          <div>
            <h3 class="text-foreground text-2xl font-black">{{ $title }}</h3>
            <p class="mt-1 text-xs font-black tracking-widest text-red-500 uppercase">Dangerous Action</p>
          </div>
        </div>

        <p class="text-muted-foreground mb-10 leading-relaxed font-medium">{{ $message }}</p>

        <div class="flex flex-col gap-4 sm:flex-row">
          <button
            type="button"
            wire:click="close"
            class="bg-muted text-foreground hover:bg-muted/80 flex-1 rounded-2xl px-8 py-4 text-sm font-bold transition-all active:scale-95"
          >
            Keep it
          </button>
          <button
            type="button"
            wire:click="confirm"
            class="group relative flex flex-1 items-center justify-center gap-2 overflow-hidden rounded-2xl bg-red-500 px-8 py-4 text-sm font-bold text-white shadow-lg shadow-red-500/20 transition-all hover:bg-red-600 active:scale-95"
          >
            <span wire:loading.remove wire:target="confirm">Delete Now</span>
            <span wire:loading wire:target="confirm">Processing...</span>
            <i
              class="ph ph-trash text-lg group-hover:animate-bounce"
              wire:loading.remove
              wire:target="confirm"
            ></i>
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="grid grid-cols-1 gap-6 md:grid-cols-3">
  <!-- Total Attempts -->
  <div
    class="bg-card ring-border group relative overflow-hidden rounded-[2rem] p-8 shadow-sm ring-1 transition-all hover:-translate-y-1 hover:shadow-lg"
  >
    <div class="flex items-center gap-6">
      <div
        class="flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-500/10 text-blue-500 transition-transform group-hover:scale-110"
      >
        <i class="ph ph-fingerprint text-3xl font-bold"></i>
      </div>
      <div>
        <p class="text-muted-foreground text-[10px] font-black tracking-[0.2em] uppercase">Total Attempts</p>
        <h4 class="text-foreground mt-1 text-3xl font-black tabular-nums">
          {{ number_format($totalData) }}
        </h4>
      </div>
    </div>
  </div>

  <!-- Success -->
  <div
    class="bg-card ring-border group relative overflow-hidden rounded-[2rem] p-8 shadow-sm ring-1 transition-all hover:-translate-y-1 hover:shadow-lg"
  >
    <div class="flex items-center gap-6">
      <div
        class="flex h-16 w-16 items-center justify-center rounded-2xl bg-green-500/10 text-green-500 transition-transform group-hover:scale-110"
      >
        <i class="ph ph-shield-check text-3xl font-bold"></i>
      </div>
      <div>
        <p class="text-muted-foreground text-[10px] font-black tracking-[0.2em] uppercase">Successful</p>
        <h4 class="text-foreground mt-1 text-3xl font-black tabular-nums">
          {{ number_format($loginSuccess) }}
        </h4>
      </div>
    </div>
  </div>

  <!-- Failed -->
  <div
    class="bg-card ring-border group relative overflow-hidden rounded-[2rem] p-8 shadow-sm ring-1 transition-all hover:-translate-y-1 hover:shadow-lg"
  >
    <div class="flex items-center gap-6">
      <div
        class="flex h-16 w-16 items-center justify-center rounded-2xl bg-red-500/10 text-red-500 transition-transform group-hover:scale-110"
      >
        <i class="ph ph-shield-warning text-3xl font-bold"></i>
      </div>
      <div>
        <p class="text-muted-foreground text-[10px] font-black tracking-[0.2em] uppercase">Failed/Blocked</p>
        <h4 class="text-foreground mt-1 text-3xl font-black tabular-nums">
          {{ number_format($loginFailed) }}
        </h4>
      </div>
    </div>
  </div>
</div>

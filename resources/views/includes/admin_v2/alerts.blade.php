@if (session()->has('success'))
  <div
    x-data="{ show: true }"
    x-show="show"
    class="mb-6 flex items-center justify-between rounded-2xl border border-green-500/20 bg-green-500/10 p-4 text-green-600 shadow-sm transition-all duration-300"
  >
    <div class="flex items-center gap-3">
      <i class="ph ph-check-circle text-xl"></i>
      <span class="text-sm font-bold">{{ session('success') }}</span>
    </div>
    <button @click="show = false" class="text-green-600 transition-colors hover:text-green-800">
      <i class="ph ph-x font-bold"></i>
    </button>
  </div>
@elseif (session()->has('danger'))
  <div
    x-data="{ show: true }"
    x-show="show"
    class="mb-6 flex items-center justify-between rounded-2xl border border-red-500/20 bg-red-500/10 p-4 text-red-600 shadow-sm transition-all duration-300"
  >
    <div class="flex items-center gap-3">
      <i class="ph ph-warning-circle text-xl"></i>
      <span class="text-sm font-bold">{{ session('danger') }}</span>
    </div>
    <button @click="show = false" class="text-red-600 transition-colors hover:text-red-800">
      <i class="ph ph-x font-bold"></i>
    </button>
  </div>
@elseif (session()->has('warning'))
  <div
    x-data="{ show: true }"
    x-show="show"
    class="mb-6 flex items-center justify-between rounded-2xl border border-amber-500/20 bg-amber-500/10 p-4 text-amber-600 shadow-sm transition-all duration-300"
  >
    <div class="flex items-center gap-3">
      <i class="ph ph-warning text-xl"></i>
      <span class="text-sm font-bold">{{ session('warning') }}</span>
    </div>
    <button @click="show = false" class="text-amber-600 transition-colors hover:text-amber-800">
      <i class="ph ph-x font-bold"></i>
    </button>
  </div>
@elseif (session()->has('info'))
  <div
    x-data="{ show: true }"
    x-show="show"
    class="mb-6 flex items-center justify-between rounded-2xl border border-blue-500/20 bg-blue-500/10 p-4 text-blue-600 shadow-sm transition-all duration-300"
  >
    <div class="flex items-center gap-3">
      <i class="ph ph-info text-xl"></i>
      <span class="text-sm font-bold">{{ session('info') }}</span>
    </div>
    <button @click="show = false" class="text-blue-600 transition-colors hover:text-blue-800">
      <i class="ph ph-x font-bold"></i>
    </button>
  </div>
@endif

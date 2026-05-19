<x-guest-layout title="Login">
  <div class="mb-10 text-center lg:text-left">
    <h2 class="text-foreground mb-3 text-3xl font-black tracking-tight">Welcome Back.</h2>
    <p class="text-muted-foreground font-medium">Log in to your account to continue.</p>
  </div>

  <!-- Session Status -->
  <x-auth-session-status
    class="mb-6 rounded-2xl border border-green-100 bg-green-50 p-4 text-sm font-bold text-green-600"
    :status="session('status')"
  />

  <form method="POST" action="{{ route('login') }}" class="space-y-6">
    @csrf

    <!-- Email Address -->
    <div class="space-y-2">
      <x-input-label for="email" :value="__('Email Address')" />
      <div class="group relative">
        <div
          class="text-muted-foreground group-focus-within:text-primary absolute top-1/2 left-5 -translate-y-1/2 transition-colors"
        >
          <i class="ph ph-envelope-simple text-xl"></i>
        </div>
        <x-text-input
          id="email"
          class="pl-12"
          type="email"
          name="email"
          :value="old('email')"
          required
          autofocus
          placeholder="name@example.com"
        />
      </div>
      <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    <!-- Password -->
    <div class="space-y-2">
      <div class="flex items-center justify-between">
        <x-input-label for="password" :value="__('Password')" />
        @if (\Illuminate\Support\Facades\Route::has('password.request'))
          <a
            class="text-primary text-xs font-bold tracking-widest uppercase hover:underline"
            href="{{ route('password.request') }}"
          >
            {{ __('Forgot?') }}
          </a>
        @endif
      </div>
      <div class="group relative">
        <div
          class="text-muted-foreground group-focus-within:text-primary absolute top-1/2 left-5 -translate-y-1/2 transition-colors"
        >
          <i class="ph ph-lock-simple text-xl"></i>
        </div>
        <x-text-input
          id="password"
          class="pl-12"
          type="password"
          name="password"
          required
          placeholder="••••••••"
        />
      </div>
      <x-input-error :messages="$errors->get('password')" class="mt-2" />
    </div>

    <!-- Remember Me -->
    <div class="flex items-center justify-between">
      <label for="remember_me" class="group inline-flex cursor-pointer items-center">
        <div class="relative flex items-center">
          <input
            id="remember_me"
            type="checkbox"
            class="peer border-border bg-background text-primary focus:ring-primary focus:ring-offset-background checked:bg-primary checked:border-primary h-5 w-5 cursor-pointer appearance-none rounded-md border-2 transition-all"
            name="remember"
          />
          <i
            class="ph ph-check pointer-events-none absolute left-1 text-xs text-white opacity-0 transition-opacity peer-checked:opacity-100"
          ></i>
        </div>
        <span
          class="text-muted-foreground group-hover:text-foreground ms-3 text-sm font-bold transition-colors"
          >{{ __('Keep me logged in') }}</span
        >
      </label>
    </div>

    <div class="pt-2">
      <x-primary-button class="h-14 w-full"> {{ __('Sign In') }} </x-primary-button>
    </div>

    <div class="mt-8 text-center">
      <p class="text-muted-foreground text-sm font-medium">
        Don't have an account?
        <a href="{{ route('register') }}" class="text-primary font-bold hover:underline"
          >Create one for free</a
        >
      </p>
    </div>
  </form>

  @section ('page-scripts')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
  @endsection

  @include ('includes.toast')
</x-guest-layout>

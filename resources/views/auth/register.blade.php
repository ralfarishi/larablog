<x-guest-layout title="Join Us">
  <div class="mb-10 text-center lg:text-left">
    <h2 class="text-foreground mb-3 text-3xl font-black tracking-tight">Join the Community.</h2>
    <p class="text-muted-foreground font-medium">Create an account to start sharing your stories.</p>
  </div>

  <form method="POST" action="{{ route('register') }}" class="space-y-6">
    @csrf

    <!-- Name -->
    <div class="space-y-2">
      <x-input-label for="name" :value="__('Full Name')" />
      <div class="group relative">
        <div
          class="text-muted-foreground group-focus-within:text-primary absolute top-1/2 left-5 -translate-y-1/2 transition-colors"
        >
          <i class="ph ph-user text-xl"></i>
        </div>
        <x-text-input
          id="name"
          class="pl-12"
          type="text"
          name="name"
          :value="old('name')"
          required
          autofocus
          placeholder="John Doe"
        />
      </div>
      <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

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
          placeholder="name@example.com"
        />
      </div>
      <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    <!-- Password -->
    <div class="space-y-2">
      <x-input-label for="password" :value="__('Create Password')" />
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

    <!-- Confirm Password -->
    <div class="space-y-2">
      <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
      <div class="group relative">
        <div
          class="text-muted-foreground group-focus-within:text-primary absolute top-1/2 left-5 -translate-y-1/2 transition-colors"
        >
          <i class="ph ph-lock-simple-open text-xl"></i>
        </div>
        <x-text-input
          id="password_confirmation"
          class="pl-12"
          type="password"
          name="password_confirmation"
          required
          placeholder="••••••••"
        />
      </div>
      <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
    </div>

    <div class="pt-4">
      <x-primary-button class="h-14 w-full"> {{ __('Create Account') }} </x-primary-button>
    </div>

    <div class="mt-8 text-center">
      <p class="text-muted-foreground text-sm font-medium">
        Already have an account?
        <a href="{{ route('login') }}" class="text-primary font-bold hover:underline"
          >Sign In instead</a
        >
      </p>
    </div>
  </form>
</x-guest-layout>

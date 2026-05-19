@extends ('layouts.admin_v2.template')

@section ('page-title', 'Create User')

@section ('content')
  <div class="space-y-8 pb-20">
    <!-- Header Section -->
    <div class="flex flex-col justify-between gap-6 sm:flex-row sm:items-center">
      <div>
        <nav class="mb-2 flex" aria-label="Breadcrumb">
          <ol
            class="text-muted-foreground flex items-center space-x-2 text-xs font-bold tracking-widest uppercase"
          >
            <li>
              <a href="{{ route('dashboard') }}" class="hover:text-primary transition-colors"
                >Dashboard</a
              >
            </li>
            <li><i class="ph ph-caret-right text-[10px]"></i></li>
            <li>
              <a href="{{ route('user.index') }}" class="hover:text-primary transition-colors"
                >Users</a
              >
            </li>
            <li><i class="ph ph-caret-right text-[10px]"></i></li>
            <li class="text-foreground">Create</li>
          </ol>
        </nav>
        <h3 class="text-foreground text-3xl font-black tracking-tight">
          Add New <span class="text-primary">User.</span>
        </h3>
      </div>
      <a
        href="{{ route('user.index') }}"
        class="bg-muted text-foreground hover:bg-muted/80 inline-flex items-center gap-2 rounded-full px-6 py-3 text-sm font-bold transition-all"
      >
        <i class="ph ph-arrow-left"></i>
        Back to Users
      </a>
    </div>

    @include ('includes.admin_v2.alerts')

    <form action="{{ route('user.store') }}" method="POST" class="max-w-3xl">
      @csrf
      <div class="bg-card ring-border rounded-[2.5rem] p-8 shadow-sm ring-1 sm:p-12">
        <h4 class="text-foreground mb-10 flex items-center gap-3 text-lg font-black">
          <i class="ph ph-user-circle text-primary text-2xl"></i>
          Account Information
        </h4>

        <div class="space-y-6">
          <!-- Name -->
          <div class="space-y-2">
            <x-input-label for="name" :value="__('Full Name')" />
            <x-text-input
              id="name"
              name="name"
              type="text"
              :value="old('name')"
              required
              placeholder="Enter full name"
            />
            <x-input-error :messages="$errors->get('name')" />
          </div>

          <!-- Email -->
          <div class="space-y-2">
            <x-input-label for="email" :value="__('Email Address')" />
            <x-text-input
              id="email"
              name="email"
              type="email"
              :value="old('email')"
              required
              placeholder="user@example.com"
            />
            <x-input-error :messages="$errors->get('email')" />
          </div>

          <!-- Role -->
          <div class="space-y-2">
            <x-input-label for="role" :value="__('Role')" />
            <div class="group relative">
              <select
                name="role"
                id="role"
                class="border-border bg-background text-foreground focus:border-primary focus:ring-primary/20 flex w-full cursor-pointer appearance-none rounded-2xl border-2 px-5 py-3.5 text-base transition-all focus:ring-4 focus:outline-none"
              >
                <option value="writter">Writer</option>
                <option value="reader">Reader</option>
              </select>
              <div
                class="text-muted-foreground group-focus-within:text-primary pointer-events-none absolute top-1/2 right-5 -translate-y-1/2"
              >
                <i class="ph ph-caret-down text-sm"></i>
              </div>
            </div>
            <x-input-error :messages="$errors->get('role')" />
          </div>

          <!-- Password -->
          <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div class="space-y-2">
              <x-input-label for="password" :value="__('Password')" />
              <x-text-input
                id="password"
                name="password"
                type="password"
                required
                placeholder="Min. 8 characters"
              />
              <x-input-error :messages="$errors->get('password')" />
            </div>
            <div class="space-y-2">
              <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
              <x-text-input
                id="password_confirmation"
                name="password_confirmation"
                type="password"
                required
                placeholder="Min. 8 characters"
              />
              <x-input-error :messages="$errors->get('password_confirmation')" />
            </div>
          </div>

          <div class="flex justify-end pt-6">
            <x-primary-button class="h-14 px-12"> {{ __('Create User') }} </x-primary-button>
          </div>
        </div>
      </div>
    </form>
  </div>
@endsection

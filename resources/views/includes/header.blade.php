<header
  id="header"
  class="bg-background/95 border-border fixed inset-x-0 top-0 z-50 border-b backdrop-blur-xl transition-all duration-300"
>
  <div
    class="mx-auto flex h-20 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8"
    x-data="{ mobileMenuOpen: false }"
  >
    <!-- Brand Logo -->
    <a href="{{ route('home') }}" class="group flex items-center gap-2">
      <h1
        class="text-foreground group-hover:text-primary font-sans text-2xl font-black tracking-tighter transition-colors"
      >
        Lara<span class="text-primary">Blog.</span>
      </h1>
    </a>

    <!-- Mobile Menu Button -->
    <button
      @click="mobileMenuOpen = !mobileMenuOpen"
      class="bg-card ring-border text-foreground hover:text-primary flex h-10 w-10 items-center justify-center rounded-full ring-1 transition-colors focus:outline-none lg:hidden"
    >
      <i x-show="!mobileMenuOpen" class="ph ph-list text-xl"></i>
      <i x-show="mobileMenuOpen" style="display: none" class="ph ph-x text-xl"></i>
    </button>

    <!-- Desktop Nav -->
    <nav class="hidden items-center gap-8 lg:flex">
      <ul class="flex items-center gap-4 text-sm font-bold tracking-widest uppercase">
        @guest
          <li>
            <a
              href="{{ route('login') }}"
              class="bg-foreground text-background inline-flex h-10 items-center justify-center rounded-full px-6 shadow-sm transition-transform hover:-translate-y-0.5 hover:shadow-lg"
            >
              Login
            </a>
          </li>
        @endguest

        @auth
          @if (auth()->user()->role !== 'reader')
            <!-- Notifications Dropdown -->
            <li>
              <livewire:layout.notification-dropdown />
            </li>
            <!-- User Menu -->
            <li class="relative" x-data="{ open: false }">
              <button
                @click="open = !open"
                @click.outside="open = false"
                class="bg-card ring-border hover:ring-primary/50 flex items-center gap-3 rounded-full py-1.5 pr-4 pl-2 ring-1 transition-all focus:outline-none"
              >
                <div class="h-8 w-8 shrink-0 overflow-hidden rounded-full">
                  <img
                    src="{{ auth()->user()->profile_picture_url }}"
                    alt="Avatar"
                    class="h-full w-full object-cover"
                  />
                </div>
                <span
                  class="text-foreground text-sm font-bold capitalize"
                  >{{ auth()->user()->name }}</span
                >
                <i class="ph ph-caret-down text-muted-foreground"></i>
              </button>

              <div
                x-show="open"
                style="display: none"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                class="bg-card ring-border absolute right-0 z-50 mt-4 w-56 overflow-hidden rounded-3xl p-2 shadow-xl ring-1 focus:outline-none"
              >
                <a
                  href="{{ route('dashboard') }}"
                  class="text-foreground hover:bg-muted hover:text-primary flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-bold transition-colors"
                >
                  <i class="ph ph-squares-four text-lg"></i>
                  Dashboard
                </a>
                <a
                  href="{{ route('bookmark.index') }}"
                  class="text-foreground hover:bg-muted hover:text-primary flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-bold transition-colors"
                >
                  <i class="ph ph-bookmark-simple text-lg"></i>
                  My Bookmarks
                </a>
                <div class="bg-border mx-2 my-1 h-px"></div>
                <a
                  href="{{ route('logout') }}"
                  onclick="
                    event.preventDefault();
                    document.getElementById('logout-form').submit();
                  "
                  class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-bold text-red-500 transition-colors hover:bg-red-50"
                >
                  <i class="ph ph-sign-out text-lg"></i>
                  Sign Out
                </a>
              </div>
            </li>
          @else
            <li>
              <a
                href="{{ route('bookmark.index') }}"
                class="text-muted-foreground hover:text-primary text-sm font-bold transition-colors"
                >Bookmarks</a
              >
            </li>
            <li>
              <a
                href="{{ route('logout') }}"
                onclick="
                  event.preventDefault();
                  document.getElementById('logout-form').submit();
                "
                class="text-muted-foreground hover:text-primary text-sm font-bold transition-colors"
                >Sign Out</a
              >
            </li>
          @endif
        @endauth
      </ul>
    </nav>

    <!-- Mobile Nav Overlay -->
    <div
      x-show="mobileMenuOpen"
      style="display: none"
      x-transition:enter="transition ease-out duration-300"
      x-transition:enter-start="opacity-0 -translate-y-4"
      x-transition:enter-end="opacity-100 translate-y-0"
      x-transition:leave="transition ease-in duration-200"
      x-transition:leave-start="opacity-100 translate-y-0"
      x-transition:leave-end="opacity-0 -translate-y-4"
      class="bg-background/95 border-border absolute top-full right-0 left-0 z-40 flex flex-col gap-6 rounded-b-[2.5rem] border-b p-6 shadow-2xl backdrop-blur-xl lg:hidden"
    >
      <ul class="flex flex-col gap-4 text-sm font-bold tracking-widest uppercase">
        @guest
          <li>
            <a
              href="{{ route('home') }}"
              class="flex items-center gap-3 p-4 rounded-2xl bg-card ring-1 ring-border transition-colors hover:ring-primary @if(request()->segment(1) == '') text-primary @else text-foreground @endif"
              ><i class="ph ph-article text-xl"></i> Journal</a
            >
          </li>
          <li>
            <a
              href="{{ route('login') }}"
              class="bg-foreground text-background flex items-center justify-center rounded-2xl p-4 shadow-md transition-colors"
              >Sign In</a
            >
          </li>
        @endguest
        @auth
          @if (auth()->user()->role !== 'reader')
            <li>
              <a
                href="{{ route('dashboard') }}"
                class="bg-card ring-border text-foreground hover:ring-primary flex items-center gap-3 rounded-2xl p-4 ring-1 transition-colors"
                ><i class="ph ph-squares-four text-xl"></i> Dashboard</a
              >
            </li>
            <li>
              <a
                href="{{ route('bookmark.index') }}"
                class="bg-card ring-border text-foreground hover:ring-primary flex items-center gap-3 rounded-2xl p-4 ring-1 transition-colors"
                ><i class="ph ph-bookmark-simple text-xl"></i> My Bookmarks</a
              >
            </li>
            <li>
              <a
                href="{{ route('logout') }}"
                onclick="
                  event.preventDefault();
                  document.getElementById('logout-form').submit();
                "
                class="flex items-center gap-3 rounded-2xl bg-red-50 p-4 text-red-600 transition-colors"
                ><i class="ph ph-sign-out text-xl"></i> Sign Out</a
              >
            </li>
          @else
            <li>
              <a
                href="{{ route('bookmark.index') }}"
                class="bg-card ring-border text-foreground hover:ring-primary flex items-center gap-3 rounded-2xl p-4 ring-1 transition-colors"
                ><i class="ph ph-bookmark-simple text-xl"></i> My Bookmarks</a
              >
            </li>
            <li>
              <a
                href="{{ route('logout') }}"
                onclick="
                  event.preventDefault();
                  document.getElementById('logout-form').submit();
                "
                class="flex items-center gap-3 rounded-2xl bg-red-50 p-4 text-red-600 transition-colors"
                ><i class="ph ph-sign-out text-xl"></i> Sign Out</a
              >
            </li>
          @endif
        @endauth
      </ul>
    </div>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
  </div>
</header>

<!-- Sidebar Mobile Overlay -->
<div
  x-show="sidebarOpen"
  x-transition:enter="transition-opacity ease-linear duration-300"
  x-transition:enter-start="opacity-0"
  x-transition:enter-end="opacity-100"
  x-transition:leave="transition-opacity ease-linear duration-300"
  x-transition:leave-start="opacity-100"
  x-transition:leave-end="opacity-0"
  class="bg-background/80 fixed inset-0 z-40 backdrop-blur-sm lg:hidden"
  @click="sidebarOpen = false"
  style="display: none"
></div>

<!-- Sidebar Sidebar -->
<aside
  class="bg-card border-border fixed inset-y-0 left-0 z-50 flex h-screen w-72 transform flex-col border-r transition-all duration-300 lg:translate-x-0"
  :class="{
    'translate-x-0': sidebarOpen,
    '-translate-x-full': !sidebarOpen,
    'w-72': !sidebarCollapsed || isMobile,
    'w-24': sidebarCollapsed && !isMobile,
  }"
>
  <!-- Sidebar Header -->
  <div
    class="border-border flex h-20 shrink-0 items-center justify-between border-b px-4 transition-all duration-300"
  >
    <a href="/" class="group flex items-center gap-3">
      <div
        class="bg-primary text-primary-foreground shadow-primary/20 flex h-10 w-10 shrink-0 items-center justify-center rounded-xl shadow-lg"
      >
        <i class="ph-bold ph-graduation-cap text-2xl"></i>
      </div>
      <h1
        class="text-foreground group-hover:text-primary text-xl font-black tracking-tighter whitespace-nowrap transition-all duration-300"
        x-show="!sidebarCollapsed || isMobile"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-x-4"
        x-transition:enter-end="opacity-100 translate-x-0"
      >
        Lara<span class="text-primary">Blog.</span>
      </h1>
    </a>

    <!-- Collapse Toggle (Desktop) - At boundary between sidebar and main content -->
    <button
      @click="sidebarCollapsed = !sidebarCollapsed"
      class="bg-muted text-muted-foreground hover:text-primary border-border bg-card ring-background absolute z-[100] hidden h-8 w-8 items-center justify-center rounded-lg border shadow-lg ring-4 transition-all duration-300 lg:flex"
      style="top: 1.5rem; right: -1rem"
    >
      <i
        class="ph-bold ph-caret-double-right toggle-icon-collapsed hidden transition-transform duration-300"
        x-show="sidebarCollapsed"
      ></i>
      <i
        class="ph-bold ph-caret-double-left toggle-icon-expanded transition-transform duration-300"
        x-show="!sidebarCollapsed"
      ></i>
    </button>

    <!-- Close Button (Mobile) -->
    <button
      @click="sidebarOpen = false"
      class="bg-muted text-foreground hover:text-primary flex h-8 w-8 items-center justify-center rounded-full transition-colors lg:hidden"
    >
      <i class="ph ph-x text-lg"></i>
    </button>
  </div>

  <!-- Navigation Menu -->
  <div class="no-scrollbar flex flex-1 flex-col overflow-y-auto p-4" data-lenis-prevent>
    <ul class="flex-1 space-y-2">
      <li
        class="px-4 pb-2 transition-opacity duration-300"
        :class="sidebarCollapsed && !isMobile ? 'opacity-0 h-0 overflow-hidden' : 'opacity-100'"
      >
        <span
          class="text-muted-foreground/60 text-[10px] font-black tracking-[0.2em] whitespace-nowrap uppercase"
          >Main Menu</span
        >
      </li>

      <li>
        <a
          href="{{ route('dashboard') }}"
          class="flex items-center gap-4 px-4 py-3.5 rounded-2xl font-bold text-sm transition-all group {{ request()->routeIs('dashboard') ? 'bg-primary text-primary-foreground shadow-lg shadow-primary/20' : 'text-muted-foreground hover:bg-muted hover:text-foreground' }}"
          :class="sidebarCollapsed && !isMobile ? 'justify-center px-0' : ''"
          title="Dashboard"
        >
          <i
            class="ph ph-squares-four text-xl {{ request()->routeIs('dashboard') ? '' : 'group-hover:scale-110 transition-transform' }}"
          ></i>
          <span
            x-show="!sidebarCollapsed || isMobile"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-x-4"
            x-transition:enter-end="opacity-100 translate-x-0"
            class="whitespace-nowrap"
            >Dashboard</span
          >
        </a>
      </li>

      <li
        class="px-4 pt-6 pb-2 transition-opacity duration-300"
        :class="sidebarCollapsed && !isMobile ? 'opacity-0 h-0 overflow-hidden' : 'opacity-100'"
      >
        <span
          class="text-muted-foreground/60 text-[10px] font-black tracking-[0.2em] whitespace-nowrap uppercase"
          >Features</span
        >
      </li>

      <!-- Articles -->
      <li x-data="{ open: {{ request()->routeIs('article*') ? 'true' : 'false' }} }">
        <button
          @click="sidebarCollapsed && !isMobile ? (sidebarCollapsed = false) : (open = !open)"
          class="w-full flex items-center justify-between gap-4 px-4 py-3.5 rounded-2xl font-bold text-sm transition-all group {{ request()->routeIs('article*') ? 'bg-muted text-foreground' : 'text-muted-foreground hover:bg-muted hover:text-foreground' }}"
          :class="sidebarCollapsed && !isMobile ? 'justify-center px-0' : ''"
          title="Articles"
        >
          <div class="flex items-center gap-4">
            <i class="ph ph-newspaper text-xl"></i>
            <span
              x-show="!sidebarCollapsed || isMobile"
              x-transition:enter="transition ease-out duration-300"
              x-transition:enter-start="opacity-0 -translate-x-4"
              x-transition:enter-end="opacity-100 translate-x-0"
              class="whitespace-nowrap"
              >Articles</span
            >
          </div>
          <i
            x-show="!sidebarCollapsed || isMobile"
            class="ph ph-caret-down text-xs transition-transform duration-200"
            :class="open ? 'rotate-180' : ''"
          ></i>
        </button>
        <div
          x-show="open && (!sidebarCollapsed || isMobile)"
          x-collapse
          style="display: none"
          class="mt-2 ml-4 space-y-1"
        >
          <a
            href="{{ route('article.index') }}"
            class="block px-8 py-2.5 rounded-xl text-sm font-bold {{ request()->routeIs('article.index') ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}"
          >
            Article List
          </a>
          <a
            href="{{ route('article.create') }}"
            class="block px-8 py-2.5 rounded-xl text-sm font-bold {{ request()->routeIs('article.create') ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}"
          >
            Create New
          </a>
        </div>
      </li>

      <li>
        <a
          href="{{ route('comment.index') }}"
          class="flex items-center gap-4 px-4 py-3.5 rounded-2xl font-bold text-sm transition-all group {{ request()->routeIs('comment.index') ? 'bg-muted text-foreground' : 'text-muted-foreground hover:bg-muted hover:text-foreground' }}"
          :class="sidebarCollapsed && !isMobile ? 'justify-center px-0' : ''"
          title="Comments"
        >
          <i class="ph ph-chat-circle-dots text-xl"></i>
          <span
            x-show="!sidebarCollapsed || isMobile"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-x-4"
            x-transition:enter-end="opacity-100 translate-x-0"
            class="whitespace-nowrap"
            >Comments</span
          >
        </a>
      </li>

      @if (auth()->user()->role == 'admin')
        <!-- Categories -->
        <li>
          <a
            href="{{ route('category.index') }}"
            class="flex items-center gap-4 px-4 py-3.5 rounded-2xl font-bold text-sm transition-all group {{ request()->routeIs('category.index') ? 'bg-muted text-foreground' : 'text-muted-foreground hover:bg-muted hover:text-foreground' }}"
            :class="sidebarCollapsed && !isMobile ? 'justify-center px-0' : ''"
            title="Categories"
          >
            <i class="ph ph-bookmarks text-xl"></i>
            <span
              x-show="!sidebarCollapsed || isMobile"
              x-transition:enter="transition ease-out duration-300"
              x-transition:enter-start="opacity-0 -translate-x-4"
              x-transition:enter-end="opacity-100 translate-x-0"
              class="whitespace-nowrap"
              >Categories</span
            >
          </a>
        </li>
        <!-- User Control -->
        <li
          x-data="{ open: {{ request()->routeIs('user*') || request()->routeIs('login-history*') ? 'true' : 'false' }} }"
        >
          <button
            @click="sidebarCollapsed && !isMobile ? (sidebarCollapsed = false) : (open = !open)"
            class="w-full flex items-center justify-between gap-4 px-4 py-3.5 rounded-2xl font-bold text-sm transition-all group {{ request()->routeIs('user*') || request()->routeIs('login-history*') ? 'bg-muted text-foreground' : 'text-muted-foreground hover:bg-muted hover:text-foreground' }}"
            :class="sidebarCollapsed && !isMobile ? 'justify-center px-0' : ''"
            title="User Control"
          >
            <div class="flex items-center gap-4">
              <i class="ph ph-users text-xl"></i>
              <span
                x-show="!sidebarCollapsed || isMobile"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 -translate-x-4"
                x-transition:enter-end="opacity-100 translate-x-0"
                class="whitespace-nowrap"
                >User Control</span
              >
            </div>
            <i
              x-show="!sidebarCollapsed || isMobile"
              class="ph ph-caret-down text-xs transition-transform duration-200"
              :class="open ? 'rotate-180' : ''"
            ></i>
          </button>
          <div
            x-show="open && (!sidebarCollapsed || isMobile)"
            x-collapse
            style="display: none"
            class="mt-2 ml-4 space-y-1"
          >
            <a
              href="{{ route('user.index') }}"
              class="block px-8 py-2.5 rounded-xl text-sm font-bold {{ request()->routeIs('user.index') ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}"
            >
              User List
            </a>
            <a
              href="{{ route('login-history.index') }}"
              class="block px-8 py-2.5 rounded-xl text-sm font-bold {{ request()->routeIs('login-history.index') ? 'text-primary' : 'text-muted-foreground hover:text-foreground' }}"
            >
              Login History
            </a>
          </div>
        </li>
        {{-- Analytics --}}
        <li>
          <a
            href="{{ route('analytics.index') }}"
            class="flex items-center gap-4 px-4 py-3.5 rounded-2xl font-bold text-sm transition-all group {{ request()->routeIs('analytics.*') ? 'bg-primary text-primary-foreground shadow-lg shadow-primary/20' : 'text-muted-foreground hover:bg-muted hover:text-foreground' }}"
            :class="sidebarCollapsed && !isMobile ? 'justify-center px-0' : ''"
            title="Analytics"
          >
            <i
              class="ph ph-chart-bar text-xl {{ request()->routeIs('analytics.*') ? '' : 'group-hover:scale-110 transition-transform' }}"
            ></i>
            <span
              x-show="!sidebarCollapsed || isMobile"
              x-transition:enter="transition ease-out duration-300"
              x-transition:enter-start="opacity-0 -translate-x-4"
              x-transition:enter-end="opacity-100 translate-x-0"
              class="whitespace-nowrap"
              >Analytics</span
            >
          </a>
        </li>
      @endif

      <li
        class="px-4 pt-6 pb-2 transition-opacity duration-300"
        :class="sidebarCollapsed && !isMobile ? 'opacity-0 h-0 overflow-hidden' : 'opacity-100'"
      >
        <span
          class="text-muted-foreground/60 text-[10px] font-black tracking-[0.2em] whitespace-nowrap uppercase"
          >Account</span
        >
      </li>

      <li>
        <a
          href="{{ route('user.edit', auth()->user()->slug) }}"
          class="flex items-center gap-4 px-4 py-3.5 rounded-2xl font-bold text-sm transition-all group {{ request()->routeIs('user.edit') ? 'bg-muted text-foreground' : 'text-muted-foreground hover:bg-muted hover:text-foreground' }}"
          :class="sidebarCollapsed && !isMobile ? 'justify-center px-0' : ''"
          title="Settings"
        >
          <i class="ph ph-gear text-xl"></i>
          <span
            x-show="!sidebarCollapsed || isMobile"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-x-4"
            x-transition:enter-end="opacity-100 translate-x-0"
            class="whitespace-nowrap"
            >Settings</span
          >
        </a>
      </li>
    </ul>

    <div class="border-border mt-8 shrink-0 border-t pt-6">
      <a
        href="{{ route('logout') }}"
        onclick="
          event.preventDefault();
          document.getElementById('logout-form').submit();
        "
        class="group flex items-center gap-4 rounded-2xl px-4 py-3.5 text-sm font-bold text-red-500 transition-all hover:bg-red-50"
        :class="sidebarCollapsed && !isMobile ? 'justify-center px-0' : ''"
        title="Sign Out"
      >
        <i class="ph ph-sign-out text-xl transition-transform group-hover:-translate-x-1"></i>
        <span
          x-show="!sidebarCollapsed || isMobile"
          x-transition:enter="transition ease-out duration-300"
          x-transition:enter-start="opacity-0 -translate-x-4"
          x-transition:enter-end="opacity-100 translate-x-0"
          class="whitespace-nowrap"
          >Sign Out</span
        >
      </a>
      <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
        @csrf
      </form>
    </div>
  </div>
</aside>

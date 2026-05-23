<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="bg-background">
<head>
  @include ('includes.admin_v2.head')
  @yield ('page_css')
  <script>
    // Prevent staggered transition by applying collapsed state immediately
    // This runs before body renders to prevent flash of wrong layout
    (function () {
      const collapsed = localStorage.getItem('sidebarCollapsed') === 'true';
      if (collapsed) {
        document.documentElement.classList.add('sidebar-collapsed');
        // Also add the correct padding class to main content before render
        document.body.classList.add('main-content-collapsed');
      }
    })();
  </script>
  <style>
    @media (min-width: 1024px) {
      /* Main content padding based on sidebar state */
      body:not(.main-content-collapsed) .lg\:pl-72 {
        padding-left: 18rem !important;
      }
      body.main-content-collapsed .lg\:pl-72,
      html.sidebar-collapsed .lg\:pl-72 {
        padding-left: 6rem !important;
      }

      /* Sidebar collapsed states */
      html.sidebar-collapsed aside {
        width: 6rem !important;
      }
      html.sidebar-collapsed aside h1 {
        display: none !important;
      }
      html.sidebar-collapsed aside .px-4.pb-2,
      html.sidebar-collapsed aside .px-4.pt-6.pb-2 {
        opacity: 0 !important;
        height: 0 !important;
        overflow: hidden !important;
      }
      html.sidebar-collapsed aside span.whitespace-nowrap {
        display: none !important;
      }
      html.sidebar-collapsed aside .justify-between {
        justify-content: center !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
      }
      html.sidebar-collapsed aside .px-4.py-3\.5 {
        justify-content: center !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
      }

      /* Toggle icon visibility based on document state */
      html.sidebar-collapsed aside button .toggle-icon-collapsed {
        display: inline-block !important;
      }
      html.sidebar-collapsed aside button .toggle-icon-expanded {
        display: none !important;
      }
    }
  </style>
</head>

<body
  class="bg-background text-foreground selection:bg-primary/30 min-h-screen font-sans antialiased"
>
  <div
    x-data="{
      sidebarOpen: false,
      sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true',
      isMobile: window.innerWidth < 1024,
    }"
    x-init="
      $watch('sidebarCollapsed', (value) => {
        localStorage.setItem('sidebarCollapsed', value);
        if (value) {
          document.documentElement.classList.add('sidebar-collapsed');
          document.body.classList.add('main-content-collapsed');
        } else {
          document.documentElement.classList.remove('sidebar-collapsed');
          document.body.classList.remove('main-content-collapsed');
        }
      });
      if (sidebarCollapsed) {
        document.documentElement.classList.add('sidebar-collapsed');
        document.body.classList.add('main-content-collapsed');
      }
    "
    @resize.window="isMobile = window.innerWidth < 1024"
    class="bg-background min-h-screen"
  >
    <!-- Sidebar -->
    @include ('includes.admin_v2.header')

    <!-- Main Content Area -->
    <div
      class="flex min-h-screen flex-col pl-0 transition-all duration-300 lg:pl-72"
      :class="{
        'lg:pl-72': !sidebarCollapsed || isMobile,
        'lg:pl-24': sidebarCollapsed && !isMobile,
      }"
    >
      <!-- Top Navbar -->
      <header
        class="bg-background/80 border-border sticky top-0 z-40 flex h-20 shrink-0 items-center justify-between border-b px-6 backdrop-blur-xl"
      >
        <div class="flex items-center gap-4">
          <button
            @click="sidebarOpen = true"
            class="bg-muted text-foreground hover:text-primary flex h-10 w-10 items-center justify-center rounded-full transition-colors focus:outline-none lg:hidden"
          >
            <i class="ph ph-list text-xl"></i>
          </button>
          <h2 class="text-foreground hidden text-xl font-black tracking-tight sm:block">
            @yield ('page-title', 'Dashboard')
          </h2>
        </div>

        <div class="flex items-center gap-4">
          <!-- Notifications -->
          <livewire:layout.notification-dropdown />

          <!-- User Profile Mini -->
          <div
            class="bg-muted ring-border flex items-center gap-3 rounded-full py-1.5 pr-2 pl-2 ring-1"
          >
            <div class="ring-background h-8 w-8 shrink-0 overflow-hidden rounded-full ring-2">
              <img
                src="{{ auth()->user()->profile_picture_url }}"
                alt="Avatar"
                class="h-full w-full object-cover"
              />
            </div>
            <span
              class="text-foreground hidden pr-2 text-sm font-bold capitalize md:block"
              >{{ auth()->user()->name }}</span
            >
          </div>
        </div>
      </header>

      <!-- Page Content -->
      <main class="flex-1 p-6 sm:p-10">
        <div class="mx-auto max-w-7xl">
          @yield ('content')
        </div>
      </main>
    </div>
  </div>

  @include ('includes.admin_v2.scripts')

  <livewire:admin.delete-confirmation-modal />

  <!-- Global Delete Modal -->
  <div
    id="deleteModal"
    class="fixed inset-0 z-999 hidden overflow-y-auto"
    aria-labelledby="modal-title"
    role="dialog"
    aria-modal="true"
  >
    <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
      <div
        class="bg-background/95 fixed inset-0 backdrop-blur-2xl transition-opacity"
        aria-hidden="true"
        onclick="closeDeleteModal()"
      ></div>

      <div
        class="bg-card ring-border animate-in fade-in zoom-in-95 relative inline-block transform overflow-hidden rounded-[2.5rem] text-left align-middle shadow-2xl ring-1 transition-all duration-300 sm:my-8 sm:w-full sm:max-w-lg"
      >
        <div class="p-8 sm:p-10">
          <div class="mb-8 flex items-center gap-5">
            <div
              class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-red-500/10 text-red-500"
            >
              <i class="ph ph-trash text-3xl"></i>
            </div>
            <div>
              <h3 class="text-foreground text-2xl font-black" id="modal-title">Confirm Deletion</h3>
              <p class="mt-1 text-xs font-black tracking-widest text-red-500 uppercase">Dangerous Action</p>
            </div>
          </div>

          <p class="text-muted-foreground mb-10 leading-relaxed font-medium">Are you sure you want to delete this record? This action is permanent and cannot be undone. All associated data will be removed.</p>

          <div class="flex flex-col gap-4 sm:flex-row">
            <button
              type="button"
              onclick="closeDeleteModal()"
              class="bg-muted text-foreground hover:bg-muted/80 flex-1 rounded-2xl px-8 py-4 text-sm font-bold transition-all active:scale-95"
            >
              Keep it
            </button>
            <form action="" method="POST" id="data-delete-form" class="flex-1">
              @csrf
              @method ('DELETE')
              <button
                type="submit"
                class="delete-btn flex w-full items-center justify-center gap-2 rounded-2xl bg-red-500 px-8 py-4 text-sm font-bold text-white shadow-lg shadow-red-500/20 transition-all hover:bg-red-600 active:scale-95"
              >
                <i class="ph ph-trash text-lg"></i>
                <span class="btn-text">Delete Now</span>
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script type="text/javascript">
    // Global Delete Logic (AJAX)
    function openDeleteModal(url) {
      const modal = document.getElementById('deleteModal');
      const form = document.getElementById('data-delete-form');
      const btn = form?.querySelector('button[type="submit"]');
      const icon = btn?.querySelector('i');
      const text = btn?.querySelector('.btn-text');

      if (btn && icon && text) {
        btn.disabled = false;
        icon.className = 'ph ph-trash text-lg';
        text.textContent = 'Delete Now';
        btn.classList.remove('bg-green-500', 'animate-pulse');
        btn.classList.add('bg-red-500', 'hover:bg-red-600');
      }

      if (modal && form) {
        form.action = url;
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        form.onsubmit = async (e) => {
          e.preventDefault();
          const btn = form.querySelector('button[type="submit"]');
          const icon = btn.querySelector('i');
          const text = btn.querySelector('.btn-text');

          // Start animation
          btn.disabled = true;
          icon.className = 'ph ph-circle-notch animate-spin text-lg';
          text.textContent = 'Deleting...';

          // Add pulse effect
          btn.classList.add('animate-pulse');

          try {
            const res = await fetch(url, {
              method: 'DELETE',
              headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                Accept: 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
              },
            });
            const data = await res.json();

            if (res.ok) {
              // Success animation
              icon.className = 'ph ph-check-circle text-lg';
              text.textContent = 'Deleted!';
              btn.classList.remove('bg-red-500', 'hover:bg-red-600');
              btn.classList.add('bg-green-500');

              // Close after brief delay
              setTimeout(() => {
                closeDeleteModal();
                if (window.LaravelDataTables) {
                  Object.values(window.LaravelDataTables).forEach((table) =>
                    table.ajax.reload(null, false),
                  );
                } else if ($.fn.DataTable.isDataTable('#list-table')) {
                  $('#list-table').DataTable().ajax.reload(null, false);
                }
                showToast(data.message, 'success');
              }, 800);
            } else {
              // Reset on failure
              icon.className = 'ph ph-trash text-lg';
              text.textContent = 'Delete Now';
              btn.classList.remove('animate-pulse');
              showToast(data.message || 'Deletion failed', 'error');
            }
          } catch (err) {
            icon.className = 'ph ph-trash text-lg';
            text.textContent = 'Delete Now';
            btn.classList.remove('animate-pulse');
            showToast('A system error occurred', 'error');
          } finally {
            btn.disabled = false;
          }
        };
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
      }
    }

    function closeDeleteModal() {
      const modal = document.getElementById('deleteModal');
      if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
      }
    }

    // Global Status Toggle Logic
    async function toggleStatus(url, el) {
      const btn = el;
      const icon = btn.querySelector('i');
      const row = btn.closest('tr');
      const badge = row.querySelector('.status-badge');

      btn.disabled = true;
      icon.className = 'ph ph-circle-notch animate-spin text-lg';

      try {
        const res = await fetch(url, {
          method: 'PATCH',
          headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            Accept: 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
          },
        });
        const data = await res.json();

        if (res.ok) {
          const status = data.status; // 'published' | 'draft' | 'hidden'

          const iconMap = {
            published: 'ph ph-eye-slash text-lg',
            draft: 'ph ph-eye text-lg',
            hidden: 'ph ph-eye-slash text-lg',
          };
          const btnMap = {
            published:
              'w-10 h-10 flex items-center justify-center rounded-xl transition-all bg-amber-500/10 text-amber-500',
            draft:
              'w-10 h-10 flex items-center justify-center rounded-xl transition-all bg-green-500/10 text-green-500',
            hidden:
              'w-10 h-10 flex items-center justify-center rounded-xl transition-all bg-red-500/10 text-red-500',
          };
          const badgeMap = {
            published:
              'px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-[0.2em] bg-green-500/10 text-green-500 status-badge',
            draft:
              'px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-[0.2em] bg-amber-500/10 text-amber-500 status-badge',
            hidden:
              'px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-[0.2em] bg-red-500/10 text-red-500 status-badge',
          };

          icon.className = iconMap[status] ?? 'ph ph-eye text-lg';
          btn.className = btnMap[status] ?? btn.className;

          if (badge) {
            badge.className = badgeMap[status] ?? badge.className;
            badge.textContent = status.charAt(0).toUpperCase() + status.slice(1);
          }

          showToast(data.message, 'success');
        } else {
          showToast(data.message || 'Toggle failed', 'error');
          icon.className = icon.dataset.originalIcon;
        }
      } catch (err) {
        showToast('Network error occurred', 'error');
      } finally {
        btn.disabled = false;
      }
    }

    function showToast(message, type = 'success') {
      if (typeof Toastify !== 'undefined') {
        Toastify({
          text: message,
          duration: 3000,
          gravity: 'bottom',
          position: 'right',
          style: {
            background: type === 'success' ? 'oklch(65% 0.2 150)' : 'oklch(60% 0.2 25)',
            borderRadius: '1rem',
            fontWeight: 'bold',
            boxShadow: '0 20px 40px -10px rgba(0,0,0,0.1)',
          },
        }).showToast();
      } else {
        alert(message);
      }
    }

    document.addEventListener('livewire:initialized', () => {
      Livewire.on('toast', (data) => {
        const toastData = Array.isArray(data) ? data[0] : data;
        showToast(toastData.message, toastData.type || 'success');
      });
    });
  </script>
  @yield ('page_scripts')
</body>
</html>

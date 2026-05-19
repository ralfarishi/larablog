@extends ('layouts.admin_v2.template')

@section ('page-title', 'User Profile')

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
            <li class="text-foreground">Profile Settings</li>
          </ol>
        </nav>
        <h3 class="text-foreground text-3xl font-black tracking-tight">
          Account <span class="text-primary">Customization.</span>
        </h3>
      </div>
    </div>

    @include ('includes.admin_v2.alerts')

    <livewire:admin.profile-settings :user="$user" />
  </div>
@endsection

@section ('page_scripts')
@endsection

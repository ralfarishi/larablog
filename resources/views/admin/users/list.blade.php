@extends ('layouts.admin_v2.template')

@section ('page-title', 'User Management')

@section ('content')
  <div class="space-y-8">
    <x-admin.page-header
      title="User <span class='text-primary'>Community.</span>"
      :breadcrumbs="
        [
        ['label' => 'Dashboard', 'route' => 'dashboard'],
        ['label' => 'Users'],
    ]
      "
      action-label="New User"
      action-route="user.create"
    />

    @include ('includes.admin_v2.alerts')

    <livewire:admin.user-table />
  </div>
@endsection

@section ('page_scripts')
@endsection

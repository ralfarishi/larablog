@extends ('layouts.admin_v2.template')

@section ('page-title', 'Category Management')

@section ('content')
  <div class="space-y-8">
    <x-admin.page-header
      title="Content <span class='text-primary'>Taxonomy.</span>"
      :breadcrumbs="
        [
        ['label' => 'Dashboard', 'route' => 'dashboard'],
        ['label' => 'Categories'],
    ]
      "
    />

    @include ('includes.admin_v2.alerts')

    <livewire:admin.category-table />
  </div>
@endsection

@section ('page_scripts')
@endsection

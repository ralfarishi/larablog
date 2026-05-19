@extends ('layouts.admin_v2.template')

@section ('page-title', 'Article Management')

@section ('content')
  <div class="space-y-8">
    <x-admin.page-header
      title="Article Library."
      :breadcrumbs="
        [
        ['label' => 'Dashboard', 'route' => 'dashboard'],
        ['label' => 'Articles'],
    ]
      "
      action-label="Create Article"
      action-route="article.create"
    />

    @include ('includes.admin_v2.alerts')

    <livewire:admin.post-table />
  </div>
@endsection

@section ('page_scripts')
@endsection

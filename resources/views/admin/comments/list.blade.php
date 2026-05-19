@extends ('layouts.admin_v2.template')

@section ('page-title', 'Comment Management')

@section ('content')
  <div class="space-y-8">
    <x-admin.page-header
      title="Community <span class='text-primary'>Feedback.</span>"
      :breadcrumbs="
        [
        ['label' => 'Dashboard', 'route' => 'dashboard'],
        ['label' => 'Comments'],
    ]
      "
    />

    @include ('includes.admin_v2.alerts')

    <livewire:admin.comment-table />
  </div>
@endsection

@section ('page_scripts')
@endsection

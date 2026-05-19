{{--
    Admin DataTable Styles
    Usage: @include('components.admin.datatable-styles')
    Place inside @section('page_css')
--}}
<style>
  .dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: oklch(65% 0.15 45) !important;
    color: white !important;
    border-radius: 0.75rem !important;
    font-weight: 700 !important;
    border-color: oklch(65% 0.15 45) !important;
  }
  .dataTables_wrapper .dataTables_paginate .paginate_button {
    border-radius: 0.75rem !important;
    font-weight: 700 !important;
  }
  .dataTables_wrapper .dataTables_filter input {
    border-radius: 0.75rem !important;
    background: var(--muted) !important;
    padding: 0.5rem 1rem !important;
  }
  .dataTables_wrapper .dataTables_filter input:focus {
    border-color: oklch(65% 0.15 45) !important;
    outline: none !important;
  }
  table.dataTable thead th {
    font-size: 0.75rem !important;
    font-weight: 900 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.1em !important;
    color: var(--muted-foreground) !important;
    padding-top: 1rem !important;
    padding-bottom: 1rem !important;
    border-bottom: 1px solid var(--border) !important;
  }
  table.dataTable td {
    padding-top: 1rem !important;
    padding-bottom: 1rem !important;
    border-bottom: 1px solid color-mix(in oklch, var(--border) 50%, transparent) !important;
    font-size: 0.875rem !important;
    font-weight: 500 !important;
  }
  table.dataTable td.dataTables_empty {
    text-align: center !important;
    padding-top: 3rem !important;
    padding-bottom: 3rem !important;
    color: var(--muted-foreground) !important;
  }
</style>

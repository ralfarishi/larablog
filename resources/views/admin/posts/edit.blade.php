@extends ('layouts.admin_v2.template')

@section ('page-title', 'Edit Article')

@section ('content')
  <div class="space-y-8 pb-20">
    @if ($errors->any())
      <div class="mb-8 rounded-[2rem] border border-red-500/20 bg-red-500/10 p-6">
        <div class="mb-4 flex items-center gap-3 text-red-500">
          <i class="ph ph-warning-circle text-2xl"></i>
          <h4 class="text-sm font-black tracking-widest uppercase">Validation Errors Found</h4>
        </div>
        <ul class="space-y-1">
          @foreach ($errors->all() as $error)
            <li class="flex items-center gap-2 text-sm font-bold text-red-500/80">
              <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
              {{ $error }}
            </li>
          @endforeach
        </ul>
      </div>
    @endif
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
              <a href="{{ route('article.index') }}" class="hover:text-primary transition-colors"
                >Articles</a
              >
            </li>
            <li><i class="ph ph-caret-right text-[10px]"></i></li>
            <li class="text-foreground">Edit</li>
          </ol>
        </nav>
        <h3 class="text-foreground text-3xl font-black tracking-tight">
          Refine your <span class="text-primary">masterpiece.</span>
        </h3>
      </div>
      <a
        href="{{ route('article.index') }}"
        class="bg-muted text-foreground hover:bg-muted/80 inline-flex items-center gap-2 rounded-full px-6 py-3 text-sm font-bold transition-all"
      >
        <i class="ph ph-arrow-left"></i>
        Back to Library
      </a>
    </div>

    @include ('includes.admin_v2.alerts')

    <livewire:admin.article-form :post="$post" />
  </div>
@endsection

@section ('page_scripts')
@endsection

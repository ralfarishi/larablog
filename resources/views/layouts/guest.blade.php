<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />

  <title>LaraBlog | {{ $title ?? 'Auth' }}</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400..700;1,9..40,400..700&display=swap"
    rel="stylesheet"
  />

  <!-- Phosphor Icons -->
  <script src="https://unpkg.com/@phosphor-icons/web"></script>

  <link rel="icon" href="{{ asset('images/favicon.ico') }}" />
  <link
    rel="stylesheet"
    type="text/css"
    href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css"
  />

  <!-- Scripts -->
  @vite (['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background text-foreground selection:bg-primary/30 font-sans antialiased">
  <div class="grid min-h-screen grid-cols-1 lg:grid-cols-2">
    <!-- Brand Section (Visible on Desktop) -->
    <div class="bg-muted/30 relative hidden flex-col justify-between overflow-hidden p-12 lg:flex">
      <div class="absolute inset-0 z-0 opacity-10">
        <div
          class="bg-primary absolute top-0 left-0 h-96 w-96 -translate-x-1/2 -translate-y-1/2 rounded-full blur-[120px]"
        ></div>
        <div
          class="bg-primary absolute right-0 bottom-0 h-96 w-96 translate-x-1/2 translate-y-1/2 rounded-full blur-[120px]"
        ></div>
      </div>

      <div class="relative z-10">
        <a href="/" class="group flex items-center gap-2">
          <h1
            class="text-foreground group-hover:text-primary text-3xl font-black tracking-tighter transition-colors"
          >
            Lara<span class="text-primary">Blog.</span>
          </h1>
        </a>
      </div>

      <div class="relative z-10 max-w-lg">
        <h2 class="mb-6 text-5xl leading-tight font-black tracking-tight">
          Unlock the best of
          <span class="text-primary underline decoration-4 underline-offset-8"
            >creative stories.</span
          >
        </h2>
        <p class="text-muted-foreground text-xl leading-relaxed font-medium">Join our community of writers and readers exploring the boundaries of design, technology, and modern life.</p>
      </div>

      <div
        class="text-muted-foreground relative z-10 flex items-center gap-6 text-sm font-bold tracking-widest uppercase"
      >
        <span>&copy; {{ date('Y') }} LaraBlog</span>
        <div class="flex gap-4">
          <a href="#" class="hover:text-primary transition-colors">Privacy</a>
          <a href="#" class="hover:text-primary transition-colors">Terms</a>
        </div>
      </div>
    </div>

    <!-- Form Section -->
    <div class="relative flex flex-col items-center justify-center p-6 sm:p-12 md:p-20">
      <!-- Mobile Logo -->
      <div class="mb-12 lg:hidden">
        <a href="/" class="group flex items-center gap-2">
          <h1 class="text-foreground text-2xl font-black tracking-tighter">
            Lara<span class="text-primary">Blog.</span>
          </h1>
        </a>
      </div>

      <div class="w-full max-w-md">{{ $slot }}</div>
    </div>
  </div>

  @yield ('page-scripts')
</body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />

  <title>{{ config('app.name', 'LaraBlog | Login') }}</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400..700;1,9..40,400..700&display=swap"
    rel="stylesheet"
  />

  <!-- Scripts -->
  @vite (['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background text-foreground font-sans antialiased">
  <div class="min-h-screen">
    @include ('layouts.navigation')

    <!-- Page Heading -->
    @if (isset($header))
      <header class="bg-card text-card-foreground border-border border-b shadow-sm">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">{{ $header }}</div>
      </header>
    @endif

    <!-- Page Content -->
    <main>{{ $slot }}</main>
  </div>
</body>
</html>

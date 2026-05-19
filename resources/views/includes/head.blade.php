<meta charset="utf-8" />
<meta content="width=device-width, initial-scale=1.0" name="viewport" />

{!! SEO::generate(true) !!}

<!-- Favicons -->
<link href="{{ asset('images/favicon.ico') }}" rel="icon" />
<link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon" />

<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link
  href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400..700;1,9..40,400..700&display=swap"
  rel="stylesheet"
/>

<!-- Phosphor Icons -->
<script src="https://unpkg.com/@phosphor-icons/web"></script>

<!-- Vite App CSS and JS -->
@vite (['resources/css/app.css', 'resources/js/app.js'])

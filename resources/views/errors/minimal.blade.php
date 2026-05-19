<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>
    @yield ('title')
    | {{ config('app.name') }}
  </title>

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,500;0,9..40,700;0,9..40,900;1,9..40,400&family=Playfair+Display:wght@400;600;700&display=swap"
    rel="stylesheet"
  />
  <script src="https://unpkg.com/@phosphor-icons/web"></script>

  @vite (['resources/css/app.css', 'resources/js/app.js'])

  <style>
    [v-cloak] {
      display: none;
    }

    @keyframes float {
      0%,
      100% {
        transform: translateY(0) rotate(0deg);
      }
      33% {
        transform: translateY(-15px) rotate(2deg);
      }
      66% {
        transform: translateY(-8px) rotate(-1deg);
      }
    }

    @keyframes fade-up {
      0% {
        opacity: 0;
        transform: translateY(30px) scale(0.95);
      }
      70% {
        opacity: 1;
        transform: translateY(-5px) scale(1);
      }
      100% {
        opacity: 1;
        transform: translateY(0) scale(1);
      }
    }

    @keyframes pulse-glow {
      0%,
      100% {
        opacity: 0.4;
        transform: scale(1);
      }
      50% {
        opacity: 0.7;
        transform: scale(1.05);
      }
    }

    @keyframes grain {
      0%,
      100% {
        transform: translate(0, 0);
      }
      10% {
        transform: translate(-5%, -10%);
      }
      20% {
        transform: translate(-15%, 5%);
      }
      30% {
        transform: translate(7%, -25%);
      }
      40% {
        transform: translate(-5%, 25%);
      }
      50% {
        transform: translate(-15%, 10%);
      }
      60% {
        transform: translate(15%, 0%);
      }
      70% {
        transform: translate(0%, 15%);
      }
      80% {
        transform: translate(3%, 35%);
      }
      90% {
        transform: translate(-10%, 10%);
      }
    }

    .animate-float {
      animation: float 8s ease-in-out infinite;
    }
    .animate-float-delay {
      animation: float 10s ease-in-out infinite;
      animation-delay: -2s;
    }
    .animate-fade-up {
      animation: fade-up 0.8s ease-out forwards;
    }
    .animate-pulse-glow {
      animation: pulse-glow 4s ease-in-out infinite;
    }

    .grain-overlay {
      position: fixed;
      inset: 0;
      background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.8' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)'/%3E%3C/svg%3E");
      opacity: 0.03;
      pointer-events: none;
      z-index: 9999;
    }
  </style>
</head>
<body class="bg-background text-foreground selection:bg-primary/20 h-full font-sans antialiased">
  <div class="grain-overlay"></div>

  <!-- Ambient Background -->
  <div class="pointer-events-none fixed inset-0 overflow-hidden">
    <div
      class="bg-primary/5 animate-pulse-glow absolute top-[-20%] left-[-10%] h-[50%] w-[50%] rounded-full blur-[150px]"
    ></div>
    <div
      class="bg-secondary/30 animate-pulse-glow absolute right-[-10%] bottom-[-20%] h-[60%] w-[60%] rounded-full blur-[180px]"
      style="animation-delay: 2s"
    ></div>
    <div
      class="bg-primary/3 animate-pulse-glow absolute top-[40%] right-[20%] h-[30%] w-[30%] rounded-full blur-[100px]"
      style="animation-delay: 4s"
    ></div>
  </div>

  <!-- Floating Decorative Elements -->
  <div class="animate-float fixed top-[15%] left-[8%] hidden opacity-10 lg:block">
    <i class="ph-fill ph-ghost text-primary text-6xl"></i>
  </div>
  <div class="animate-float-delay fixed right-[12%] bottom-[20%] hidden opacity-10 lg:block">
    <i class="ph-fill ph-sparkle text-primary text-5xl"></i>
  </div>

  <main class="relative flex min-h-full items-center justify-center px-6 py-10">
    <div class="w-full max-w-2xl">
      <div class="text-center">
        <!-- Error Code Display -->
        <div class="animate-fade-up relative mb-12" style="animation-delay: 0.1s">
          <span
            class="font-display from-primary via-primary to-primary/60 bg-gradient-to-br bg-clip-text text-[12rem] leading-none font-black tracking-tighter text-transparent opacity-90 sm:text-[16rem]"
          >
            @yield ('code')
          </span>
          <div
            class="from-background absolute inset-0 bg-gradient-to-t via-transparent to-transparent"
          ></div>
        </div>

        <!-- Icon Badge -->
        <div class="animate-fade-up relative mb-8 inline-flex" style="animation-delay: 0.2s">
          <div class="bg-primary/20 absolute inset-0 rounded-full blur-2xl"></div>
          <div
            class="bg-card border-border/50 relative flex h-20 w-20 items-center justify-center rounded-3xl border shadow-xl"
          >
            <i class="ph-duotone @yield('icon', 'ph-warning') text-4xl text-primary"></i>
          </div>
        </div>

        <!-- Title & Description -->
        <div class="animate-fade-up mb-12 space-y-4" style="animation-delay: 0.3s">
          <h1 class="font-display text-4xl font-bold tracking-tight sm:text-5xl">
            @yield ('message')
          </h1>
          <p class="text-muted-foreground mx-auto max-w-lg text-lg leading-relaxed sm:text-xl">
            @yield ('description')
          </p>
        </div>

        <!-- Actions -->
        <div
          class="animate-fade-up flex flex-col items-center justify-center gap-4 sm:flex-row"
          style="animation-delay: 0.4s"
        >
          @if (request()->routeIs('home'))
            <a
              href="javascript:history.back()"
              class="group bg-muted hover:bg-muted/80 border-border/50 flex items-center gap-3 rounded-2xl border px-8 py-4 text-sm font-bold transition-all duration-300 hover:-translate-y-1"
            >
              <i class="ph ph-arrow-left transition-transform group-hover:-translate-x-1"></i>
              Go Back
            </a>
          @else
            <a
              href="{{ route('home') }}"
              class="group bg-foreground text-background hover:bg-primary hover:shadow-primary/25 flex items-center gap-3 rounded-2xl px-8 py-4 text-sm font-bold transition-all duration-300 hover:-translate-y-1 hover:shadow-xl"
            >
              <i class="ph ph-house"></i>
              Return Home
            </a>
          @endif

          @if (!request()->routeIs('home'))
            <a
              href="javascript:history.back()"
              class="group bg-muted hover:bg-muted/80 border-border/50 flex items-center gap-3 rounded-2xl border px-8 py-4 text-sm font-bold transition-all duration-300 hover:-translate-y-1"
            >
              <i class="ph ph-arrow-left transition-transform group-hover:-translate-x-1"></i>
              Go Back
            </a>
          @endif
        </div>
      </div>
    </div>
  </main>
</body>
</html>

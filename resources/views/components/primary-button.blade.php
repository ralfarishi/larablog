<button
  {{
    $attributes->merge([
      'type' => 'submit',
      'class' =>
        'inline-flex items-center justify-center h-12 px-8 py-2 bg-foreground text-background border border-transparent rounded-full font-bold text-sm tracking-widest uppercase transition-all duration-300 hover:bg-primary hover:text-primary-foreground hover:-translate-y-0.5 hover:shadow-lg focus:outline-none focus:ring-4 focus:ring-primary/20 active:scale-[0.98] disabled:opacity-50 disabled:pointer-events-none',
    ])
  }}
>
  {{ $slot }}
</button>

<button
  {{
    $attributes->merge([
      'type' => 'button',
      'class' =>
        'inline-flex items-center justify-center h-10 px-6 py-2 bg-background border border-border rounded-lg font-medium text-sm text-foreground hover:bg-muted focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:ring-offset-background disabled:opacity-50 transition-all duration-200 active:scale-[0.98] shadow-sm',
    ])
  }}
>
  {{ $slot }}
</button>

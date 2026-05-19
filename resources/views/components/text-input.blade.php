@props (['disabled' => false])

<input
  {{ $disabled ? 'disabled' : '' }}
  {!!
    $attributes->merge([
      'class' =>
        'flex w-full rounded-2xl border-2 border-border bg-background px-5 py-3.5 text-base text-foreground ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:border-primary focus-visible:ring-4 focus-visible:ring-primary/20 disabled:cursor-not-allowed disabled:opacity-50 transition-all',
    ])
  !!}
/>

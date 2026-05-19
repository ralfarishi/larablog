@props (['value'])

<label
  {{
    $attributes->merge([
      'class' => 'block text-xs font-bold text-muted-foreground uppercase tracking-widest mb-2.5 ml-1',
    ])
  }}
>
  {{ $value ?? $slot }}
</label>

<button
  {{
    $attributes->merge([
      'type' => 'submit',
      'class' =>
        'inline-flex items-center justify-center h-10 px-6 py-2 bg-red-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:ring-offset-background disabled:opacity-50 transition-all duration-200 active:scale-[0.98] shadow-sm',
    ])
  }}
>
  {{ $slot }}
</button>

@props (['post', 'showCategory' => false])

<article
  {{
    $attributes->merge([
      'class' =>
        'relative flex flex-col bg-card rounded-3xl shadow-sm ring-1 ring-border overflow-hidden transition-all duration-300 hover:shadow-lg hover:-translate-y-2 group',
    ])
  }}
>
  <a
    href="{{ route('post', $post->slug) }}"
    class="bg-muted/50 relative block h-56 overflow-hidden"
  >
    @if ($post->image_url && !str_contains($post->image_url, 'placehold.co'))
      <img
        src="{{ $post->image_url }}"
        alt="{{ $post->title }}"
        class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
        loading="lazy"
      />
    @else
      <div class="flex h-full w-full items-center justify-center">
        <i class="ph ph-image-square text-muted-foreground/20 text-4xl"></i>
      </div>
    @endif
    <div
      class="absolute inset-0 bg-black/10 opacity-0 transition-opacity group-hover:opacity-100"
    ></div>
  </a>
  <div class="flex flex-1 flex-col p-6 md:p-8">
    <div class="mb-4 flex items-center justify-between">
      @if ($showCategory)
        <a
          href="{{ route('categories', strtolower($post->category->name)) }}"
          class="text-primary relative z-10 text-xs font-bold tracking-wider uppercase hover:underline"
          >{{ $post->category->name }}</a
        >
      @else
        <a
          href="{{ route('post-by-user', $post->user->slug) }}"
          class="text-primary relative z-10 text-xs font-bold tracking-wider uppercase hover:underline"
          >{{ $post->user->name }}</a
        >
      @endif
      <time
        datetime="{{ $post->created_at }}"
        class="text-muted-foreground text-xs font-semibold"
        >{{ formatDate($post->created_at) }}</time
      >
    </div>

    <h2
      class="text-foreground group-hover:text-primary mb-4 line-clamp-3 text-xl leading-snug font-bold transition-colors md:text-2xl"
    >
      <a
        href="{{ route('post', $post->slug) }}"
        class="before:absolute before:inset-0"
        >{{ $post->title }}</a
      >
    </h2>

    @php $pTag = getParagraphTagOnly($post->content); @endphp
    <p class="text-muted-foreground mb-6 line-clamp-3 flex-1 text-sm">
      {!!
        str()->limit(
          strip_tags($pTag),
          150,
        )
      !!}
    </p>

    <div
      class="border-border relative z-10 mt-auto flex items-center justify-between border-t pt-5"
    >
      <div
        class="text-muted-foreground hover:text-primary flex cursor-pointer items-center gap-1.5 text-xs font-medium transition-colors"
      >
        <i class="ph ph-chat-circle text-lg"></i>
        <span>{{ $post->comments->count() }} Comments</span>
      </div>
      <span
        class="bg-primary/10 text-primary group-hover:bg-primary group-hover:text-primary-foreground flex h-8 w-8 items-center justify-center rounded-full transition-colors"
      >
        <i class="ph ph-arrow-right"></i>
      </span>
    </div>
  </div>
</article>

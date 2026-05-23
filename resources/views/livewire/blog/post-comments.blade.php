<div class="bg-card ring-border rounded-3xl sm:rounded-[2.5rem] p-5 sm:p-12 shadow-xl ring-1">
  <h3 class="text-foreground mb-6 sm:mb-10 text-xl sm:text-2xl font-black">{{ $comments->total() }} Comments</h3>

  <div class="mb-8 sm:mb-12 space-y-6 sm:space-y-8">
    @foreach ($comments as $comment)
      @php
        $badgeColor =
          $comment->user->role === 'reader'
            ? 'bg-primary/10 text-primary border-primary/20'
            : 'bg-green-500/10 text-green-500 border-green-500/20';
      @endphp
      <div class="flex flex-col sm:flex-row gap-3 sm:gap-5" wire:key="comment-{{ $comment->id }}">
        <!-- Avatar -->
        <div class="shrink-0">
          <img
            src="{{ $comment->user->profile_picture_url }}"
            alt="{{ $comment->user->name }}"
            loading="lazy"
            class="ring-background h-10 w-10 sm:h-12 sm:w-12 rounded-full object-cover shadow-sm ring-2"
          />
        </div>

        <!-- Comment Content / Bubble -->
        <div class="bg-muted/30 ring-border/50 flex-1 rounded-2xl sm:rounded-3xl sm:rounded-tl-none p-4 sm:p-5 ring-1">
          <!-- Desktop Header (Hidden on Mobile) -->
          <div class="hidden sm:flex flex-wrap items-center gap-3 mb-2">
            <h5 class="text-foreground text-base font-bold">{{ $comment->user->name }}</h5>
            @if ($comment->user->role !== 'admin')
              <span
                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $badgeColor }} uppercase tracking-wider"
              >
                {{ strtolower($comment->user->role) }}
              </span>
            @endif
            <time
              class="text-muted-foreground ml-auto text-xs font-semibold tracking-wider uppercase"
            >
              {{
                $comment->created_at->format(
                  'M d, Y',
                )
              }}
            </time>
          </div>
          <p class="text-muted-foreground text-sm sm:text-base leading-relaxed">{{ $comment->content }}</p>
        </div>
      </div>
    @endforeach
  </div>

  {{-- Pagination for comments --}}
  @if ($comments->hasPages())
    <div class="border-border border-t pt-6 mb-8">
      {{ $comments->links() }}
    </div>
  @endif

  <!-- Reply Form -->
  <div class="border-border border-t pt-8 sm:pt-10">
    <h4 class="text-foreground mb-1 sm:mb-2 text-lg sm:text-xl font-bold">Leave a Reply</h4>
    <p class="text-muted-foreground mb-6 sm:mb-8 text-xs sm:text-sm">Join the conversation. Required fields are marked *</p>

    @guest
      <a
        href="{{ route('login') }}"
        class="bg-primary text-primary-foreground inline-flex h-11 sm:h-12 items-center justify-center rounded-xl px-6 sm:px-8 text-sm font-bold shadow-sm transition-all hover:-translate-y-0.5 hover:shadow-lg"
      >
        Login to comment
      </a>
    @endguest

    @auth
      @if ($post->allowed_comment)
        <form wire:submit="postComment" class="space-y-4 sm:space-y-6">
          <div>
            <textarea
              wire:model="content"
              class="border-border bg-background text-foreground ring-offset-background placeholder:text-muted-foreground focus-visible:border-primary focus-visible:ring-primary/20 flex min-h-[120px] sm:min-h-[160px] w-full resize-y rounded-2xl border-2 px-4 py-3 sm:px-5 sm:py-4 text-sm sm:text-base transition-all focus-visible:ring-4 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
              placeholder="What are your thoughts?*"
              rows="4"
            ></textarea>
            @error ('content')
              <p class="mt-2 text-sm font-medium text-red-500">{{ $message }}</p>
            @enderror
          </div>

          @if (session()->has('message'))
            <div class="rounded-xl bg-green-500/10 p-4 text-sm font-bold text-green-600">
              {{ session('message') }}
            </div>
          @endif

          <button
            type="submit"
            wire:loading.attr="disabled"
            class="bg-primary text-primary-foreground inline-flex h-11 sm:h-12 items-center justify-center rounded-xl px-6 sm:px-8 text-sm font-bold shadow-sm transition-all hover:-translate-y-0.5 hover:shadow-lg disabled:pointer-events-none disabled:opacity-50"
          >
            <span wire:loading.remove wire:target="postComment">Post Comment</span>
            <span wire:loading wire:target="postComment">Posting...</span>
          </button>
        </form>
      @else
        <div class="bg-muted rounded-2xl p-6 text-center">
          <p class="text-muted-foreground font-bold italic text-sm sm:text-base">Comments are disabled for this article.</p>
        </div>
      @endif
    @endauth
  </div>
</div>

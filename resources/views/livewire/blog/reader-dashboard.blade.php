<section class="bg-background min-h-screen py-12 md:py-20">
  <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
    
    {{-- Header --}}
    <div class="mb-10 flex flex-col justify-between gap-6 sm:flex-row sm:items-center border-b border-border pb-6">
      <div>
        <h3 class="text-foreground text-3xl font-black tracking-tight">
          Reader <span class="text-primary">Center</span>
        </h3>
        <p class="text-muted-foreground mt-1 font-medium">Manage your bookmarks, comment logs, and account settings.</p>
      </div>
      <div class="flex items-center gap-4">
        <span class="text-muted-foreground text-sm font-semibold">
          Logged in as: <strong class="text-foreground font-black">{{ $user->name }}</strong>
        </span>
      </div>
    </div>

    {{-- Main Workspace Grid --}}
    <div class="grid grid-cols-1 gap-8 lg:grid-cols-4">
      
      {{-- Sidebar Navigation --}}
      <div class="lg:col-span-1">
        <div class="bg-card ring-border rounded-[2.5rem] p-6 shadow-sm ring-1 sticky top-24">
          <nav class="space-y-2">
            <button
              type="button"
              wire:click="setTab('bookmarks')"
              class="w-full flex items-center gap-3 px-3 py-3.5 rounded-2xl text-sm font-bold tracking-wide uppercase transition-all duration-200 active:scale-95 whitespace-nowrap {{ $activeTab === 'bookmarks' ? 'bg-primary text-primary-foreground shadow-sm' : 'text-muted-foreground hover:bg-muted hover:text-foreground' }}"
            >
              <i class="ph ph-bookmark text-lg"></i> Bookmarks
            </button>
            
            <button
              type="button"
              wire:click="setTab('comments')"
              class="w-full flex items-center gap-3 px-3 py-3.5 rounded-2xl text-sm font-bold tracking-wide uppercase transition-all duration-200 active:scale-95 whitespace-nowrap {{ $activeTab === 'comments' ? 'bg-primary text-primary-foreground shadow-sm' : 'text-muted-foreground hover:bg-muted hover:text-foreground' }}"
            >
              <i class="ph ph-chat-centered-text text-lg"></i> Comments
            </button>

            <button
              type="button"
              wire:click="setTab('profile')"
              class="w-full flex items-center gap-3 px-3 py-3.5 rounded-2xl text-sm font-bold tracking-wide uppercase transition-all duration-200 active:scale-95 whitespace-nowrap {{ $activeTab === 'profile' ? 'bg-primary text-primary-foreground shadow-sm' : 'text-muted-foreground hover:bg-muted hover:text-foreground' }}"
            >
              <i class="ph ph-user-gear text-lg"></i> Profile Settings
            </button>
          </nav>
        </div>
      </div>

      {{-- Content Area --}}
      <div class="lg:col-span-3">
        
        {{-- Bookmarks Tab --}}
        @if ($activeTab === 'bookmarks')
          <div class="space-y-6">
            <div class="bg-card ring-border rounded-[2.5rem] p-8 shadow-sm ring-1 sm:p-10">
              <h4 class="text-foreground mb-6 text-xl font-black">Bookmarked Articles</h4>
              
              @if ($bookmarks->isEmpty())
                <div class="flex flex-col items-center justify-center py-16 text-center">
                  <div class="flex h-16 w-16 items-center justify-center rounded-full bg-primary/10 text-primary mb-4">
                    <i class="ph ph-bookmark-simple text-4xl"></i>
                  </div>
                  <p class="text-foreground text-lg font-bold">No Bookmarks Yet</p>
                  <p class="text-muted-foreground mt-1 text-sm max-w-xs">Articles you bookmark will appear here for easy access.</p>
                </div>
              @else
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                  @foreach ($bookmarks as $bookmark)
                    @php $post = $bookmark->post; @endphp
                    @if ($post)
                      <article wire:key="bookmark-{{ $bookmark->id }}" class="bg-muted/30 ring-border group relative flex flex-col overflow-hidden rounded-3xl ring-1 transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                        <a href="{{ route('post', $post->slug) }}" class="relative block h-40 overflow-hidden bg-muted">
                          <img
                            src="{{ $post->image_url }}"
                            alt="{{ $post->title }}"
                            class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                          />
                        </a>
                        <div class="flex flex-1 flex-col p-5">
                          @if ($post->category)
                            <span class="bg-primary/10 text-primary mb-2 inline-flex items-center self-start rounded-full px-2.5 py-0.5 text-[10px] font-black tracking-widest uppercase">
                              {{ $post->category->name }}
                            </span>
                          @endif
                          <h5 class="text-foreground group-hover:text-primary mb-2 line-clamp-2 text-base font-bold transition-colors">
                            <a href="{{ route('post', $post->slug) }}">{{ $post->title }}</a>
                          </h5>
                          <div class="mt-auto flex items-center justify-between pt-4 border-t border-border/50">
                            <span class="text-muted-foreground text-xs font-semibold">By {{ $post->user->name ?? 'Author' }}</span>
                            <button
                              type="button"
                              wire:click="removeBookmark({{ $bookmark->id }})"
                              class="text-red-500 hover:text-red-700 hover:bg-red-500/10 p-1.5 rounded-lg transition-colors"
                              title="Remove Bookmark"
                            >
                              <i class="ph ph-trash text-lg"></i>
                            </button>
                          </div>
                        </div>
                      </article>
                    @endif
                  @endforeach
                </div>
                
                <div class="mt-8">
                  {{ $bookmarks->links() }}
                </div>
              @endif
            </div>
          </div>
        @endif

        {{-- Comment History Tab --}}
        @if ($activeTab === 'comments')
          <div class="space-y-6">
            <div class="bg-card ring-border rounded-[2.5rem] p-8 shadow-sm ring-1 sm:p-10">
              <h4 class="text-foreground mb-6 text-xl font-black">Comment History</h4>
              
              @if ($comments->isEmpty())
                <div class="flex flex-col items-center justify-center py-16 text-center">
                  <div class="flex h-16 w-16 items-center justify-center rounded-full bg-primary/10 text-primary mb-4">
                    <i class="ph ph-chat-circle text-4xl"></i>
                  </div>
                  <p class="text-foreground text-lg font-bold">No Comments Yet</p>
                  <p class="text-muted-foreground mt-1 text-sm max-w-xs">Your comments on articles will be logged here.</p>
                </div>
              @else
                <div class="space-y-6">
                  @foreach ($comments as $comment)
                    <div wire:key="dashboard-comment-{{ $comment->id }}" class="bg-muted/30 border-border rounded-2xl border p-5 transition-all hover:bg-muted/50">
                      <div class="flex items-start justify-between gap-4 mb-3">
                        <div class="min-w-0">
                          <p class="text-muted-foreground text-xs font-semibold">
                            Commented on 
                            <a href="{{ route('post', $comment->post->slug) }}" class="text-primary hover:underline font-bold">
                              {{ $comment->post->title ?? 'Deleted Post' }}
                            </a>
                          </p>
                          <time class="text-muted-foreground text-[10px] font-bold uppercase block mt-0.5">
                            {{ $comment->created_at->diffForHumans() }}
                          </time>
                        </div>
                        <button
                          type="button"
                          wire:click="deleteComment({{ $comment->id }})"
                          class="text-red-500 hover:text-red-700 hover:bg-red-500/10 p-2 rounded-lg transition-colors shrink-0"
                          title="Delete Comment"
                        >
                          <i class="ph ph-trash text-base"></i>
                        </button>
                      </div>
                      <p class="text-foreground text-sm font-medium leading-relaxed bg-background/50 rounded-xl p-4 border border-border/40">
                        {{ $comment->content }}
                      </p>
                    </div>
                  @endforeach
                </div>

                <div class="mt-8">
                  {{ $comments->links() }}
                </div>
              @endif
            </div>
          </div>
        @endif

        {{-- Profile Settings Tab --}}
        @if ($activeTab === 'profile')
          <div class="space-y-6">
            <div class="bg-card ring-border rounded-[2.5rem] p-8 shadow-sm ring-1 sm:p-10">
              <h4 class="text-foreground mb-6 text-xl font-black">Profile Settings</h4>
              
              <form wire:submit.prevent="updateProfile" class="space-y-6">
                
                {{-- Avatar Upload --}}
                <div class="flex flex-col items-center gap-4 sm:flex-row">
                  <div class="relative group">
                    <div class="h-24 w-24 overflow-hidden rounded-full ring-border ring-4">
                      @if ($avatar)
                        <img src="{{ $avatar->temporaryUrl() }}" class="h-full w-full object-cover" />
                      @else
                        <img src="{{ $user->profile_picture_url }}" class="h-full w-full object-cover" />
                      @endif
                    </div>
                    <label class="absolute inset-0 flex cursor-pointer items-center justify-center rounded-full bg-black/50 opacity-0 transition-opacity group-hover:opacity-100">
                      <i class="ph ph-camera text-white text-2xl"></i>
                      <input type="file" wire:model="avatar" class="sr-only" accept="image/*" />
                    </label>
                  </div>
                  <div class="text-center sm:text-left">
                    <p class="text-foreground font-bold text-sm">Profile Picture</p>
                    <p class="text-muted-foreground text-xs mt-1">Upload a PNG, JPG, JPEG, or WEBP image. Max size 1MB.</p>
                    @error ('avatar')
                      <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p>
                    @enderror
                  </div>
                </div>

                <hr class="border-border/60" />

                {{-- Name --}}
                <div class="space-y-2">
                  <x-input-label for="name" :value="__('Name')" />
                  <x-text-input
                    id="name"
                    wire:model="name"
                    type="text"
                    required
                    autocomplete="name"
                  />
                  @error ('name')
                    <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p>
                  @enderror
                </div>

                {{-- Email --}}
                <div class="space-y-2">
                  <x-input-label for="email" :value="__('Email Address')" />
                  <x-text-input
                    id="email"
                    wire:model="email"
                    type="email"
                    required
                    autocomplete="username"
                  />
                  @error ('email')
                    <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p>
                  @enderror
                </div>

                <hr class="border-border/60" />

                <div class="bg-muted/20 border border-border/60 rounded-2xl p-6 space-y-4">
                  <h5 class="text-foreground font-black text-sm uppercase tracking-wider">Change Password</h5>
                  <p class="text-muted-foreground text-xs">Leave these fields blank if you do not wish to change your password.</p>
                  
                  {{-- Password --}}
                  <div class="space-y-2">
                    <x-input-label for="password" :value="__('New Password')" />
                    <x-text-input
                      id="password"
                      wire:model="password"
                      type="password"
                      autocomplete="new-password"
                    />
                    @error ('password')
                      <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p>
                    @enderror
                  </div>

                  {{-- Password Confirmation --}}
                  <div class="space-y-2">
                    <x-input-label for="password_confirmation" :value="__('Confirm New Password')" />
                    <x-text-input
                      id="password_confirmation"
                      wire:model="password_confirmation"
                      type="password"
                      autocomplete="new-password"
                    />
                  </div>
                </div>

                {{-- Save Button --}}
                <div class="flex justify-end pt-4">
                  <button
                    type="submit"
                    wire:loading.attr="disabled"
                    class="bg-primary text-primary-foreground hover:opacity-90 inline-flex items-center gap-2 rounded-xl px-6 py-3.5 text-xs font-black tracking-widest uppercase transition-all duration-300 active:scale-95 disabled:opacity-75"
                  >
                    <span wire:loading.remove wire:target="updateProfile">Save Changes</span>
                    <span wire:loading wire:target="updateProfile" class="flex items-center gap-2">
                      <div class="h-3 w-3 animate-spin rounded-full border border-white border-t-transparent"></div>
                      Saving...
                    </span>
                    <i class="ph ph-check text-base"></i>
                  </button>
                </div>
              </form>
            </div>
          </div>
        @endif

      </div>
    </div>
  </div>
</section>

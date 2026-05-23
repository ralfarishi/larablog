<div class="space-y-6">
  <!-- Workspace Control Bar -->
  <div class="flex items-center justify-between border-b border-border/60 pb-4">
    <div>
      <h3 class="text-foreground text-xl font-bold tracking-tight">
        @if($post)
          Edit <span class="text-primary">Article</span>
        @else
          Create <span class="text-primary">Article</span>
        @endif
      </h3>
    </div>
    <div class="bg-muted flex items-center gap-1 rounded-xl p-1 shadow-sm">
      <button
        type="button"
        wire:click="$set('editorMode', 'write')"
        class="flex items-center gap-2 px-4 py-2 text-xs font-black tracking-widest uppercase rounded-lg transition-all active:scale-95 {{ $editorMode === 'write' ? 'bg-primary text-primary-foreground shadow-sm' : 'text-muted-foreground hover:bg-card hover:text-foreground' }}"
      >
        <i class="ph ph-pencil-simple text-sm"></i> Write
      </button>
      <button
        type="button"
        wire:click="$set('editorMode', 'split')"
        class="flex items-center gap-2 px-4 py-2 text-xs font-black tracking-widest uppercase rounded-lg transition-all active:scale-95 {{ $editorMode === 'split' ? 'bg-primary text-primary-foreground shadow-sm' : 'text-muted-foreground hover:bg-card hover:text-foreground' }}"
      >
        <i class="ph ph-columns text-sm"></i> Split
      </button>
      <button
        type="button"
        wire:click="$set('editorMode', 'preview')"
        class="flex items-center gap-2 px-4 py-2 text-xs font-black tracking-widest uppercase rounded-lg transition-all active:scale-95 {{ $editorMode === 'preview' ? 'bg-primary text-primary-foreground shadow-sm' : 'text-muted-foreground hover:bg-card hover:text-foreground' }}"
      >
        <i class="ph ph-eye text-sm"></i> Preview
      </button>
    </div>
  </div>

  <form wire:submit.prevent="save" @class([
      'grid grid-cols-1 gap-8',
      'lg:grid-cols-3' => $editorMode === 'write',
      'lg:grid-cols-2' => $editorMode === 'split',
      'grid-cols-1' => $editorMode === 'preview',
  ])>
    <!-- Main Form Area -->
    <div @class([
        'space-y-8',
        'lg:col-span-2' => $editorMode === 'write',
        'lg:col-span-1' => $editorMode === 'split',
        'hidden' => $editorMode === 'preview',
    ])>
    <div class="bg-card ring-border rounded-[2.5rem] p-8 shadow-sm ring-1 sm:p-10">
      <div class="space-y-6">
        <!-- Title -->
        <div class="space-y-2">
          <x-input-label for="title" :value="__('Article Title')" />
          <x-text-input
            id="title"
            wire:model="title"
            type="text"
            required
            maxlength="255"
            placeholder="Enter a catchy title..."
          />
          @error ('title')
            <p class="mt-2 text-sm font-bold text-red-500">{{ $message }}</p>
          @enderror
        </div>

        <!-- Content -->
        <div class="space-y-2" wire:ignore>
          <x-input-label for="content" :value="__('Story Content')" />
          <x-tiptap-editor id="content" name="content" wire:model="content" :value="$content" />
        </div>
        @error ('content')
          <p class="mt-2 text-sm font-bold text-red-500">{{ $message }}</p>
        @enderror
      </div>
    </div>
  </div>

  <!-- Live Preview Area -->
  <div @class([
      'space-y-8',
      'lg:col-span-1' => $editorMode === 'split',
      'w-full' => $editorMode === 'preview',
      'hidden' => $editorMode === 'write',
  ])>
    <div class="bg-card ring-border rounded-[2.5rem] p-8 shadow-sm ring-1 sm:p-10 min-h-[500px] flex flex-col">
      <h4 class="text-muted-foreground mb-6 flex items-center gap-2 text-sm font-bold tracking-widest uppercase">
        <i class="ph ph-eye text-primary text-xl"></i>
        Live Preview
      </h4>

      {{-- Preview Title --}}
      <h1 class="text-foreground text-3xl font-black tracking-tight mb-6">
        {{ $title ?: 'Untitled Article' }}
      </h1>

      {{-- Rendered Content --}}
      <div class="prose dark:prose-invert prose-headings:font-black prose-headings:tracking-tight prose-a:text-primary prose-a:font-bold prose-a:no-underline hover:prose-a:underline prose-p:text-foreground/90 prose-p:leading-relaxed prose-p:mb-8 prose-img:rounded-[2.5rem] prose-img:shadow-2xl prose-img:w-full prose-li:text-foreground/90 prose-li:mb-2 prose-blockquote:border-primary prose-blockquote:bg-primary/5 prose-blockquote:text-lg prose-blockquote:font-medium prose-blockquote:py-6 prose-blockquote:px-8 prose-blockquote:rounded-3xl prose-code:text-primary prose-code:bg-primary/10 prose-code:px-2 prose-code:py-1.5 prose-code:rounded-xl prose-code:before:hidden prose-code:after:hidden prose-pre:bg-card prose-pre:ring-1 prose-pre:ring-border prose-pre:text-foreground prose-pre:rounded-2xl prose-strong:font-black max-w-none flex-1 overflow-y-auto">
        {!! \App\Support\ContentRenderer::render($content) !!}
      </div>
    </div>
  </div>

  <!-- Sidebar Options -->
  <div @class([
      'flex flex-col gap-8',
      'hidden' => $editorMode !== 'write',
  ])>
    <!-- Publishing Box -->
    <div class="bg-card ring-border overflow-hidden rounded-[2.5rem] p-8 shadow-sm ring-1">
      <h4
        class="text-foreground mb-6 flex items-center gap-2 text-sm font-black tracking-widest uppercase"
      >
        <i class="ph ph-rocket-launch text-primary text-xl"></i>
        Publishing
      </h4>

      <div class="space-y-6">
        <!-- Category -->
        <div class="space-y-2">
          <x-input-label for="category_id" :value="__('Category')" />
          <div class="group relative">
            <select
              wire:model="category_id"
              id="category_id"
              required
              class="border-border bg-background text-foreground focus:border-primary focus:ring-primary/20 flex w-full cursor-pointer appearance-none rounded-2xl border-2 px-5 py-3.5 text-base transition-all focus:ring-4 focus:outline-none"
            >
              <option value="">Select Category</option>
              @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
              @endforeach
            </select>
            <div
              class="text-muted-foreground group-focus-within:text-primary pointer-events-none absolute top-1/2 right-5 -translate-y-1/2"
            >
              <i class="ph ph-caret-down text-sm"></i>
            </div>
          </div>
          @error ('category_id')
            <p class="mt-2 text-sm font-bold text-red-500">{{ $message }}</p>
          @enderror
        </div>

        <!-- Status -->
        <div class="space-y-3">
          <x-input-label :value="__('Visibility')" />
          <div class="grid grid-cols-2 gap-3">
            <!-- Status -> Published -->
            <label class="group relative cursor-pointer">
              <input type="radio" wire:model="status" value="published" class="peer sr-only" />
              <div
                class="border-border bg-card text-muted-foreground group-hover:border-primary/50 peer-checked:border-primary peer-checked:bg-primary/10 peer-checked:text-primary peer-checked:shadow-primary/10 flex flex-col items-center justify-center gap-1.5 rounded-xl border-2 p-3 shadow-sm transition-all"
              >
                <i class="ph ph-globe-hemisphere-west text-xl"></i>
                <span class="text-xs font-bold tracking-widest uppercase">Published</span>
              </div>
            </label>

            <!-- Status -> Draft -->
            <label class="group relative cursor-pointer">
              <input type="radio" wire:model="status" value="draft" class="peer sr-only" />
              <div
                class="border-border bg-card text-muted-foreground group-hover:border-primary/50 peer-checked:border-primary peer-checked:bg-primary/10 peer-checked:text-primary peer-checked:shadow-primary/10 flex flex-col items-center justify-center gap-1.5 rounded-xl border-2 p-3 shadow-sm transition-all"
              >
                <i class="ph ph-lock-key text-xl"></i>
                <span class="text-xs font-bold tracking-widest uppercase">Draft</span>
              </div>
            </label>
          </div>
          @error ('status')
            <p class="mt-2 text-sm font-bold text-red-500">{{ $message }}</p>
          @enderror
        </div>

        <!-- Comments Toggle -->
        <div class="space-y-3">
          <x-input-label :value="__('Allow Comments')" />
          <div class="grid grid-cols-2 gap-3">
            <!-- Comments -> Yes -->
            <label class="group relative cursor-pointer">
              <input
                type="radio"
                wire:model="allowed_comment"
                value="1"
                class="peer sr-only"
              />
              <div
                class="border-border bg-card text-muted-foreground group-hover:border-primary/50 peer-checked:border-primary peer-checked:bg-primary/10 peer-checked:text-primary peer-checked:shadow-primary/10 flex flex-col items-center justify-center gap-1.5 rounded-xl border-2 p-3 shadow-sm transition-all"
              >
                <i class="ph ph-chat-circle-text text-xl"></i>
                <span class="text-xs font-bold tracking-widest uppercase">Allow</span>
              </div>
            </label>

            <!-- Comments -> No -->
            <label class="group relative cursor-pointer">
              <input
                type="radio"
                wire:model="allowed_comment"
                value="0"
                class="peer sr-only"
              />
              <div
                class="border-border bg-card text-muted-foreground group-hover:border-primary/50 peer-checked:border-primary peer-checked:bg-primary/10 peer-checked:text-primary peer-checked:shadow-primary/10 flex flex-col items-center justify-center gap-1.5 rounded-xl border-2 p-3 shadow-sm transition-all"
              >
                <i class="ph ph-prohibit text-xl"></i>
                <span class="text-xs font-bold tracking-widest uppercase">Disable</span>
              </div>
            </label>
          </div>
          @error ('allowed_comment')
            <p class="mt-2 text-sm font-bold text-red-500">{{ $message }}</p>
          @enderror
        </div>

        <div class="border-border mt-2 border-t pt-6">
          <button
            type="submit"
            wire:loading.attr="disabled"
            wire:target="save"
            class="group bg-primary text-primary-foreground relative flex w-full items-center justify-center gap-3 overflow-hidden rounded-[2rem] px-8 py-5 text-sm font-black tracking-widest uppercase shadow-xl transition-all hover:scale-[1.02] active:scale-95 disabled:opacity-70"
          >
            <div wire:loading.remove wire:target="save" class="flex items-center gap-3">
              <span>{{
                $post
                  ? 'Update Article'
                  : 'Publish Now'
              }}</span>
              <i
                class="ph ph-rocket-launch text-xl transition-transform group-hover:translate-x-1 group-hover:-translate-y-1"
              ></i>
            </div>

            <div wire:loading.flex wire:target="save" class="flex items-center gap-3">
              <div
                class="h-5 w-5 animate-spin rounded-full border-2 border-white border-t-transparent"
              ></div>
              <span>Processing...</span>
            </div>
          </button>
        </div>
      </div>
    </div>

    <!-- Media Box -->
    <div class="bg-card ring-border rounded-[2.5rem] p-8 shadow-sm ring-1">
      <h4
        class="text-foreground mb-6 flex items-center gap-2 text-sm font-black tracking-widest uppercase"
      >
        <i class="ph ph-image text-primary text-xl"></i>
        Cover Image
      </h4>

      <div class="space-y-4">
        <div
          class="group border-border hover:border-primary bg-muted/50 relative flex aspect-video items-center justify-center overflow-hidden rounded-2xl border-2 border-dashed transition-colors"
        >
          @if ($image)
            <img
              src="{{ $image->temporaryUrl() }}"
              class="absolute inset-0 h-full w-full object-contain"
            />
          @elseif ($post && $post->image_url)
            <img
              src="{{ $post->image_url }}"
              class="absolute inset-0 h-full w-full object-contain"
            />
          @else
            <div class="p-6 text-center">
              <i
                class="ph ph-cloud-arrow-up text-muted-foreground group-hover:text-primary text-3xl transition-colors"
              ></i>
              <p class="text-muted-foreground mt-2 text-xs font-bold">Click to upload</p>
            </div>
          @endif

          <div
            wire:loading
            wire:target="image"
            class="bg-background/80 absolute inset-0 z-10 grid place-items-center backdrop-blur-sm"
          >
            <div class="flex flex-col items-center">
              <div
                class="border-primary h-12 w-12 animate-spin rounded-full border-4 border-t-transparent"
              ></div>
              <p class="text-primary mt-4 animate-pulse text-xs font-black tracking-widest uppercase">Uploading...</p>
            </div>
          </div>

          <input
            type="file"
            wire:model="image"
            class="absolute inset-0 cursor-pointer opacity-0"
            accept="image/*"
          />
        </div>
        <p class="text-muted-foreground text-[10px] font-medium italic">Recommended size: 1200x630px (16:9) or 1200x514px (21:9) for optimal cinematic display.</p>
        @error ('image')
          <p class="mt-2 text-sm font-bold text-red-500">{{ $message }}</p>
        @enderror
      </div>
    </div>

    <!-- Tags Box -->
    <div class="bg-card ring-border rounded-[2.5rem] p-8 shadow-sm ring-1">
      <h4
        class="text-foreground mb-6 flex items-center gap-2 text-sm font-black tracking-widest uppercase"
      >
        <i class="ph ph-hash text-primary text-xl"></i>
        Tags
      </h4>
      <x-tag-input name="tags" id="tags" :value="$tags" :suggestions="$availableTags" />
      @error ('tags')
        <p class="mt-2 text-sm font-bold text-red-500">{{ $message }}</p>
      @enderror
    </div>
  </div>
</form>
</div>

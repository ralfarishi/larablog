<div
  class="bg-card border-border overflow-hidden rounded-3xl border shadow-sm transition-all duration-300"
>
  <!-- Table Header & Search & Quick Add -->
  <div
    class="border-border flex flex-col items-center justify-between gap-6 border-b p-8 md:flex-row"
  >
    <div class="flex items-center gap-4">
      <div class="bg-primary/10 flex h-12 w-12 items-center justify-center rounded-2xl">
        <i class="ph ph-folders text-primary text-2xl font-black"></i>
      </div>
      <div>
        <h3 class="text-foreground text-lg font-black tracking-tight">Taxonomy.</h3>
        <p class="text-muted-foreground text-xs font-bold tracking-widest uppercase">Content Structure</p>
      </div>
    </div>

    <div class="flex w-full flex-col gap-4 md:w-auto md:flex-row md:items-center">
      <!-- Search -->
      <div class="relative w-full md:w-64">
        <span class="text-muted-foreground absolute inset-y-0 left-5 flex items-center">
          <i class="ph ph-magnifying-glass text-lg"></i>
        </span>
        <input
          type="text"
          wire:model.live.debounce.300ms="search"
          class="border-border bg-muted/30 focus:border-primary focus:bg-background focus:ring-primary/10 w-full rounded-2xl py-2.5 pr-4 pl-12 text-sm font-medium transition-all focus:ring-4"
          placeholder="Filter taxonomy..."
        />
      </div>

      <!-- Quick Add Input -->
      <div x-data="{ open: false }" @category-created.window="open = false" class="relative">
        <button
          @click="open = !open"
          class="bg-primary text-primary-foreground hover:shadow-primary/20 inline-flex h-11 items-center gap-2 rounded-xl px-5 text-xs font-black tracking-widest uppercase shadow-lg transition-all hover:scale-[1.02] active:scale-95"
        >
          <i class="ph ph-plus-circle text-lg"></i>
          Quick Add
        </button>

        <div
          x-show="open"
          x-cloak
          x-transition
          @click.outside="open = false"
          class="bg-card ring-border absolute right-0 z-50 mt-4 w-72 rounded-4xl p-6 shadow-2xl ring-1"
        >
          <h4 class="text-foreground mb-4 text-sm font-black">New Category</h4>
          <form wire:submit="createCategory" class="space-y-4">
            <input
              type="text"
              wire:model="new_category_name"
              class="bg-muted focus:border-primary text-foreground w-full rounded-xl border-2 border-transparent px-4 py-2.5 text-sm font-bold focus:outline-none"
              placeholder="Category name..."
              required
            />
            @error ('new_category_name')
              <span class="text-xs font-bold text-red-500">{{ $message }}</span>
            @enderror
            <button
              type="submit"
              class="bg-primary text-primary-foreground w-full rounded-xl py-2.5 text-xs font-black tracking-widest uppercase shadow-md transition-opacity hover:opacity-90"
            >
              Save Category
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Table Content -->
  <div class="overflow-x-auto">
    <table class="w-full border-collapse text-left">
      <thead>
        <tr class="bg-muted/30">
          <th class="text-muted-foreground px-6 py-4 text-xs font-black tracking-widest uppercase">
            #
          </th>
          <th class="text-muted-foreground px-6 py-4 text-xs font-black tracking-widest uppercase">
            Category Name
          </th>
          <th class="text-muted-foreground px-6 py-4 text-xs font-black tracking-widest uppercase">
            Slug
          </th>
          <th
            class="text-muted-foreground px-6 py-4 text-center text-xs font-black tracking-widest uppercase"
          >
            Articles
          </th>
          <th
            class="text-muted-foreground px-6 py-4 text-right text-xs font-black tracking-widest uppercase"
          >
            Actions
          </th>
        </tr>
      </thead>
      <tbody class="divide-border divide-y">
        @forelse ($categories as $index => $category)
          <tr
            wire:key="category-{{ $category->id }}"
            class="group hover:bg-muted/20 transition-colors"
          >
            <td class="text-muted-foreground px-6 py-4 text-sm font-bold">
              {{ $categories->firstItem() + $index }}
            </td>
            <td class="px-6 py-4">
              @if ($editingCategoryId === $category->id)
                <div class="flex items-center gap-2">
                  <input
                    type="text"
                    wire:model="editingCategoryName"
                    wire:keydown.enter="updateCategory"
                    wire:keydown.escape="cancelEditing"
                    class="border-border bg-background focus:border-primary focus:ring-primary/10 w-full rounded-xl px-3 py-1.5 text-sm font-bold transition-all outline-none focus:ring-4"
                    autofocus
                  />
                  <button
                    type="button"
                    wire:click="updateCategory"
                    class="bg-primary flex size-8 items-center justify-center rounded-lg text-white shadow-sm transition-all hover:opacity-90"
                    title="Save"
                  >
                    <i class="ph ph-check text-base"></i>
                  </button>
                  <button
                    type="button"
                    wire:click="cancelEditing"
                    class="bg-muted text-foreground hover:bg-background flex size-8 items-center justify-center rounded-lg transition-all"
                    title="Cancel"
                  >
                    <i class="ph ph-x text-base"></i>
                  </button>
                </div>
              @else
                <div class="flex flex-col">
                  <span class="text-foreground text-sm font-black">{{ $category->name }}</span>
                </div>
              @endif
            </td>
            <td class="text-foreground px-6 py-4 text-sm font-medium italic">
              /{{ $category->slug }}
            </td>
            <td class="px-6 py-4 text-center">
              <span
                class="bg-muted text-foreground inline-flex size-8 items-center justify-center rounded-lg text-xs font-black"
              >
                {{ $category->posts_count }}
              </span>
            </td>
            <td class="px-6 py-4 text-right">
              <div
                class="flex justify-end gap-2 opacity-100 transition-opacity md:opacity-0 md:group-hover:opacity-100"
              >
                <button
                  type="button"
                  wire:click="startEditing({{ $category->id }}, '{{ addslashes($category->name) }}')"
                  class="bg-muted text-foreground hover:bg-primary hover:text-primary-foreground flex size-9 items-center justify-center rounded-xl transition-all"
                  title="Edit Name"
                >
                  <i class="ph ph-note-pencil text-lg"></i>
                </button>
                <button
                  type="button"
                  wire:click="$dispatch('open-confirm-modal', { componentId: '{{ $this->getName() }}', action: 'deleteConfirmed', id: {{ $category->id }}, title: 'Delete Category', message: 'Are you sure you want to delete this category? This action is permanent. Categories with existing articles cannot be deleted.' })"
                  class="bg-muted text-foreground hover:bg-destructive hover:text-destructive-foreground flex size-9 items-center justify-center rounded-xl transition-all"
                  title="Delete"
                >
                  <i class="ph ph-trash text-lg"></i>
                </button>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="px-6 py-12 text-center">
              <div class="flex flex-col items-center gap-2">
                <i class="ph ph-folders text-muted-foreground text-4xl opacity-20"></i>
                <span class="text-muted-foreground text-sm font-bold">No categories found.</span>
              </div>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <!-- Table Footer / Pagination -->
  <div class="border-border border-t p-6">{{ $categories->links() }}</div>
</div>

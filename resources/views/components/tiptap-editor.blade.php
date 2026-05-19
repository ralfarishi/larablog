<div
  class="tiptap-editor-wrapper border-border focus-within:border-primary bg-background overflow-hidden rounded-2xl border-2 transition-all"
  x-data="tiptapEditor({
        id: @js($id),
        wireModel: @js($attributes->get('wire:model')),
        initialContent: @js($value)
    })"
  @submit.window="clearDraft()"
>
  <!-- Toolbar -->
  <div class="tiptap-toolbar bg-muted/50 border-border z-60 flex flex-wrap gap-1 border-b p-2">
    <div class="bg-background/50 flex items-center gap-1 rounded-xl p-1">
      <button
        type="button"
        @click="undo()"
        class="hover:bg-background rounded-lg p-2 transition-colors"
        title="Undo"
      >
        <i class="ph ph-arrow-counter-clockwise text-lg"></i>
      </button>
      <button
        type="button"
        @click="redo()"
        class="hover:bg-background rounded-lg p-2 transition-colors"
        title="Redo"
      >
        <i class="ph ph-arrow-clockwise text-lg"></i>
      </button>
    </div>

    <div class="bg-border mx-1 h-6 w-px self-center"></div>

    <div class="bg-background/50 flex items-center gap-1 rounded-xl p-1">
      <button
        type="button"
        @click="toggleHeading(1)"
        :class="isActive('heading', { level: 1 })
          ? 'bg-primary text-white shadow-sm'
          : 'hover:bg-background'"
        class="rounded-lg p-2 transition-all"
        title="Heading 1"
      >
        <i class="ph ph-text-h-one text-lg font-bold"></i>
      </button>
      <button
        type="button"
        @click="toggleHeading(2)"
        :class="isActive('heading', { level: 2 })
          ? 'bg-primary text-white shadow-sm'
          : 'hover:bg-background'"
        class="rounded-lg p-2 transition-all"
        title="Heading 2"
      >
        <i class="ph ph-text-h-two text-lg font-bold"></i>
      </button>
      <button
        type="button"
        @click="toggleHeading(3)"
        :class="isActive('heading', { level: 3 })
          ? 'bg-primary text-white shadow-sm'
          : 'hover:bg-background'"
        class="rounded-lg p-2 transition-all"
        title="Heading 3"
      >
        <i class="ph ph-text-h-three text-lg font-bold"></i>
      </button>
    </div>

    <div class="bg-border mx-1 h-6 w-px self-center"></div>

    <div class="bg-background/50 flex items-center gap-1 rounded-xl p-1">
      <button
        type="button"
        @click="toggleBold()"
        :class="isActive('bold') ? 'bg-primary text-white shadow-sm' : 'hover:bg-background'"
        class="rounded-lg p-2 transition-all"
        title="Bold"
      >
        <i class="ph ph-text-b text-lg font-bold"></i>
      </button>
      <button
        type="button"
        @click="toggleItalic()"
        :class="isActive('italic') ? 'bg-primary text-white shadow-sm' : 'hover:bg-background'"
        class="rounded-lg p-2 transition-all"
        title="Italic"
      >
        <i class="ph ph-text-italic text-lg font-bold"></i>
      </button>
      <button
        type="button"
        @click="toggleUnderline()"
        :class="isActive('underline') ? 'bg-primary text-white shadow-sm' : 'hover:bg-background'"
        class="rounded-lg p-2 transition-all"
        title="Underline"
      >
        <i class="ph ph-text-underline text-lg font-bold"></i>
      </button>
      <button
        type="button"
        @click="toggleStrike()"
        :class="isActive('strike') ? 'bg-primary text-white shadow-sm' : 'hover:bg-background'"
        class="rounded-lg p-2 transition-all"
        title="Strike"
      >
        <i class="ph ph-text-strikethrough text-lg font-bold"></i>
      </button>
      <button
        type="button"
        @click="toggleCode()"
        :class="isActive('code') ? 'bg-primary text-white shadow-sm' : 'hover:bg-background'"
        class="rounded-lg p-2 transition-all"
        title="Inline Code"
      >
        <i class="ph ph-code text-lg font-bold"></i>
      </button>
      <button
        type="button"
        @click="toggleLink()"
        :class="isActive('link') ? 'bg-primary text-white shadow-sm' : 'hover:bg-background'"
        class="rounded-lg p-2 transition-all"
        title="Insert Link"
      >
        <i class="ph ph-link text-lg font-bold"></i>
      </button>
    </div>

    <div class="bg-border mx-1 h-6 w-px self-center"></div>

    <div class="bg-background/50 flex items-center gap-1 rounded-xl p-1">
      <button
        type="button"
        @click="toggleBulletList()"
        :class="isActive('bulletList') ? 'bg-primary text-white shadow-sm' : 'hover:bg-background'"
        class="rounded-lg p-2 transition-all"
        title="Bullet List"
      >
        <i class="ph ph-list-bullets text-lg font-bold"></i>
      </button>
      <button
        type="button"
        @click="toggleOrderedList()"
        :class="isActive('orderedList') ? 'bg-primary text-white shadow-sm' : 'hover:bg-background'"
        class="rounded-lg p-2 transition-all"
        title="Ordered List"
      >
        <i class="ph ph-list-numbers text-lg font-bold"></i>
      </button>
      <button
        type="button"
        @click="toggleBlockquote()"
        :class="isActive('blockquote') ? 'bg-primary text-white shadow-sm' : 'hover:bg-background'"
        class="rounded-lg p-2 transition-all"
        title="Blockquote"
      >
        <i class="ph ph-quotes text-lg font-bold"></i>
      </button>
      <button
        type="button"
        @click="toggleCodeBlock()"
        :class="isActive('codeBlock') ? 'bg-primary text-white shadow-sm' : 'hover:bg-background'"
        class="rounded-lg p-2 transition-all"
        title="Code Block"
      >
        <i class="ph ph-terminal-window text-lg font-bold"></i>
      </button>
    </div>

    <div class="bg-border mx-1 h-6 w-px self-center"></div>

    <div class="bg-background/50 flex items-center gap-1 rounded-xl p-1" x-data="{ open: false }">
      <button
        type="button"
        @click="insertTable()"
        class="hover:bg-background rounded-lg p-2 transition-colors"
        title="Insert Table"
      >
        <i class="ph ph-table text-lg"></i>
      </button>
      <template x-if="isActive('table')">
        <div class="flex items-center gap-1">
          <button
            type="button"
            @click="addColumnAfter()"
            class="hover:bg-background rounded-lg p-2 transition-colors"
            title="Add Column"
          >
            <i class="ph ph-columns text-lg"></i>
          </button>
          <button
            type="button"
            @click="addRowAfter()"
            class="hover:bg-background rounded-lg p-2 transition-colors"
            title="Add Row"
          >
            <i class="ph ph-rows text-lg"></i>
          </button>
          <button
            type="button"
            @click="deleteTable()"
            class="rounded-lg p-2 transition-colors hover:bg-red-500/10 hover:text-red-500"
            title="Delete Table"
          >
            <i class="ph ph-trash text-lg"></i>
          </button>
        </div>
      </template>
    </div>

    <div class="bg-border mx-1 h-6 w-px self-center"></div>

    <div class="bg-background/50 flex items-center gap-1 rounded-xl p-1">
      <button
        type="button"
        @click="addImage()"
        class="hover:bg-background rounded-lg p-2 transition-colors"
        title="Add Image"
      >
        <i class="ph ph-image text-lg"></i>
      </button>
    </div>
  </div>

  <!-- Custom Modal for Link & Image -->
  <div
    x-show="modalOpen"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    style="display: none"
    class="fixed inset-0 z-[999] flex items-center justify-center overflow-y-auto p-4"
    role="dialog"
    aria-modal="true"
  >
    <!-- Backdrop -->
    <div class="bg-background/95 fixed inset-0 backdrop-blur-xl" @click="closeModal()"></div>

    <!-- Modal Content -->
    <div
      class="bg-card ring-border relative w-full max-w-md transform rounded-[2rem] p-8 shadow-2xl ring-1 transition-all"
      x-transition:enter="transition ease-out duration-200"
      x-transition:enter-start="opacity-0 scale-95 translate-y-4"
      x-transition:enter-end="opacity-100 scale-100 translate-y-0"
    >
      <div class="mb-6 flex items-center justify-between">
        <h3 class="text-foreground text-lg font-black" x-text="modalTitle"></h3>
        <button
          type="button"
          @click="closeModal()"
          class="hover:bg-muted flex h-8 w-8 items-center justify-center rounded-full transition-colors"
        >
          <i class="ph ph-x text-lg"></i>
        </button>
      </div>

      <div class="space-y-4">
        <div>
          <label
            class="text-muted-foreground mb-2 block text-sm font-bold tracking-widest uppercase"
          >
            <span x-text="modalType === 'link' ? 'URL' : 'Image URL'"></span>
          </label>
          <input
            type="url"
            id="tiptap-modal-input"
            x-model="modalValue"
            @keydown.enter="submitModal()"
            @keydown.escape="closeModal()"
            class="bg-muted focus:border-primary text-foreground placeholder:text-muted-foreground/50 w-full rounded-xl border-2 border-transparent px-4 py-3 transition-all focus:outline-none"
            :placeholder="modalType === 'link'
              ? 'https://example.com'
              : 'https://example.com/image.jpg'"
          />
        </div>

        <div class="flex gap-3 pt-2">
          <button
            type="button"
            @click="closeModal()"
            class="bg-muted text-foreground hover:bg-muted/80 flex-1 rounded-xl px-4 py-3 font-bold transition-colors"
          >
            Cancel
          </button>
          <button
            type="button"
            @click="submitModal()"
            class="bg-primary text-primary-foreground flex-1 rounded-xl px-4 py-3 font-bold transition-opacity hover:opacity-90"
          >
            Insert
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Editor Content -->
  <div id="tiptap-el-{{ $id }}" class="tiptap-content"></div>

  <!-- Hidden Input for Form -->
  <input type="hidden" name="{{ $name }}" id="tiptap-input-{{ $id }}" value="{{ $value }}" />
</div>

<script>
  if (typeof window.tiptapEditor !== 'function') {
    window.tiptapEditor = function (config) {
      return {
        editor: null,
        updated: 0,
        modalOpen: false,
        modalType: '',
        modalValue: '',
        modalTitle: '',
        init() {
          const id = config.id;
          const inputEl = document.getElementById('tiptap-input-' + id);
          const initialContent = inputEl ? inputEl.value : '';

          const savedDraft = localStorage.getItem('draft-' + id);
          let contentToLoad = initialContent;

          // If we have a draft and no real initial content, use the draft
          if (
            savedDraft &&
            (!initialContent ||
              initialContent === '{}' ||
              initialContent === '""' ||
              initialContent === 'null')
          ) {
            contentToLoad = savedDraft;
          }

          const setupCallback = () => {
            this.updated++;
            const content = document.getElementById('tiptap-input-' + id).value;
            localStorage.setItem('draft-' + id, content);

            // Livewire sync
            if (window.Livewire && config.wireModel) {
              this.$wire.set(config.wireModel, content);
            }
          };

          try {
            this.editor = window.setupTiptap(
              'tiptap-el-' + id,
              'tiptap-input-' + id,
              contentToLoad,
              setupCallback,
            );

            // Immediately sync back to Livewire on load if draft was used
            if (contentToLoad === savedDraft && window.Livewire && config.wireModel) {
              this.$wire.set(config.wireModel, savedDraft);
            }
          } catch (e) {
            console.error(
              'Failed to parse Tiptap draft (Mismatched Transaction). Clearing corrupt draft.',
              e,
            );
            localStorage.removeItem('draft-' + id);

            // Fallback to original content
            this.editor = window.setupTiptap(
              'tiptap-el-' + id,
              'tiptap-input-' + id,
              initialContent,
              setupCallback,
            );
          }
        },
        openModal(type, title, defaultValue = '') {
          this.modalType = type;
          this.modalTitle = title;
          this.modalValue = defaultValue;
          this.modalOpen = true;
          this.$nextTick(() => {
            document.getElementById('tiptap-modal-input')?.focus();
          });
        },
        closeModal() {
          this.modalOpen = false;
          this.modalValue = '';
          this.modalType = '';
        },
        submitModal() {
          const value = this.modalValue.trim();
          if (this.modalType === 'link') {
            const editor = this.getEditor();
            if (!editor) return;
            if (value === '') {
              editor.chain().focus().unsetLink().run();
            } else {
              editor.chain().focus().extendMarkRange('link').setLink({ href: value }).run();
            }
          } else if (this.modalType === 'image') {
            const editor = this.getEditor();
            if (editor && value) {
              editor.chain().focus().setImage({ src: value }).run();
            }
          }
          this.closeModal();
        },
        getEditor() {
          return window.tiptapEditors['tiptap-el-' + config.id] || this.editor;
        },
        toggleBold() {
          this.getEditor()?.chain().focus().toggleBold().run();
        },
        toggleLink() {
          const previousUrl = this.getEditor()?.getAttributes('link').href;
          this.openModal('link', 'Insert Link URL', previousUrl || 'https://');
        },
        toggleItalic() {
          this.getEditor()?.chain().focus().toggleItalic().run();
        },
        toggleUnderline() {
          this.getEditor()?.chain().focus().toggleUnderline().run();
        },
        toggleStrike() {
          this.getEditor()?.chain().focus().toggleStrike().run();
        },
        toggleCode() {
          this.getEditor()?.chain().focus().toggleCode().run();
        },
        toggleHeading(level) {
          this.getEditor()?.chain().focus().toggleHeading({ level }).run();
        },
        toggleBulletList() {
          this.getEditor()?.chain().focus().toggleBulletList().run();
        },
        toggleOrderedList() {
          this.getEditor()?.chain().focus().toggleOrderedList().run();
        },
        toggleBlockquote() {
          this.getEditor()?.chain().focus().toggleBlockquote().run();
        },
        toggleCodeBlock() {
          this.getEditor()?.chain().focus().toggleCodeBlock().run();
        },
        undo() {
          this.getEditor()?.chain().focus().undo().run();
        },
        redo() {
          this.getEditor()?.chain().focus().redo().run();
        },
        insertTable() {
          this.getEditor()
            ?.chain()
            .focus()
            .insertTable({ rows: 3, cols: 3, withHeaderRow: true })
            .run();
        },
        addColumnAfter() {
          this.getEditor()?.chain().focus().addColumnAfter().run();
        },
        deleteColumn() {
          this.getEditor()?.chain().focus().deleteColumn().run();
        },
        addRowAfter() {
          this.getEditor()?.chain().focus().addRowAfter().run();
        },
        deleteRow() {
          this.getEditor()?.chain().focus().deleteRow().run();
        },
        deleteTable() {
          this.getEditor()?.chain().focus().deleteTable().run();
        },
        addImage() {
          this.openModal('image', 'Insert Image URL', 'https://');
        },
        isActive(type, opts = {}) {
          this.updated;
          return this.getEditor() ? this.getEditor().isActive(type, opts) : false;
        },
        clearDraft() {
          localStorage.removeItem('draft-' + config.id);
        },
      };
    };
  }
</script>

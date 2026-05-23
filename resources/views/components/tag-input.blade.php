@props ([
  'name' => 'tags',
  'id' => 'tags',
  'value' => '',
  'suggestions' => []
])

{{-- Hidden input that actually submits the comma-separated tag string --}}
<input
  type="hidden"
  name="{{ $name }}"
  id="{{ $id }}"
  wire:model="{{ $name }}"
  value="{{ old($name, $value) }}"
/>

{{-- Wrapper with autocomplete dropdown --}}
<div class="relative" id="{{ $id }}-root" wire:ignore>
  {{-- Visual tag input container --}}
  <div
    id="{{ $id }}-wrapper"
    class="border-border bg-background focus-within:border-primary focus-within:ring-primary/20 flex min-h-[48px] w-full cursor-text flex-wrap gap-2 rounded-2xl border-2 px-3 py-2.5 transition-all focus-within:ring-4"
    onclick="document.getElementById('{{ $id }}-field').focus()"
  >
    {{-- Tag chips render here via JS --}}
    <div id="{{ $id }}-chips" class="flex flex-wrap items-center gap-1.5"></div>

    {{-- Typing input --}}
    <input
      type="text"
      id="{{ $id }}-field"
      placeholder="Type a tag…"
      autocomplete="off"
      class="text-foreground placeholder:text-muted-foreground min-w-[140px] flex-1 border-none bg-transparent py-0.5 text-sm outline-none"
    />
  </div>

  {{-- Autocomplete dropdown --}}
  <div
    id="{{ $id }}-dropdown"
    class="bg-card ring-border absolute top-full right-0 left-0 z-50 mt-1.5 hidden max-h-52 overflow-y-auto rounded-2xl py-2 shadow-xl ring-1"
    role="listbox"
  ></div>
</div>

<script>
  (function () {
    const hiddenInput = document.getElementById('{{ $id }}');
    const chipsEl = document.getElementById('{{ $id }}-chips');
    const field = document.getElementById('{{ $id }}-field');
    const dropdown = document.getElementById('{{ $id }}-dropdown');
    const suggestions = @json ($suggestions);

    let tags = hiddenInput.value
      ? hiddenInput.value
          .split(',')
          .map((t) => t.trim())
          .filter(Boolean)
      : [];
    let activeIndex = -1;

    // ── Core helpers ────────────────────────────────────────────────

    function escapeHtml(str) {
      return str
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
    }

    function normalise(raw) {
      return raw.trim().toLowerCase().replace(/\s+/g, '-');
    }

    function sync() {
      hiddenInput.value = tags.join(',');
      // Livewire sync
      hiddenInput.dispatchEvent(new Event('input', { bubbles: true }));
    }

    // ── Chips ────────────────────────────────────────────────────────

    function renderChip(tag, index) {
      const chip = document.createElement('span');
      chip.className = [
        'inline-flex items-center gap-1 px-3 py-1 rounded-lg',
        'bg-primary/10 text-primary text-xs font-bold',
        'border border-primary/20 group hover:bg-primary/20 transition-all',
      ].join(' ');

      chip.innerHTML = `
              <i class="ph ph-hash text-[10px] opacity-60"></i>
              <span>${escapeHtml(tag)}</span>
              <button type="button"
                  class="ml-0.5 w-3.5 h-3.5 rounded-full flex items-center justify-center text-primary/60 hover:text-red-500 hover:bg-red-100 transition-colors"
                  data-index="${index}"
                  aria-label="Remove tag ${escapeHtml(tag)}"
              ><i class="ph ph-x text-[9px]"></i></button>
          `;

      chip.querySelector('button').addEventListener('click', function () {
        tags.splice(Number(this.dataset.index), 1);
        render();
        sync();
      });

      return chip;
    }

    function render() {
      chipsEl.innerHTML = '';
      tags.forEach((tag, i) => chipsEl.appendChild(renderChip(tag, i)));
    }

    function addTag(raw) {
      const tag = normalise(raw);
      if (!tag || tags.includes(tag)) return;
      tags.push(tag);
      render();
      sync();
    }

    // ── Autocomplete dropdown ────────────────────────────────────────

    function getMatches(query) {
      if (!query) return [];
      const q = query.toLowerCase();
      return suggestions
        .filter((s) => normalise(s).includes(q) && !tags.includes(normalise(s)))
        .slice(0, 8);
    }

    function highlightMatch(str, query) {
      const normalStr = normalise(str);
      const normalQ = query.toLowerCase();
      const idx = normalStr.indexOf(normalQ);
      if (idx === -1) return escapeHtml(str);
      return (
        escapeHtml(str.slice(0, idx)) +
        `<mark class="bg-primary/20 text-primary rounded font-black not-italic">${escapeHtml(str.slice(idx, idx + query.length))}</mark>` +
        escapeHtml(str.slice(idx + query.length))
      );
    }

    function showDropdown(matches, query) {
      if (!matches.length) {
        hideDropdown();
        return;
      }

      activeIndex = -1;
      dropdown.innerHTML = '';

      matches.forEach((match, i) => {
        const item = document.createElement('div');
        item.setAttribute('role', 'option');
        item.setAttribute('data-value', normalise(match));
        item.className =
          'flex items-center gap-2 px-4 py-2.5 text-sm font-bold text-foreground cursor-pointer hover:bg-muted transition-colors rounded-xl mx-1';
        item.innerHTML = `<i class="ph ph-hash text-primary opacity-60 text-xs"></i>${highlightMatch(match, query)}`;

        item.addEventListener('mousedown', function (e) {
          e.preventDefault(); // keep focus in field
          addTag(this.dataset.value);
          field.value = '';
          hideDropdown();
        });

        dropdown.appendChild(item);
      });

      dropdown.classList.remove('hidden');
    }

    function hideDropdown() {
      dropdown.classList.add('hidden');
      activeIndex = -1;
    }

    function navigateDropdown(dir) {
      const items = dropdown.querySelectorAll('[role=option]');
      if (!items.length) return;

      items[activeIndex]?.classList.remove('bg-muted');
      activeIndex = (activeIndex + dir + items.length) % items.length;
      items[activeIndex].classList.add('bg-muted');
      items[activeIndex].scrollIntoView({ block: 'nearest' });
    }

    // ── Events ───────────────────────────────────────────────────────

    field.addEventListener('input', function () {
      const val = this.value.replace(/,/g, '').trim();
      const matches = getMatches(val);
      showDropdown(matches, val);
    });

    field.addEventListener('keydown', function (e) {
      if (e.key === 'ArrowDown') {
        e.preventDefault();
        navigateDropdown(1);
        return;
      }
      if (e.key === 'ArrowUp') {
        e.preventDefault();
        navigateDropdown(-1);
        return;
      }

      if (e.key === 'Enter' || e.key === ',') {
        e.preventDefault();
        const items = dropdown.querySelectorAll('[role=option]');
        if (activeIndex >= 0 && items[activeIndex]) {
          addTag(items[activeIndex].dataset.value);
        } else {
          const val = this.value.replace(/,/g, '').trim();
          if (val) addTag(val);
        }
        this.value = '';
        hideDropdown();
        return;
      }

      if (e.key === 'Escape') {
        hideDropdown();
        return;
      }

      if (e.key === 'Backspace' && this.value === '' && tags.length) {
        tags.pop();
        render();
        sync();
      }
    });

    field.addEventListener('blur', function () {
      // small delay so mousedown on item fires first
      setTimeout(() => {
        const val = this.value.replace(/,/g, '').trim();
        if (val) {
          addTag(val);
          this.value = '';
        }
        hideDropdown();
      }, 150);
    });

    // Handle paste — split on commas/newlines
    field.addEventListener('paste', function (e) {
      e.preventDefault();
      const pasted = (e.clipboardData || window.clipboardData).getData('text');
      pasted.split(/[,\n]+/).forEach((part) => {
        if (part.trim()) addTag(part);
      });
      this.value = '';
      hideDropdown();
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function (e) {
      if (!document.getElementById('{{ $id }}-root').contains(e.target)) {
        hideDropdown();
      }
    });

    // Boot: render existing tags
    render();
  })();
</script>

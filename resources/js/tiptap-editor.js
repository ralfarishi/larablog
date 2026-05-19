import { Editor } from '@tiptap/core';
import StarterKit from '@tiptap/starter-kit';
import Link from '@tiptap/extension-link';
import Image from '@tiptap/extension-image';
import Underline from '@tiptap/extension-underline';
import Placeholder from '@tiptap/extension-placeholder';
import { Table } from '@tiptap/extension-table';
import { TableRow } from '@tiptap/extension-table-row';
import { TableCell } from '@tiptap/extension-table-cell';
import { TableHeader } from '@tiptap/extension-table-header';

window.setupTiptap = function (
  elementId,
  contentInputId,
  initialContent = '',
  onUpdateCallback = null,
) {
  const contentInput = document.getElementById(contentInputId);

  let content = initialContent;
  try {
    if (
      typeof initialContent === 'string' &&
      (initialContent.startsWith('{') || initialContent.startsWith('['))
    ) {
      content = JSON.parse(initialContent);
    }
  } catch (e) {
    content = initialContent;
  }

  const editor = new Editor({
    element: document.getElementById(elementId),
    extensions: [
      StarterKit,
      Link.configure({
        openOnClick: false,
        HTMLAttributes: {
          class: 'text-primary underline underline-offset-4 decoration-2',
        },
      }),
      Underline,
      Image.configure({
        HTMLAttributes: {
          class: 'rounded-3xl shadow-xl border border-border mx-auto my-8 max-w-full',
        },
      }),
      Table.configure({
        resizable: true,
        HTMLAttributes: {
          class: 'tiptap-table',
        },
      }),
      TableRow,
      TableHeader,
      TableCell,
      Placeholder.configure({
        placeholder: 'Write something remarkable...',
      }),
    ],
    content: content,
    editorProps: {
      attributes: {
        class:
          'prose prose-zinc prose-lg md:prose-xl max-w-none focus:outline-none min-h-[500px] px-8 py-10',
      },
    },
    shouldRerenderOnTransaction: true,
    onUpdate: ({ editor }) => {
      contentInput.value = JSON.stringify(editor.getJSON());
      if (onUpdateCallback) onUpdateCallback();
    },
    onSelectionUpdate: () => {
      if (onUpdateCallback) onUpdateCallback();
    },
    onTransaction: () => {
      if (onUpdateCallback) onUpdateCallback();
    },
  });

  // Expose editor globally for Alpine to access
  window.tiptapEditors = window.tiptapEditors || {};
  window.tiptapEditors[elementId] = editor;

  return editor;
};

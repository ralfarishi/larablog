<?php

declare(strict_types=1);

namespace App\Support;

class ContentRenderer
{
  /**
   * Minimal server-side renderer for Tiptap JSON.
   * Since composer installation failed, we use a simple parser
   * or fallback to raw string if it's legacy HTML.
   */
  public static function render($content): string
  {
    if (empty($content)) {
      return '';
    }

    if (!self::isJson($content)) {
      // Clean up any stray backticks from markdown-style code
      $content = self::cleanBackticks($content);

      // Sanitize legacy HTML — strip dangerous tags, keep safe formatting
      return strip_tags(
        (string) $content,
        '<p><br><strong><em><u><s><a><ul><ol><li><h1><h2><h3><h4><h5><h6><code><pre><blockquote><img><hr>',
      );
    }

    $data = json_decode($content, true);

    if (!isset($data['type']) || $data['type'] !== 'doc') {
      return (string) $content;
    }

    return self::parseNode($data);
  }

  private static function cleanBackticks(string $html): string
  {
    // Replace markdown-style inline code (backticks) with clean <code> tags
    $html = preg_replace('/`([^`]+)`/i', '<code>$1</code>', $html);

    // Replace triple backticks with clean <pre><code> blocks
    $html = preg_replace('/```(\w*)\n?([^`]*?)```/i', '<pre><code>$2</code></pre>', $html);

    return $html;
  }

  private static function parseNode(array $node): string
  {
    $html = '';

    if (isset($node['content'])) {
      foreach ($node['content'] as $child) {
        $html .= self::renderNode($child);
      }
    }

    return $html;
  }

  private static function renderNode(array $node): string
  {
    $type = $node['type'] ?? 'text';
    $content = '';

    if (isset($node['content'])) {
      foreach ($node['content'] as $child) {
        $content .= self::renderNode($child);
      }
    }

    if ($type === 'text') {
      $text = htmlspecialchars($node['text'] ?? '');
      if (isset($node['marks'])) {
        foreach ($node['marks'] as $mark) {
          $text = self::applyMark($mark, $text);
        }
      }

      return $text;
    }

    $attributes = self::getAttributes($node['attrs'] ?? []);

    switch ($type) {
      case 'paragraph':
        return "<p class=\"mb-4 text-muted-foreground leading-relaxed\">{$content}</p>";
      case 'heading':
        $level = $node['attrs']['level'] ?? 1;
        $headingClass = match ($level) {
          1 => 'text-3xl md:text-4xl font-black text-foreground mb-6 mt-10',
          2 => 'text-2xl md:text-3xl font-bold text-foreground mb-4 mt-8',
          default => 'text-xl md:text-2xl font-bold text-foreground mb-3 mt-6',
        };

        return "<h{$level} class=\"{$headingClass}\">{$content}</h{$level}>";
      case 'bulletList':
        return "<ul class=\"list-disc list-inside mb-4 space-y-2 text-muted-foreground pl-4\">{$content}</ul>";
      case 'orderedList':
        return "<ol class=\"list-decimal list-inside mb-4 space-y-2 text-muted-foreground pl-4\">{$content}</ol>";
      case 'listItem':
        return "<li class=\"text-muted-foreground\">{$content}</li>";
      case 'blockquote':
        return "<blockquote class=\"border-l-4 border-primary pl-6 py-3 my-6 italic text-lg text-muted-foreground bg-muted/30 rounded-r-lg\">{$content}</blockquote>";
      case 'codeBlock':
        $escapedContent = htmlspecialchars($content);

        return '<pre class="bg-muted/50 text-foreground border border-border/50 rounded-xl p-4 md:p-5 my-6 text-[13px] sm:text-sm font-mono leading-relaxed whitespace-pre-wrap wrap-break-word"><code>' .
          $escapedContent .
          '</code></pre>';
      case 'image':
        $src = htmlspecialchars($node['attrs']['src'] ?? '', ENT_QUOTES, 'UTF-8');
        $alt = htmlspecialchars($node['attrs']['alt'] ?? '', ENT_QUOTES, 'UTF-8');

        return "<img src=\"{$src}\" alt=\"{$alt}\" class=\"rounded-3xl shadow-xl border border-border mx-auto my-8 max-w-full\">";

      case 'horizontalRule':
        return '<hr>';
      case 'hardBreak':
        return '<br>';
      default:
        return $content;
    }
  }

  private static function applyMark(array $mark, string $text): string
  {
    switch ($mark['type']) {
      case 'bold':
        return "<strong class=\"font-bold text-foreground\">{$text}</strong>";
      case 'italic':
        return "<em class=\"italic text-foreground\">{$text}</em>";
      case 'underline':
        return "<u class=\"underline decoration-primary decoration-2 underline-offset-2 text-foreground\">{$text}</u>";
      case 'strike':
        return "<s class=\"line-through text-muted-foreground\">{$text}</s>";
      case 'code':
        return '<code class="bg-muted text-foreground border border-border/50 rounded-md px-1.5 py-[2px] text-[0.825em] font-mono font-semibold inline-block align-middle max-w-full wrap-break-word leading-normal my-0.5">' .
          $text .
          '</code>';
      case 'link':
        $href = htmlspecialchars($mark['attrs']['href'] ?? '#');
        $target = $mark['attrs']['target'] ?? '_blank';
        $rel = $target === '_blank' ? 'noopener noreferrer' : '';

        return "<a href=\"{$href}\" class=\"text-primary underline underline-offset-4 decoration-2 hover:decoration-primary transition-colors\" target=\"{$target}\" rel=\"{$rel}\">{$text}</a>";
      default:
        return $text;
    }
  }

  private static function getAttributes(array $attrs): string
  {
    $html = '';
    foreach ($attrs as $key => $value) {
      $html .= " {$key}=\"" . htmlspecialchars((string) $value) . '"';
    }

    return $html;
  }

  private static function isJson($string): bool
  {
    if (!is_string($string)) {
      return false;
    }
    json_decode($string);

    return json_last_error() === JSON_ERROR_NONE;
  }
}

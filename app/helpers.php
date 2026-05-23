<?php

declare(strict_types=1);

use Carbon\Carbon;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Facades\Cache;

function formatDate(mixed $date): string
{
  $carbonDate = Carbon::parse($date);
  $carbonDate->setLocale('en');

  return $carbonDate->isSameDay(Carbon::now())
    ? $carbonDate->diffForHumans()
    : $carbonDate->format('d F Y');
}

function getSidebarData(): array
{
  return Cache::remember('sidebar_data', now()->addMinutes(30), function (): array {
    return [
      'categories' => Category::withCount([
        'posts' => fn($q) => $q->where('status', 'published'),
      ])->get(),
      // Only show tags that have at least one published article.
      // whereHas filters efficiently without a HAVING clause (cross-DB compatible).
      'tags' => Tag::whereHas('posts', fn($q) => $q->where('status', 'published'))
        ->withCount(['posts' => fn($q) => $q->where('status', 'published')])
        ->orderByDesc('posts_count')
        ->take(8)
        ->get(),
    ];
  });
}

function getParagraphTagOnly(mixed $s): ?string
{
  if (empty($s)) {
    return null;
  }

  // If it's Tiptap JSON, extract the first paragraph text
  if (str_starts_with($s, '{') || str_starts_with($s, '[')) {
    try {
      $data = json_decode($s, true);
      if (isset($data['content'])) {
        foreach ($data['content'] as $node) {
          if ($node['type'] === 'paragraph' && isset($node['content'])) {
            $text = '';
            foreach ($node['content'] as $child) {
              if ($child['type'] === 'text') {
                $text .= $child['text'];
              }
            }
            return $text;
          }
        }
      }
    } catch (\Exception) {
      // Fallback to HTML parsing if JSON decode fails
    }
  }

  $dom = new DOMDocument();
  libxml_use_internal_errors(true);
  $dom->loadHTML($s);
  libxml_clear_errors();

  $pTag = null;
  foreach ($dom->getElementsByTagName('p') as $p) {
    $pTag = $p->nodeValue;
    break;
  }

  return $pTag;
}

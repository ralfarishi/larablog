<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Support\Str;

class TagService
{
  /**
   * Parse a comma-separated tag string, upsert each tag,
   * and sync the pivot table for the given post.
   *
   * @param  Post  $post  The post to sync tags for
   * @param  string  $tagInput  Comma-separated tag names (e.g. "Laravel,PHP,Web")
   */
  public function sync(Post $post, string $tagInput): void
  {
    $tagIds = [];

    foreach (explode(',', $tagInput) as $tagName) {
      $tagName = trim($tagName);

      if ($tagName === '') {
        continue;
      }

      $tag = Tag::firstOrCreate(['name' => $tagName], ['slug' => Str::slug($tagName)]);

      $tagIds[] = $tag->id;
    }

    $post->tags()->sync($tagIds);
  }
}

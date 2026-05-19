<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MigrateTagsToRelational extends Command
{
  protected $signature = 'migrate:tags-to-relational';
  protected $description = 'Migrate existing comma-separated tags to the new relational structure';

  public function handle()
  {
    $posts = Post::all();
    $count = 0;

    foreach ($posts as $post) {
      if (empty($post->tags)) {
        continue;
      }

      $tagNames = explode(',', $post->tags);
      $tagIds = [];

      foreach ($tagNames as $name) {
        $name = trim($name);
        if (empty($name)) {
          continue;
        }

        $tag = Tag::firstOrCreate(['name' => $name], ['slug' => Str::slug($name)]);

        $tagIds[] = $tag->id;
      }

      $post->tags()->sync($tagIds);
      $count++;
    }

    $this->info("Successfully migrated tags for {$count} posts.");
  }
}

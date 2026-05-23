<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration {
  /**
   * Backfill the post_tag pivot table from the legacy posts.tags string column,
   * then drop that column entirely.
   *
   * For each post that has a non-empty tags string (e.g. "laravel,php,web"):
   *  1. Parse the CSV into individual tag names.
   *  2. Upsert a row in the `tags` table (creates the tag if it doesn't exist).
   *  3. Insert into `post_tag` pivot (ignores duplicates via insertOrIgnore).
   *
   * This is safe to run on a database that already has some pivot rows —
   * insertOrIgnore will skip any that were already synced.
   */
  public function up(): void
  {
    // Only run backfill if the legacy column still exists
    if (Schema::hasColumn('posts', 'tags')) {
      $posts = DB::table('posts')
        ->whereNotNull('tags')
        ->where('tags', '!=', '')
        ->select('id', 'tags')
        ->get();

      foreach ($posts as $post) {
        $tagNames = array_filter(array_map('trim', explode(',', (string) $post->tags)));

        foreach ($tagNames as $tagName) {
          if ($tagName === '') {
            continue;
          }

          // Upsert the tag — create if it doesn't exist
          $slug = Str::slug($tagName);

          // Handle slug collisions gracefully
          $existingTag = DB::table('tags')
            ->whereRaw('LOWER(name) = ?', [strtolower($tagName)])
            ->first();

          if ($existingTag) {
            $tagId = $existingTag->id;
          } else {
            // Ensure slug uniqueness
            $baseSlug = $slug;
            $counter = 1;
            while (DB::table('tags')->where('slug', $slug)->exists()) {
              $slug = $baseSlug . '-' . $counter++;
            }

            $tagId = DB::table('tags')->insertGetId([
              'name' => $tagName,
              'slug' => $slug,
              'created_at' => now(),
              'updated_at' => now(),
            ]);
          }

          // Insert pivot row — ignore if it already exists
          DB::table('post_tag')->insertOrIgnore([
            'post_id' => $post->id,
            'tag_id' => $tagId,
            'created_at' => now(),
            'updated_at' => now(),
          ]);
        }
      }

      // Drop the legacy column now that data is migrated
      Schema::table('posts', function (Blueprint $table) {
        $table->dropColumn('tags');
      });
    }
  }

  /**
   * There is no safe rollback — we cannot reconstruct the exact original
   * comma-separated strings from the pivot without loss of ordering.
   * The column is re-added as nullable so existing data isn't broken,
   * but it will be empty.
   */
  public function down(): void
  {
    Schema::table('posts', function (Blueprint $table) {
      $table->string('tags')->nullable()->after('image');
    });
  }
};

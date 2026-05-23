<?php

declare(strict_types=1);

/**
 * One-time script to backfill reading_time for all existing posts.
 * Run via: php artisan tinker --execute="require 'database/scripts/backfill_reading_time.php';"
 * Or run directly: php database/scripts/backfill_reading_time.php
 */

require __DIR__ . '/../../vendor/autoload.php';

$app = require __DIR__ . '/../../bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

use App\Models\Post;
use App\Support\ContentRenderer;
use Illuminate\Contracts\Console\Kernel;

$count = 0;
Post::chunk(50, function ($posts) use (&$count) {
  foreach ($posts as $post) {
    $plain = strip_tags(ContentRenderer::render($post->content));
    $post->reading_time = max(1, (int) ceil(str_word_count($plain) / 200));
    $post->saveQuietly();
    $count++;
  }
});

echo "Done: {$count} posts backfilled with reading_time.\n";

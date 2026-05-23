<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Post;
use App\Support\ContentRenderer;

class PostObserver
{
    /**
     * Compute and persist reading_time before saving.
     * This ensures the heavy ContentRenderer::render + str_word_count computation
     * happens only once at write time instead of on every query that accesses the accessor.
     */
    public function saving(Post $post): void
    {
        if ($post->isDirty('content') || is_null($post->reading_time)) {
            $plainText = strip_tags(ContentRenderer::render($post->content));
            $wordCount = str_word_count($plainText);
            $post->reading_time = (int) max(1, ceil($wordCount / 200));
        }
    }
}

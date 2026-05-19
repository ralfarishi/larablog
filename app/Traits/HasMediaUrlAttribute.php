<?php

declare(strict_types=1);

namespace App\Traits;

/**
 * Provides a reusable media URL resolution method for models that use
 * Spatie Media Library alongside a legacy image column.
 *
 * Resolution order:
 *   1. Spatie Media Library conversion URL
 *   2. Spatie Media Library original URL
 *   3. Legacy column value (if a full URL, return directly; otherwise asset())
 *   4. Static fallback URL
 */
trait HasMediaUrlAttribute
{
  /**
   * Resolve the correct public URL for a media asset.
   *
   * @param  string  $collection  Spatie collection name (e.g. 'cover', 'avatar')
   * @param  string  $conversion  Spatie conversion name (e.g. 'preview', 'thumb')
   * @param  string|null $legacyColumn  The model attribute holding the legacy path/URL
   * @param  string  $fallback    URL to return when no media is found
   */
  protected function resolveMediaUrl(
    string $collection,
    string $conversion,
    ?string $legacyColumn,
    string $fallback,
  ): string {
    // 1. Spatie Media Library takes priority
    if ($this->hasMedia($collection)) {
      return $this->getFirstMediaUrl($collection, $conversion) ?:
        $this->getFirstMediaUrl($collection);
    }

    // 2. Legacy column
    if (!$legacyColumn) {
      return $fallback;
    }

    // If it's already an absolute URL, return it directly
    if (filter_var($legacyColumn, FILTER_VALIDATE_URL)) {
      return $legacyColumn;
    }

    // Relative path → prefix with uploads/
    return asset('uploads/' . $legacyColumn);
  }
}

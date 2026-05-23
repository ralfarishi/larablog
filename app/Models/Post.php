<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\ContentRenderer;
use App\Traits\HasMediaUrlAttribute;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

#[
  Fillable([
    'title',
    'slug',
    'content',
    'image',
    'user_id',
    'category_id',
    'allowed_comment',
    'status',
  ]),
]
#[Table('posts')]
class Post extends Model implements HasMedia
{
  use HasFactory, HasMediaUrlAttribute, InteractsWithMedia, Searchable;

  protected function casts(): array
  {
    return [
      'allowed_comment' => 'boolean',
    ];
  }

  /**
   * Get the indexable data array for the model.
   *
   * @return array<string, mixed>
   */
  public function toSearchableArray(): array
  {
    return [
      'id' => $this->id,
      'title' => $this->title,
      'content' => strip_tags((string) $this->content),
    ];
  }

  /**
   * Determine if the model should be searchable.
   */
  public function shouldBeSearchable(): bool
  {
    return $this->status === 'published';
  }

  /**
   * Spatie Media Collections
   */
  public function registerMediaCollections(): void
  {
    $this->addMediaCollection('cover')
      ->singleFile()
      ->useDisk('public')
      ->useFallbackUrl('https://placehold.co/1200x630?text=No+Image');
  }

  public function registerMediaConversions(?Media $media = null): void
  {
    // Conversions are queued (async) to avoid blocking the HTTP response.
    // Ensure QUEUE_CONNECTION != sync in .env for this to work.
    $this->addMediaConversion('thumb')->fit(Fit::Contain, 400, 225);
    $this->addMediaConversion('preview')->fit(Fit::Contain, 1200, 630);
  }

  /**
   * Resolve the correct public URL for the post image.
   */
  public function getImageUrlAttribute(): string
  {
    return $this->resolveMediaUrl(
      collection: 'cover',
      conversion: 'preview',
      legacyColumn: $this->image,
      fallback: 'https://placehold.co/1200x630?text=No+Image',
    );
  }

  /**
   * Get the estimated reading time in minutes.
   * Returns the persisted DB value when available; computes on the fly only
   * as a fallback (e.g. on freshly created models before the observer fires).
   */
  public function getReadingTimeAttribute(): int
  {
    // Use the stored column if it has been persisted
    if ($this->attributes['reading_time'] ?? null) {
      return (int) $this->attributes['reading_time'];
    }

    $plainText = strip_tags(ContentRenderer::render($this->content));
    $wordCount = str_word_count($plainText);

    return (int) max(1, ceil($wordCount / 200));
  }

  /**
   * Get the engagement score for the post.
   */
  public function getEngagementScoreAttribute(): int
  {
    $bookmarksCount = $this->bookmarks_count ?? $this->bookmarks()->count();
    $commentsCount = $this->comments_count ?? $this->comments()->count();

    return $bookmarksCount * 2 + $commentsCount;
  }

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  public function comments(): HasMany
  {
    return $this->hasMany(Comment::class, 'post_id');
  }

  public function bookmarks(): HasMany
  {
    return $this->hasMany(Bookmark::class);
  }

  public function category(): BelongsTo
  {
    return $this->belongsTo(Category::class, 'category_id', 'id');
  }

  public function tags(): BelongsToMany
  {
    return $this->belongsToMany(Tag::class)->withTimestamps();
  }

  /**
   * Get the route key for the model.
   */
  public function getRouteKeyName(): string
  {
    return 'slug';
  }
}

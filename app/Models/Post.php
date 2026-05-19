<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Scout\Searchable;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Traits\HasMediaUrlAttribute;

#[
  Fillable([
    'title',
    'slug',
    'content',
    'image',
    'tags',
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
    // Fit::Contain to ensure full image sizing as requested in TODO Point 1
    $this->addMediaConversion('thumb')->fit(Fit::Contain, 400, 225)->nonQueued();

    $this->addMediaConversion('preview')->fit(Fit::Contain, 1200, 630)->nonQueued();
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
    return $this->belongsToMany(Tag::class);
  }

  /**
   * Get the route key for the model.
   */
  public function getRouteKeyName(): string
  {
    return 'slug';
  }
}

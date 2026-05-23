<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasMediaUrlAttribute;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

#[Fillable(['name', 'email', 'password', 'role', 'slug', 'display_picture'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements HasMedia
{
  use HasFactory, HasMediaUrlAttribute, InteractsWithMedia, Notifiable;

  protected function casts(): array
  {
    return [
      'email_verified_at' => 'datetime',
      'password' => 'hashed',
    ];
  }

  public function registerMediaCollections(): void
  {
    $this->addMediaCollection('avatar')
      ->singleFile()
      ->useFallbackUrl(
        'https://ui-avatars.com/api/?name=' .
          urlencode($this->name) .
          '&size=100&color=fff&background=00B4A7',
      );
  }

  public function registerMediaConversions(?Media $media = null): void
  {
    $this->addMediaConversion('preview')->fit(Fit::Crop, 300, 300);
  }

  public function getProfilePictureUrlAttribute(): string
  {
    return $this->resolveMediaUrl(
      collection: 'avatar',
      conversion: 'preview',
      legacyColumn: $this->display_picture,
      fallback: 'https://ui-avatars.com/api/?name=' .
        urlencode($this->name) .
        '&size=100&color=fff&background=00B4A7',
    );
  }

  public function posts(): HasMany
  {
    return $this->hasMany(Post::class);
  }

  public function comments(): HasMany
  {
    return $this->hasMany(Comment::class);
  }

  public function bookmarks(): HasMany
  {
    return $this->hasMany(Bookmark::class);
  }
}

<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\Post;
use App\Models\User;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

class CustomMediaPathGenerator implements PathGenerator
{
  public function getPath(Media $media): string
  {
    return $this->getBasePath($media) . '/';
  }

  public function getPathForConversions(Media $media): string
  {
    return $this->getBasePath($media) . '/conversions/';
  }

  public function getPathForResponsiveImages(Media $media): string
  {
    return $this->getBasePath($media) . '/responsive-images/';
  }

  protected function getBasePath(Media $media): string
  {
    $prefix = 'images';

    if ($media->model_type === Post::class) {
      return "{$prefix}/articles/{$media->id}";
    }

    if ($media->model_type === User::class) {
      return "{$prefix}/users/{$media->id}";
    }

    return "{$prefix}/others/{$media->id}";
  }
}

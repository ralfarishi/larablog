<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'slug'])]
#[Table('categories')]
class Category extends Model
{
  use HasFactory;

  public function posts(): HasMany
  {
    return $this->hasMany(Post::class, 'category_id', 'id');
  }
}

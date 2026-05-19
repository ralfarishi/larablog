<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['post_id', 'user_id', 'content', 'active'])]
#[Table('comments')]
class Comment extends Model
{
  use HasFactory;

  protected function casts(): array
  {
    return [
      'active' => 'boolean',
    ];
  }

  public function post(): BelongsTo
  {
    return $this->belongsTo(Post::class, 'post_id', 'id');
  }

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }
}

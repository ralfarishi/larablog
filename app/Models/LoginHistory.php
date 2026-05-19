<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[
  Fillable([
    'email',
    'activity',
    'status',
    'ip_address',
    'user_agent',
    'city',
    'latitude',
    'longitude',
    'logout_at',
  ]),
]
#[Table('login_history')]
class LoginHistory extends Model
{
  use HasFactory;
  const UPDATED_AT = null;

  protected function casts(): array
  {
    return [
      'status' => 'boolean',
      'logout_at' => 'datetime',
    ];
  }
}

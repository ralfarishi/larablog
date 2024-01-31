<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notifications extends Model
{
	protected $table = 'notifications';

	protected $fillable = ['user_id', 'post_id', 'message', 'commenter_id', 'is_read'];

	public function commenter(): BelongsTo
	{
		return $this->belongsTo(User::class, 'commenter_id');
	}

	public function posts(): BelongsTo
	{
		return $this->belongsTo(Posts::class, 'post_id');
	}
}

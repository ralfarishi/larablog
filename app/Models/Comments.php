<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
	protected $table = "comments";

	protected $fillable = ['post_id', 'user_id', 'content', 'active'];

	public function post()
	{
		return $this->belongsTo(Posts::class, 'post_id');
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}

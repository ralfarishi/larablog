<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
	protected $table = "comments";

	protected $fillable = ['post_id', 'user_name', 'user_email', 'content', 'active'];

	public function post()
	{
		return $this->belongsTo(Posts::class, 'post_id');
	}
}

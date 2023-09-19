<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Posts extends Model
{
	protected $table = "posts";

	protected $fillable = ['title', 'slug', 'content', 'featured_image', 'user_id', 'category_id', 'allowed_comment', 'active'];

	public function user()
	{
		return $this->belongsTo('App\Models\User');
	}

	public function comments()
	{
		return $this->hasMany(Comments::class, 'post_id');
	}

	public function category()
	{
		return $this->belongsTo(Categories::class, 'category_id', 'id');
	}
}

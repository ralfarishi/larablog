<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
	protected $table = 'categories';

	protected $fillable = ['name', 'icon'];

	public function posts()
	{
		return $this->hasMany(Posts::class, 'category_id', 'id');
	}
}

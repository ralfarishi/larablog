<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Services\ImagePath;

class PostsModel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
	protected $table = "posts";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title','slug','content','featured_image','user_id'];

    /**
     * Accessor that returns an object/instance with path to different sized images.
     *
     * @param $value
     * @return ImagePath
     */

    public function getFeaturedImageAttribute($value)
    {
        return new ImagePath($value);
    }

    
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

     public function comments()
    {
        return $this->hasMany('App\Models\CommentsModel','post_id');
    }
}
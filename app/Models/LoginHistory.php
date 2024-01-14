<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginHistory extends Model
{
	protected $table = 'login_history';

	protected $fillable = ['email', 'activity', 'status', 'ip_address', 'user_agent', 'city', 'latitude', 'longitude'];

	public $timestamps = true;
}

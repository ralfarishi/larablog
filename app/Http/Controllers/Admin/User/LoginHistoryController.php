<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LoginHistoryController extends Controller
{
  public function index(Request $request): View
  {
    return view('admin.users.login-history');
  }
}

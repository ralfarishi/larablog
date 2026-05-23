<?php

declare(strict_types=1);

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
  /**
   * Display the search page.
   * The actual search query is handled by the SearchComponent Livewire component.
   * This controller only bootstraps the view with the initial query string.
   */
  public function index(Request $request): View
  {
    return view('search', [
      'query' => $request->query('q', ''),
    ]);
  }
}

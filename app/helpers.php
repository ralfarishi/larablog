<?php

use Carbon\Carbon;

use App\Models\Posts;
use App\Models\Categories;

function formatDate($date)
{
  $carbonDate = Carbon::parse($date);

  $carbonDate->setLocale('id');

  return $carbonDate->isSameDay(Carbon::now()) ?
    $carbonDate->diffForHumans() :
    $carbonDate->format('d F Y');
}

function getSidebarData()
{
  $categories = Categories::withCount(['posts' => function ($query) {
    $query->where('active', 1);
  }])->get();

  $tags = Posts::where('active', 1)->pluck('tags')->flatMap(function ($tags) {
    return array_map('strtolower', explode(',', $tags));
  })->unique()->reject(function ($tag) {
    return empty($tag);
  });

  return compact('categories', 'tags');
}

function getParagraphTagOnly($s)
{
  $dom = new DOMDocument();
  libxml_use_internal_errors(true);
  $dom->loadHTML($s);
  libxml_clear_errors();

  $pTag = null;
  foreach ($dom->getElementsByTagName('p') as $p) {
    $pTag = $p->nodeValue;
    break;
  }

  return $pTag;
}

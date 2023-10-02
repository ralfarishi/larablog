<?php

use Carbon\Carbon;

function formatDate($date)
{
  $carbonDate = Carbon::parse($date);

  $carbonDate->setLocale('id');

  return $carbonDate->isSameDay(Carbon::now()) ?
    $carbonDate->diffForHumans() :
    $carbonDate->format('l, d F Y');
}

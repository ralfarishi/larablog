<?php

use App\Providers\AppServiceProvider;
use Artesaos\SEOTools\Providers\SEOToolsServiceProvider;
use Stevebauman\Location\LocationServiceProvider;
use Yajra\DataTables\DataTablesServiceProvider;

return [
  AppServiceProvider::class,
  DataTablesServiceProvider::class,
  SEOToolsServiceProvider::class,
  LocationServiceProvider::class,
];

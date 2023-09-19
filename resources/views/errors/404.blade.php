@extends('errors::layout')

@section('title', __('Not Found'))

@section('code', '404')

@section('message')
<p>
  The page you are looking for might have been removed had its name
  changed or is temporarily unavailable.
</p>
<a href="{{ route('home') }}">Homepage</a>
@endsection

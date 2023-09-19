@extends('errors::layout')

@section('title', __('Forbidden'))

@section('code', '403')

@section('message')
<p>
  Sorry, you are not allowed to access this page. Please contact the administrator if you believe this is an error.
</p>
<a href="{{ route('home') }}">Homepage</a>
@endsection

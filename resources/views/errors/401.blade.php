@extends('errors::layout')

@section('title', __('Unauthorized'))

@section('code', '401')

@section('message')
<p>
  Sorry, you don't have permission to access this page. Please log in or contact the administrator if you need further access.
</p>
<a href="{{ route('home') }}">Homepage</a>
@endsection

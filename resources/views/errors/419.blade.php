@extends('errors::layout')

@section('title', __('Page Expired'))

@section('code', '419')

@section('message')
<p>
  Please log in again. Your authentication session has expired. Make sure you enter your login details correctly.
</p>
<a href="{{ route('login') }}">Return to login page</a>
@endsection



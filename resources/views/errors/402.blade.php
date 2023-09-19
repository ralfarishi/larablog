@extends('errors::layout')

@section('title', __('Payment Required'))

@section('code', '402')

@section('message')
<p>
  You must make a payment before accessing this content. Please proceed with the required payment.
</p>
<a href="{{ route('home') }}">Homepage</a>
@endsection

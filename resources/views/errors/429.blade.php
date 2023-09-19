@extends('errors::layout')

@section('title', __('Too Many Requests'))

@section('code', '429')

@section('message', __("We've received too many requests from your IP address. Please wait a moment and try again later."))


@extends('errors::layout')

@section('title', __('Server Error'))

@section('code', '500')

@section('message', __("An internal server error occurred. Our team has been notified of the issue. We apologize for any inconvenience."))

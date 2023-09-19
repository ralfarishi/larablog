@extends('errors::layout')

@section('title', __('Service Unavailable'))

@section('code', '503')

@section('message', __("Sorry, this service is currently unavailable. We are undergoing maintenance. Please try again later."))

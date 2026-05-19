@extends ('errors.minimal')

@section ('title', 'Server Error')
@section ('code', '500')
@section ('icon', 'ph-hard-drives')
@section ('message', 'Something Went Wrong')
@section ('description',
  'An unexpected error has occurred on our end. Our team has been notified and is working on a fix. Please try again shortly.')

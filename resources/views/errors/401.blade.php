@extends ('errors.minimal')

@section ('title', 'Unauthorized')
@section ('code', '401')
@section ('icon', 'ph-keyhole')
@section ('message', 'Identity Required')
@section ('description',
  'This zone requires authentication. Please sign in to continue exploring. If you do not have an account, registration awaits you.')

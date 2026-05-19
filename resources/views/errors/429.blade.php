@extends ('errors.minimal')

@section ('title', 'Too Many Requests')
@section ('code', '429')
@section ('icon', 'ph-warning-octagon')
@section ('message', 'Slow Down')
@section ('description',
  'You have made too many requests in a short time. Our servers need a moment to catch their breath. Please wait before trying again.')

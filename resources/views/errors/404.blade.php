@extends('errors::minimal')

@section('title', __('Not Found'))
@section('code', '404')
{{-- @section('message',  __($exception->getMessage() ?: __('Not Found'))) --}}
@section('message')
{{ str_contains($exception->getMessage(), '[CM404]') ? str_replace('[CM404]', '', $exception->getMessage()) : __('Not Found') }}
@endsection
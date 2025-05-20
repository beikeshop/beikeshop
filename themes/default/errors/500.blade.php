@php
    $cleanMessage = preg_replace('/ \(View: .*?\)/', '', $exception->getMessage());
@endphp

@extends('errors::minimal')

@section('title', __('Server Error'))
@section('code', '500')

@section('message')
    <p><strong>Error Message：</strong> {{ $cleanMessage }}</p>
@endsection

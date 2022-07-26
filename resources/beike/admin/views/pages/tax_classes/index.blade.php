@extends('admin::layouts.master')
@section('title', '税类设置')

@section('content')
    @foreach($tax_classes as $tax_class)
        @dump($tax_class->title)
    @endforeach
@endsection

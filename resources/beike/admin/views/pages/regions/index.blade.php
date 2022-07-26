@extends('admin::layouts.master')
@section('title', '区域组')

@section('content')
    @foreach($regions as $region)
        @dump($region->name)
    @endforeach
@endsection

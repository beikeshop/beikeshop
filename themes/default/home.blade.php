@extends('layout.master')
@section('body-class', 'page-home')
@section('content')


    @foreach($modules as $module)
        @include($module['view_path'], $module)
    @endforeach


@endsection

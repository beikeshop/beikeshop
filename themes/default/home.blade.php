@extends('layout.master')
@section('body-class', 'page-home')
@section('content')

<div class="modules-box">
  @foreach($modules as $module)
    @include($module['view_path'], $module)
  @endforeach
</div>

@endsection

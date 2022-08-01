@extends('layout.master')
@section('body-class', 'page-categories')

@section('content')
<div class="container">
  {{ dd($brands) }}

</div>

@endsection

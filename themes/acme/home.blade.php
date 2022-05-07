@extends('layout.master')

@section('content')
<h1>Acme theme - Home</h1>

<h2>Categories</h2>
@foreach ($categories as $category)
  <a href="{{ shop_route('categories.show', $category) }}">{{ $category->description->name }}</a>
@endforeach
@endsection

@extends('layout.master')
@section('body-class', 'page-pages')
@section('title', $page['meta_title'] ?: $page['title'])
@section('keywords', $page['meta_keyword'])
@section('description', $page['meta_description'])

@section('content')
  <div class="container">
    <x-shop-breadcrumb type="page" :value="$page['id']" />
    <div class="row">
      {!! $page['content'] !!}
    </div>
  </div>
@endsection

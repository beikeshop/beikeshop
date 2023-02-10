@extends('layout.master')
@section('body-class', 'page-pages')
@section('title', $page['meta_title'] ?: $page['title'])
@section('keywords', $page['meta_keywords'])
@section('description', $page['meta_description'])

@section('content')
  <div class="container">
    <x-shop-breadcrumb type="page" :value="$page['id']" />
    <div class="card">
      <div class="card-body">
        {!! $page_format['content'] !!}
      </div>
    </div>
  </div>
@endsection

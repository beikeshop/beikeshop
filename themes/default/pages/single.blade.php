@extends('layout.master')
@section('body-class', 'page-pages')
@section('title', $page->description->meta_title ?: $page->description->title)
@section('keywords', $page->description->meta_keywords)
@section('description', $page->description->meta_description)

@section('content')
  <x-shop-breadcrumb type="page" :value="$page['id']" />

  <div class="container">
    <div class="card">
      <div class="card-body">
        {!! $page_format['content'] !!}
      </div>
    </div>
  </div>
@endsection

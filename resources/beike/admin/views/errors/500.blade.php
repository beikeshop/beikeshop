@extends('admin::layouts.master')

@section('title', 'Server Error')
@section('code', '500')

@section('content')
<div style="word-break: break-all;" class="alert alert-danger">
  <p><strong>Error Message：</strong> {{ $exception->getMessage() }}</p>
  <p><strong>File：</strong> {{ $exception->getFile() }}</p>
  <p><strong>Line：</strong> {{ $exception->getLine() }}</p>
  <pre style="background:#f5f5f5;padding:10px;border:1px solid #ccc;overflow:auto;">
      {{ $exception->getTraceAsString() }}
  </pre>
</div>
@endsection

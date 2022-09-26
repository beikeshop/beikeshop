@extends('admin::layouts.master')

@section('title', __('admin/marketing.marketing_list'))

@section('content')
    @dump($plugins)
@endsection

@push('footer')
  <script>
  </script>
@endpush

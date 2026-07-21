@extends('layout.master')
@section('body-class', 'page-home')
@section('content')

<div class="modules-box" id="home-modules-box">

  @hook('home.modules.before')

  @foreach($modules as $module)
    @include($module['view_path'], $module)
  @endforeach

  @hook('home.modules.after')

</div>

@endsection

@if ($design)
@push('add-scripts')
<script>
  document.addEventListener('click', function(event) {
    event.preventDefault();
  });
</script>
@endpush
@endif

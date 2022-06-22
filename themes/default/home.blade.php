@extends('layout.master')

@section('content')
<div class="banner-wrap"><img src="{{ asset('image/default/banner.png') }}" class="img-fluid"></div>

<div class="module-image-plus">
  <div class="container">
    <div class="col-6"><img src="{{ asset('image/default/image_plus_1.png') }}" class="img-fluid"></div>
    <div class="col-6">
      <div class="module-image-plus-top">
        <a href=""><img src="{{ asset('image/default/image_plus_2.png') }}" class="img-fluid"></a>
        <a href=""><img src="{{ asset('image/default/image_plus_3.png') }}" class="img-fluid"></a>
      </div>
      <div class="module-image-plus-bottom"><a href=""><img src="{{ asset('image/default/image_plus_4.png') }}" class="img-fluid"></a></div>
    </div>
  </div>
</div>
@endsection

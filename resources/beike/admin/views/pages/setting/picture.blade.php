@extends('admin::layouts.master')

@section('title', __('admin/common.settings.picture'))

@section('content-area-class', 'w-max-1200')

@section('page-title-back', admin_route('settings.index'))

@section('head-form-btns', true)

@push('header')
  <script src="{{ asset('vendor/cropper/cropper.min.js') }}"></script>
  <link rel="stylesheet" href="{{ asset('vendor/cropper/cropper.min.css') }}">
@endpush

@section('content')
  @if (session('success'))
    <x-admin-alert type="success" msg="{{ session('success') }}" class="mt-4"/>
  @endif
  @if (session('error'))
    <div class="alert alert-danger">
      {!! session('error') !!}
    </div>
  @endif
  <div class="card h-min-600">
    <div class="card-body">
      @hook('admin.setting.picture.content.before')
      <form action="{{ admin_route('settings.store') }}" class="needs-validation" novalidate method="POST" id="form-app">
        @csrf
        <input type="hidden" name="return_url" value="{{ url()->full() }}"/>

        @hook('admin.setting.image.before')

        <x-admin::form.row title="{{ __('admin/setting.shop_logo') }}">
          <div class="open-crop cursor-pointer bg-light wh-80 border d-flex justify-content-center align-items-center me-2 mb-2 position-relative" ratio="380/100">
            <img src="{{ image_resize(old('logo', system_setting('base.logo', ''))) }}" class="img-fluid">
          </div>
          <input type="hidden" value="{{ old('logo', system_setting('base.logo', '')) }}" name="logo">
          <div class="help-text font-size-12 lh-base">{{ __('common.recommend_size') }} 380*100</div>
        </x-admin::form.row>

        <x-admin::form.row title="{{ __('admin/setting.admin_logo') }}">
          <div class="open-crop cursor-pointer bg-light wh-80 border d-flex justify-content-center align-items-center me-2 mb-2 position-relative" ratio="380/100">
            <img src="{{ image_resize(old('admin_logo', system_setting('base.admin_logo', 'image/logo.png'))) }}" class="img-fluid">
          </div>
          <input type="hidden" value="{{ old('admin_logo', system_setting('base.admin_logo', 'image/logo.png')) }}" name="admin_logo">
          <div class="help-text font-size-12 lh-base">{{ __('common.recommend_size') }} 388*73</div>
        </x-admin::form.row>

        <x-admin::form.row title="favicon">
          <div class="open-crop cursor-pointer bg-light wh-80 border d-flex justify-content-center align-items-center me-2 mb-2 position-relative" ratio="32/32">
            <img src="{{ image_resize(old('favicon', system_setting('base.favicon', ''))) }}" class="img-fluid">
          </div>
          <input type="hidden" value="{{ old('favicon', system_setting('base.favicon', '')) }}" name="favicon">
          <div class="help-text font-size-12 lh-base">{{ __('admin/setting.favicon_info') }}</div>
        </x-admin::form.row>

        <x-admin::form.row :title="__('admin/setting.placeholder_image')">
          <div class="open-crop cursor-pointer bg-light wh-80 border d-flex justify-content-center align-items-center me-2 mb-2 position-relative" ratio="500/500">
            <img src="{{ image_resize(old('placeholder', system_setting('base.placeholder', ''))) }}" class="img-fluid">
          </div>
          <input type="hidden" value="{{ old('placeholder', system_setting('base.placeholder', '')) }}" name="placeholder">
          <div class="help-text font-size-12 lh-base">{{ __('admin/setting.placeholder_image_info') }}</div>
        </x-admin::form.row>

        @hook('admin.setting.image.after')

        <x-admin::form.row title="">
          <button type="submit" class="btn btn-primary d-none mt-4">{{ __('common.submit') }}</button>
        </x-admin::form.row>
      </form>
      @hook('admin.setting.picture.content.after')
    </div>

    <div class="modal fade" id="modal" tabindex="-1" data-bs-backdrop="static" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <div class="d-flex align-items-center">
              <h5 class="modal-title" id="exampleModalLabel">{{ __('shop/account/edit.crop') }}</h5>
              <div class="cropper-size ms-4">{{ __('common.cropper_size') }}：<span></span></div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="img-container">
              <img id="cropper-image" src="{{ image_resize('/') }}" class="img-fluid">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('shop/common.cancel') }}</button>
            <button type="button" class="btn btn-primary cropper-crop">{{ __('shop/common.confirm') }}</button>
          </div>
        </div>
      </div>
  </div>
@endsection

@push('footer')
<script>
  let ratio = 1;
  let $crop = null
  var cropper;

  $(function() {
    $('.open-crop').click(function() {
      var image = document.getElementById('cropper-image');
      $crop = $(this);
      ratio = $(this).attr('ratio')
      var cropper;
      var $input = $('<input type="file" accept="image/*" class="d-none">');
      $input.click();
      $input.change(function() {
        var files = this.files;
        var done = function(url) {
          image.src = url;
          $('#modal').modal('show');
        };

        if (files && files.length > 0) {
          var reader = new FileReader();
          reader.onload = function(e) {
            done(reader.result);
          };
          reader.readAsDataURL(files[0]);
        }
      });
    });

    $('input[name="show_price_after_login"]').change(function () {
      if ($(this).val() == 1 && $('input[name="guest_checkout"]').prop('checked') == true) {
        $('input[name="guest_checkout"]').prop('checked', true);
        $('.show-price-error-text').addClass('text-danger fw-bold');
        setTimeout(() => {
          $('.show-price-error-text').removeClass('text-danger fw-bold');
        }, 1200);
      }
    });

    $('input[name="guest_checkout"]').change(function () {
      if ($(this).val() == 1 && $('input[name="show_price_after_login"]').prop('checked') == true) {
        $('input[name="show_price_after_login"]').prop('checked', 1);
        $('.show-price-error-text').addClass('text-danger fw-bold');
        setTimeout(() => {
          $('.show-price-error-text').removeClass('text-danger fw-bold');
        }, 1200);
      }
    });
  });

  $('#modal').on('shown.bs.modal', function() {
    var image = document.getElementById('cropper-image');
    cropper = new Cropper(image, {
      initialAspectRatio: ratio.split('/')[0] / ratio.split('/')[1],
      autoCropArea: 1,
      viewMode: 1,
      // 回调 获取尺寸
      crop: function(event) {
        $('.cropper-size span').html(parseInt(event.detail.width) + ' * ' + parseInt(event.detail.height))
      }
    });
  }).on('hidden.bs.modal', function() {
    cropper.destroy();
    cropper = null;
  });

  $('.cropper-crop').click(function(event) {
    var canvas;

    $('#modal').modal('hide');

    if (cropper) {
      canvas = cropper.getCroppedCanvas({
        imageSmoothingQuality: 'high',
        width: 800, //最大宽度
        height: 800, //最大高度
      });
      canvas.toBlob(function(blob) {
        var formData = new FormData();

        formData.append('file', blob, 'avatar.png');
        formData.append('type', 'avatar');
        $http.post('{{ shop_route('file.store') }}', formData).then(res => {
          $crop.find('img').attr('src', res.data.url);
          $crop.next('input').val(res.data.value);
        })
      });
    }
  });
</script>
@endpush
@extends('layout.master')

@section('body-class', 'page-account-edit')

@push('header')
  <script src="{{ asset('vendor/cropper/cropper.min.js') }}"></script>
  <link rel="stylesheet" href="{{ asset('vendor/cropper/cropper.min.css') }}">
@endpush

@section('content')
  <x-shop-breadcrumb type="static" value="account.edit.index" />

  <div class="container" id="address-app">
    <div class="row">
      <x-shop-sidebar />

      <div class="col-12 col-md-9">
        <div class="card h-min-600">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title">{{ __('shop/account/edit.index') }}</h5>
          </div>
          <div class="card-body h-600">
            <form novalidate class="needs-validation" action="{{ shop_route('account.edit.update') }}" method="POST">
              @csrf
              {{ method_field('put') }}

              @if (session('success'))
                <x-shop-alert type="success" msg="{{ session('success') }}" class="mt-4" />
              @endif

              <div class="bg-light rounded-3 p-4 mb-4" style="background: #f6f9fc;">
                <div class="d-flex align-items-center">
                  <img class="rounded-3" id="avatar" src="{{ image_resize($customer->avatar, 200, 200) }}"
                    width="90">
                  <div class="ps-3">
                    <label class="btn btn-light shadow-sm bg-body mb-2" data-toggle="tooltip" title="Change your avatar">
                      <i class="bi bi-arrow-repeat"></i> {{ __('shop/account/edit.modify_avatar') }}
                      <input type="file" class="d-none" id="update-btn" name="" accept="image/*">
                      <input type="hidden" id="avatar-input" name="avatar" value="{{ $customer->avatar }}">
                    </label>
                    <div class="p mb-0 fs-ms text-muted">{{ __('shop/account/edit.suggest') }}</div>
                  </div>
                </div>
              </div>
              <div class="row gx-4 gy-3">
                <div class="col-sm-6">
                  <label class="form-label">{{ __('shop/account/edit.name') }}</label>
                  <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name"
                    value="{{ old('name', $customer->name ?? '') }}" required>
                  <span class="invalid-feedback"
                    role="alert">{{ $errors->has('name') ? $errors->first('name') : __('common.error_required', ['name' => __('shop/account/edit.name')]) }}</span>
                </div>
                @hookwrapper('account.edit.email')
                <div class="col-sm-6">
                  <label class="form-label">{{ __('shop/account/edit.email') }}</label>
                  <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email"
                    name="email" value="{{ old('email', $customer->email ?? '') }}" required>
                  <span class="invalid-feedback"
                    role="alert">{{ $errors->has('email') ? $errors->first('email') : __('common.error_required', ['name' => __('shop/account/edit.email')]) }}</span>
                </div>
                @endhookwrapper
                <div class="col-12 mt-4">
                  <button class="btn btn-primary mt-sm-0" type="submit">{{ __('common.submit') }}</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal" tabindex="-1" data-bs-backdrop="static" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">{{ __('shop/account/edit.crop') }}</h5>
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
  </div>
@endsection

@push('add-scripts')
  <script>
    var avatar = document.getElementById('avatar');
    var image = document.getElementById('cropper-image');
    var cropper;
    var $modal = $('#modal');

    $(document).on('change', '#update-btn', function(e) {
      var files = e.target.files;
      var done = function(url) {
        $(this).val('');
        image.src = url;
        $('#modal').modal('show');
      };
      var reader;
      var file;
      var url;

      if (files && files.length > 0) {
        file = files[0];

        $('#update-btn').remove()
        $('#avatar-input').before('<input type="file" class="d-none" id="update-btn" name="" accept="image/*">');

        if (URL) {
          done(URL.createObjectURL(file));
        } else if (FileReader) {
          reader = new FileReader();
          reader.onload = function(e) {
            done(reader.result);
          };
          reader.readAsDataURL(file);
        }
      }
    });

    $modal.on('shown.bs.modal', function() {
      cropper = new Cropper(image, {
        aspectRatio: 1,
        viewMode: 3,
      });
    }).on('hidden.bs.modal', function() {
      cropper.destroy();
      cropper = null;
    });

    $('.cropper-crop').click(function(event) {
      var initialAvatarURL;
      var canvas;

      $modal.modal('hide');

      if (cropper) {
        canvas = cropper.getCroppedCanvas({
          width: 200,
          height: 200,
        });
        initialAvatarURL = avatar.src;
        // avatar.src = canvas.toDataURL();
        canvas.toBlob(function(blob) {
          var formData = new FormData();

          formData.append('file', blob, 'avatar.png');
          formData.append('type', 'avatar');
          $http.post('{{ shop_route('file.store') }}', formData).then(res => {
            $('#avatar').attr('src', res.data.url);
            $('#avatar-input').val(res.data.value)
          })
        });
      }
    });
  </script>
@endpush

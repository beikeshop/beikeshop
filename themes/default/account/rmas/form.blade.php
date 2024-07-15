@extends('layout.master')

@section('body-class', 'page-account-rmas')


@section('content')
  <x-shop-breadcrumb type="static" value="account.rma.index" />

  <div class="container" id="address-app">
    <div class="row">
      <x-shop-sidebar/>
      <div class="col-12 col-md-9">
        <div class="card h-min-600">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title">{{ __('shop/account/rma_form.index') }}</h5>
          </div>
          <div class="card-body h-min-600">
            <div class="bg-light rounded-3 p-3 mb-4" style="background: #f6f9fc;">
              <div class="d-flex align-items-center">
                <div class="left wh-70">
                  <img src="{{ $orderProduct->image }}" class="img-fluid">
                </div>
                <div class="right ms-3">
                  <div class="name mb-2 fw-bold fs-5">{{ $orderProduct->name }}</div>
                  <div class="price">{{ $orderProduct->price }} x {{ $orderProduct->quantity }}</div>
                </div>
              </div>
            </div>

            <form action="{{ shop_route('account.rma.store') }}" method="POST" novalidate id="form-page" class="needs-validation">
              @csrf
              @if (session('success'))
                <x-shop-alert type="success" msg="{{ session('success') }}" class="mt-4"/>
              @endif

              <input type="hidden" name="order_product_id" value="{{ $orderProduct->id }}">

              <div class="row">
                <div class="col-6 mb-4">
                  <label class="col-form-label">{{ __('shop/account/rma_form.service_type') }}</label>
                  <select class="form-select" name="type">
                    @foreach ($types as $key => $item)
                      <option value="{{ $key }}" {{ $key == old('type', '') ? 'selected': '' }}>{{ $item }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="col-6 mb-4">
                  <label class="col-form-label required">{{ __('shop/account/rma_form.return_quantity') }}</label>
                  <input class="form-control {{ $errors->has('quantity') ? 'is-invalid' : '' }}" type="text" name="quantity" required value="{{ old('quantity', $orderProduct->quantity ?? '1') }}" placeholder="{{ __('shop/account/rma_form.return_quantity') }}">
                  <span class="invalid-feedback {{ $errors->has('quantity') ? 'd-block' : '' }}" role="alert">{{ __('common.error_required', ['name' => __('shop/account/rma_form.return_quantity')]) }}</span>
                </div>

                <div class="col-6 mb-4">
                  <label class="col-form-label">{{ __('shop/account/rma_form.unpacked') }}</label>
                  <select class="form-select" name="opened">
                    <option selected value="0">{{ __('common.no') }}</option>
                    <option value="1">{{ __('common.yes') }}</option>
                  </select>
                </div>

                <div class="col-6 mb-4">
                  <label class="col-form-label">{{ __('shop/account/rma_form.return_reason') }}</label>
                  <select class="form-select {{ $errors->has('rma_reason_id') ? 'is-invalid' : '' }}" name="rma_reason_id">
                    @foreach ($reasons as $item)
                      <option value="{{ $item['id'] }}" {{ $item['id'] == old('opened', '') ? 'selected': '' }}>{{ $item['name'] }}</option>
                    @endforeach
                  </select>

                  @if ($errors->has('rma_reason_id'))
                    <span class="invalid-feedback" role="alert">{{ $errors->first('rma_reason_id') }}</span>
                  @endif
                </div>

                <div class="col-6 mb-4">
                  <label class="col-form-label">{{ __('common.image') }}</label>
                  <div class="p-2 bg-light h-min-100">
                    <div class="image-wrap d-flex flex-wrap">
                      <div class="up-img d-flex align-items-center flex-wrap"></div>
                      <label class="btn wh-60 btn-light d-flex align-items-center justify-content-center shadow-sm bg-body">
                        <i class="bi bi-plus-lg fs-3"></i>
                        <input type="file" class="d-none" id="update-btn" name="" accept="image/*" multiple>
                      </label>
                    </div>
                  </div>
                </div>

                <div class="col-6 mb-4">
                  <label class="col-form-label">{{ __('shop/account/rma_form.remark') }}</label>
                  <textarea rows="4" type="text" name="comment" class="form-control">{{ old('comment', '') }}</textarea>
                </div>

                <div class="col-12 mt-4">
                  <button class="btn btn-primary mt-sm-0 w-min-100" type="submit">{{ __('shop/common.submit') }}</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('add-scripts')
<script>
  $(document).on('change', '#update-btn', function(e) {
    var files = e.target.files;

    for (var i = 0; i < files.length; i++) {
      var image = files[i];
      var formData = new FormData();
      formData.append('file', image);
      formData.append('type', 'rma');
      $http.post('{{ shop_route('file.store') }}', formData).then(res => {
        if (res.data.value) {
          var html = '<div class="img-item wh-60 me-2 mb-2 border rounded position-relative d-flex align-items-center justify-content-center"><img src="' + res.data.url + '" class="img-fluid"><input type="hidden" name="images[]" value="' + res.data.value + '"><i class="bi bi-x-lg position-absolute top-0 end-0 cursor-pointer px-1 text-white bg-danger"></i></div>';
          $('.image-wrap .up-img').append(html);
          $('#update-btn').val('');
        }
      })
    }
  });

  $(document).on('click', '.image-wrap .up-img .img-item i', function() {
    $(this).parent().remove();
  });
</script>
@endpush

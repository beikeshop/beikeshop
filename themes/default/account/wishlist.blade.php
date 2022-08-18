@extends('layout.master')

@section('body-class', 'page-account-wishlist')

@section('content')
  <div class="container">

    <x-shop-breadcrumb type="static" value="account.wishlist.index" /> 

    {{-- <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Library</li>
      </ol>
    </nav> --}}

    <div class="row">
      <x-shop-sidebar/>

      <div class="col-12 col-md-9">
        <div class="card mb-4 h-min-600">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title">我的收藏</h5>
          </div>
          <div class="card-body">
            <table class="table align-middle">
              <thead>
                <tr>
                  <th width="90px"></th>
                  <th>产品</th>
                  <th>价格</th>
                  <th class="text-end"></th>
                </tr>
              </thead>
              <tbody>
                @foreach ($wishlist as $item)
                <tr data-id="{{ $item['id'] }}">
                  <td><div class="wh-70"><img src="{{ $item['image'] }}" class="img-fluid"></div></td>
                  <td>{{ $item['product_name'] }}</td>
                  <td>{{ $item['price'] }}</td>
                  <td class="text-end">
                    <div class="">
                      <a class="btn btn-dark btn-sm add-cart" href="{{ shop_route('products.show', $item['product_id']) }}">查看详情</a>
                      <button class="btn btn-danger btn-sm remove-wishlist"><i class="bi bi-x-lg"></i></button>
                    </div>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
        {{-- {{ $wishlist->links('shared/pagination/bootstrap-4') }} --}}
      </div>
    </div>
  </div>
@endsection

@push('add-scripts')
<script>
  $(document).ready(function() {
    $('.remove-wishlist').click(function() {
      const product_id = $(this).closest('tr').data('id');

      $http.delete('account/wishlist/' + product_id).then((res) => {
        if (res.status == 'success') {
          $(this).closest('tr').fadeOut(function() {
            $(this).remove();
            if ($('.remove-wishlist').length == 0) {
              location.reload();
            }
          });
        }
      })
    });
  });
</script>
@endpush

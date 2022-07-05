@extends('admin::layouts.master')

@section('title', '订单列表')

@section('content')
  <div id="customer-app" class="card" v-cloak>
    <div class="card-body">
      <div class="d-flex justify-content-between mb-4">
        <button type="button" class="btn btn-primary" @click="checkedCustomersCreate">创建顾客</button>
      </div>

      {{-- {{ $customers->links('admin::vendor/pagination/bootstrap-4') }} --}}
    </div>
  </div>
@endsection

@push('footer')
  <script>

  </script>
@endpush

@extends('admin::layouts.master')

@section('title', __('admin/marketing.marketing_show'))

@section('body-class', 'page-marketing-info')

@section('content')
  @php
    $data = $plugin['data'];
  @endphp
  <div class="card mb-4">
    <div class="card-body">
      <div class="d-flex mb-5">
        <div class="border wp-400 hp-400 d-flex justify-content-between align-items-center"><img src="{{ $data['icon_big'] }}" class="img-fluid"></div>
        <div class="ms-5 mt-2">
          <h3 class="card-title mb-4">{{ $data['name'] }}</h3>
          <div class="plugin-item d-flex align-items-center mb-4 lh-1 text-secondary">
            <div class="mx-3 ms-0">下载次数：{{ $data['number'] }}</div><span class="vr lh-1 bg-secondary"></span>
            <div class="mx-3">最后更新：{{ $data['updated_at'] }}</div><span class="vr lh-1 bg-secondary"></span>
            <div class="mx-3">版本：{{ $data['version'] }}</div>
          </div>

          <div class="mb-4">
            <div class="mb-2 fw-bold">兼容性：</div>
            <div>{{ $data['version_name_format'] }}</div>
          </div>
          <div class="mb-5">
            <div class="mb-2 fw-bold">插件作者：</div>
            <div class="d-flex">
              <div class="border wh-60 d-flex justify-content-between align-items-center"><img src="{{ $data['developer']['avatar'] }}" class="img-fluid"></div>
              <div class="ms-3">
                <div class="mb-2 fw-bold">{{ $data['developer']['name'] }}</div>
                <div>{{ $data['developer']['email'] }}</div>
              </div>
            </div>
          </div>

          <div>
            <button class="btn btn-primary btn-lg download-plugin"><i class="bi bi-cloud-arrow-down-fill"></i> 下载插件</button>
            <div class="mt-3 d-none download-help"><a href="{{ admin_route('plugins.index') }}" class=""><i class="bi bi-cursor-fill"></i> <span></span></a></div>
          </div>
        </div>
      </div>

      <div>
        <h5 class="text-center">插件描述</h5>
        <div>{{ $data['description'] }}</div>
      </div>
    </div>
  </div>
@endsection

@push('footer')
  <script>
    $('.download-plugin').click(function(e) {
      $http.post('{{ admin_route('marketing.download', ['code' => $data['code']]) }}').then((res) => {
        $('.download-help').removeClass('d-none').find('span').text(res.message);
      })
    })
    // {{ admin_route('marketing.download', ['code' => $data['code']]) }}
  </script>
@endpush

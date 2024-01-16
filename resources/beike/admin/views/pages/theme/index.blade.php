@extends('admin::layouts.master')

@section('title', __('admin/common.theme_index'))

@section('body-class', 'page-theme')

@section('content')
  <div id="customer-app" class="card h-min-600">
    <div class="card-header d-flex justify-content-between align-items-start">
      <h5 class="card-title">{{ __('admin/theme.page_title') }}</h5>
      <a href="{{ admin_route('marketing.index') }}?type=theme" class="btn btn-outline-info">{{ __('common.get_more') }}</a>
    </div>
    <div class="card-body">
      <div class="theme-wrap">
        <div class="row">
          @foreach ($themes as $item)
          <div class="col-12 col-md-6 col-lg-4 col-xl-3">
            <div class="item">
              <div class="img"><img src="{{ $item['image'] }}" class="img-fluid"></div>
              <div class="theme-bottom d-flex justify-content-between align-items-center">
                <div class="name fs-5">{{ $item['name'] }}</div>
                <div class="theme-tool">
                  @if ($item['status'])
                    <div class="enabled-text">{{ __('admin/theme.enabled_text') }}</div>
                  @else
                    <button class="btn btn-outline-success enabled-theme" data-code="{{ $item['code'] }}">{{ __('common.enabled') }}</button>
                  @endif
                </div>
              </div>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
  <div id="theme-config" class="p-3" style="display: none">
    <div class="form-check form-check-inline mb-3">
      <input class="form-check-input" type="checkbox" id="theme-demo-data" value="">
      <label class="form-check-label" for="theme-demo-data">{{ __('admin/theme.theme_pop_checkbox') }}</label>
    </div>
    <div class="text-danger"><i class="bi bi-exclamation-triangle-fill"></i> {{ __('admin/theme.theme_pop_text') }}</div>
    <div class="d-flex justify-content-end mt-3">
      <button class="btn btn-primary theme-config-btn">{{ __('common.confirm') }}</button>
    </div>
  </div>
@endsection

@push('footer')
  <script>
    $(function () {
      let theme = null;
      $('.enabled-theme').click(function () {
        theme = $(this).data('code');

        layer.open({
          type: 1,
          title: '{{ __('common.text_hint') }}',
          area: ['400px'],
          content: $('#theme-config'),
        })
      })

      $('.theme-config-btn').click(function () {
        const demoData = $('#theme-demo-data').is(':checked');

        $http.put(`themes/${theme}`, {import_demo: demoData}).then((res) => {
          layer.msg(res.message, {time: 600}, ()=> {
            window.location.reload();
          });
        })
      })
    })
  </script>
@endpush

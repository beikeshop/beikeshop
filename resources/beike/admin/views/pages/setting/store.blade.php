@extends('admin::layouts.master')

@section('title', __('admin/common.settings.store_settings'))

@section('content-area-class', 'w-max-1200')

@section('page-title-back', admin_route('settings.index'))

@section('head-form-btns', true)

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
      @hook('admin.setting.store.content.before')
      <form action="{{ admin_route('settings.store') }}" class="needs-validation" novalidate method="POST" id="form-app">
        @csrf

        <input type="hidden" name="return_url" value="{{ url()->full() }}"/>

        <x-admin::form.row title="{{ __('admin/setting.default_address') }}">
          <div class="d-lg-flex w-max-400">
            <div class="w-50 me-3">
              <select class="form-select me-3" name="country_id" aria-label="Default select example">
                @foreach ($countries as $country)
                  <option
                    value="{{ $country->id }}"
                    {{ $country->id == system_setting('base.country_id', '1') ? 'selected': '' }}>
                    {{ $country->name }}
                  </option>
                @endforeach
              </select>
              <div class="help-text font-size-12 lh-base">{{ __('admin/setting.default_country_set') }}</div>
            </div>
            <div class="w-50">
              <select class="form-select zones-select" name="zone_id" aria-label="Default select example"></select>
              <div class="help-text font-size-12 lh-base">{{ __('admin/setting.default_zone_set') }}</div>
            </div>
          </div>
        </x-admin::form.row>

        <x-admin-form-select title="{{ __('admin/setting.default_currency') }}" name="currency" :value="old('currency', system_setting('base.currency', 'USD'))" :options="$currencies->toArray()" key="code" label="name" />

        <x-admin-form-select title="{{ __('admin/setting.default_language') }}" name="locale" :value="old('locale', system_setting('base.locale', 'zh_cn'))" :options="$languages" key="code" label="name" />

        @php
          $weight = array_map(function ($value) {
            return ['code' => $value, 'name' => trans('product.' . $value)];
          }, $weight_classes);
        @endphp
        <x-admin-form-select title="{{ __('admin/setting.weight_unit') }}"  name="weight" :options="$weight" :value="old('weight', system_setting('base.weight', 'kg'))" key="code" label="name" />

        <x-admin-form-select title="{{ __('admin/setting.default_customer_group') }}" name="default_customer_group_id" :value="old('locale', system_setting('base.default_customer_group_id', ''))" :options="$customer_groups" key="id" label="name" />

        <x-admin-form-input name="admin_name" title="{{ __('admin/setting.admin_name') }}" required value="{{ old('admin_name', system_setting('base.admin_name', 'admin')) }}">
          <div class="help-text font-size-12 lh-base">{{ __('admin/setting.admin_name_info') }}</div>
        </x-admin-form-input>

        <x-admin-form-input name="product_per_page" title="{{ __('admin/setting.product_per_page') }}" required value="{{ old('product_per_page', system_setting('base.product_per_page', 20)) }}">
        </x-admin-form-input>

        <x-admin::form.row title="{{ __('admin/setting.image_origin_size') }}">
          <div class="d-lg-flex w-max-400">
            <div class="w-50 me-3">
              <div class="input-group">
                <span class="input-group-text">{{ __('admin/builder.modules_width') }}</span>
                <input type="text" class="form-control" value="{{ old('product_image_origin_width', system_setting('base.product_image_origin_width', '800')) }}" name="product_image_origin_width">
              </div>
            </div>
            <div class="w-50">
              <div class="input-group">
                <span class="input-group-text">{{ __('admin/builder.modules_height') }}</span>
                <input type="text" class="form-control" value="{{ old('product_image_origin_height', system_setting('base.product_image_origin_height', '800')) }}" name="product_image_origin_height">
              </div>
            </div>
          </div>
          <div class="help-text font-size-12 lh-base">{{ __('admin/setting.image_origin_size_text') }}</div>
        </x-admin::form.row>

        <x-admin-form-input-locale name="hot_keywords.*" title="{{ __('admin/setting.hot_keywords') }}" :value="old('hot_keywords', system_setting('base.hot_keywords', ''))">
          <div class="help-text font-size-12 lh-base">{{ __('admin/setting.hot_keywords_tips') }}</div>
        </x-admin-form-input-locale>

        @hook('admin.setting.store.after')

        <x-admin::form.row title="">
          <button type="submit" class="btn btn-primary d-none mt-4">{{ __('common.submit') }}</button>
        </x-admin::form.row>
      </form>
      @hook('admin.setting.store.content.after')
    </div>
  </div>
@endsection

@push('footer')
  <script>
    @if (session('success'))
      layer.msg('{{ session('success') }}')
    @endif

    const country_id = {{ system_setting('base.country_id', '1') }};
    const zone_id = {{ system_setting('base.zone_id', '1') ?: 1 }};

    // 获取省份
    const getZones = (country_id) => {
      $http.get(`countries/${country_id}/zones`, {'active': 1}, {hload: true}).then((res) => {
        if (res.data.zones.length > 0) {
          $('select[name="zone_id"]').html('');
          res.data.zones.forEach((zone) => {
            $('select[name="zone_id"]').append(`
              <option ${zone_id == zone.id ? 'selected' : ''} value="${zone.id}">${zone.name}</option>
            `);
          });
        } else {
          $('select[name="zone_id"]').html(`
            <option value="">{{ __('common.please_choose') }}</option>
          `);
        }
      })
    }

    $(function() {
      const line = bk.getQueryString('line');
      getZones(country_id);

      $('select[name="country_id"]').on('change', function () {
        getZones($(this).val());
      });

      if (line) {
        $(`textarea[name="${line}"], select[name="${line}"], input[name="${line}"]`).parents('.row').addClass('active-line');

        setTimeout(() => {
          $('div').removeClass('active-line');
        }, 1200);
      }

      let smtpHost = $('input[name="smtp[host]"]').val();
      if (smtpHost && smtpHost.includes('smtp.qq.com')) {
        $('.smtp-qq-hint').removeClass('d-none');
      }

      $(document).on('input', 'input[name="smtp[host]"]', function () {
        if ($(this).val().includes('smtp.qq.com')) {
          $('.smtp-qq-hint').removeClass('d-none');
        } else {
          $('.smtp-qq-hint').addClass('d-none');
        }
      });

      $('.nav-tabs a').on ('click', function (e) {
        const formAction = @json(admin_route('settings.store'));
        const tab = $(this).attr('href').replace('#', '');
        $('form#app').attr('action', formAction + '?tab=' + tab);
      });
    });

  </script>
@endpush
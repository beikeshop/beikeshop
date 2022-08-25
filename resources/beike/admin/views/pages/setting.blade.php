@extends('admin::layouts.master')

@section('title', __('admin/setting.index'))

@section('content')
  <div id="plugins-app-form" class="card h-min-600">
    <div class="card-body">
      <form action="{{ admin_route('settings.store') }}" method="POST" id="app">
        @csrf
        @if (session('success'))
          <x-admin-alert type="success" msg="{{ session('success') }}" class="mt-4"/>
        @endif
        <ul class="nav nav-tabs nav-bordered mb-5" role="tablist">
          <li class="nav-item" role="presentation">
            <a class="nav-link active" data-bs-toggle="tab" href="#tab-general">{{ __('admin/setting.basic_settings') }}</a>
          </li>
          <li class="nav-item" role="presentation">
            <a class="nav-link" data-bs-toggle="tab" href="#tab-store">{{ __('admin/setting.store_settings') }}</a>
          </li>
          <li class="nav-item" role="presentation">
            <a class="nav-link" data-bs-toggle="tab" href="#tab-image">{{ __('admin/setting.picture_settings') }}</a>
          </li>
        </ul>

        <div class="tab-content">
          <div class="tab-pane fade show active" id="tab-general">
            <x-admin-form-input name="meta_title" title="{{ __('admin/setting.meta_tiele') }}" value="{{ old('meta_title', system_setting('base.meta_title', '')) }}" />
            <x-admin-form-textarea name="meta_description" title="{{ __('admin/setting.meta_description') }}" value="{{ old('meta_description', system_setting('base.meta_description', '')) }}" />
            <x-admin-form-textarea name="meta_keyword" title="{{ __('admin/setting.meta_keyword') }}" value="{{ old('meta_keyword', system_setting('base.meta_keyword', '')) }}" />
            <x-admin-form-input name="telephone" title="{{ __('admin/setting.telephone') }}" value="{{ old('telephone', system_setting('base.telephone', '')) }}" />
            <x-admin-form-input name="email" title="{{ __('admin/setting.email') }}" value="{{ old('email', system_setting('base.email', '')) }}" />
          </div>

          <div class="tab-pane fade" id="tab-store">
            <x-admin::form.row title="{{ __('admin/setting.default_address') }}">
              <div class="d-flex">
                <div>
                  <select class="form-select wp-200 me-3" name="country_id" aria-label="Default select example">
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
                <div>
                  <select class="form-select wp-200 zones-select" name="zone_id" aria-label="Default select example"></select>
                  <div class="help-text font-size-12 lh-base">{{ __('admin/setting.default_zone_set') }}</div>
                </div>
              </div>
            </x-admin::form.row>

            <x-admin-form-select title="{{ __('admin/setting.default_language') }}" name="locale" :value="old('locale', system_setting('base.locale', 'zh_cn'))" :options="$admin_languages" key="code" label="name">
              <div class="help-text font-size-12 lh-base">{{ __('admin/setting.default_language') }}</div>
            </x-admin-form-select>

            <x-admin-form-select title="{{ __('admin/setting.default_currency') }}" name="currency" :value="old('currency', system_setting('base.currency', 'USD'))" :options="$currencies->toArray()" key="code" label="name">
              <div class="help-text font-size-12 lh-base">{{ __('admin/setting.default_currency') }}</div>
            </x-admin-form-select>

              <x-admin-form-select title="{{ __('admin/setting.default_customer_group') }}" name="default_customer_group_id" :value="old('locale', system_setting('base.default_customer_group_id', ''))" :options="$customer_groups" key="id" label="name">
                  <div class="help-text font-size-12 lh-base">{{ __('admin/setting.default_customer_group') }}</div>
              </x-admin-form-select>

            <x-admin-form-input name="admin_name" title="{{ __('admin/setting.admin_name') }}" required value="{{ old('admin_name', system_setting('base.admin_name', 'admin')) }}">
              <div class="help-text font-size-12 lh-base">{{ __('admin/setting.admin_name_info') }}</div>
            </x-admin-form-input>

            {{-- <x-admin-form-select title="模版主题" name="theme" :value="old('theme', system_setting('base.theme', 'default'))" :options="$themes">
              <div class="help-text font-size-12 lh-base">主题模板选择</div>
            </x-admin-form-select> --}}

            <x-admin-form-switch name="tax" title="{{ __('admin/setting.enable_tax') }}" value="{{ old('tax', system_setting('base.tax', '0')) }}">
              <div class="help-text font-size-12 lh-base">{{ __('admin/setting.enable_tax_info') }}</div>
            </x-admin-form-switch>

            <x-admin-form-select title="{{ __('admin/setting.tax_address') }}" name="tax_address" :value="old('tax_address', system_setting('base.tax_address', 'shipping'))" :options="$tax_address">
              <div class="help-text font-size-12 lh-base">{{ __('admin/setting.tax_address_info') }}</div>
            </x-admin-form-select>
          </div>

          <div class="tab-pane fade" id="tab-image">
            <x-admin-form-image name="logo" title="logo" :value="old('logo', system_setting('base.logo', ''))">
              <div class="help-text font-size-12 lh-base">{{ __('common.recommend_size') }} 380*100</div>
            </x-admin-form-image>

            <x-admin-form-image name="favicon" title="favicon" :value="old('favicon', system_setting('base.favicon', ''))">
              <div class="help-text font-size-12 lh-base">{{ __('admin/setting.favicon_info') }}</div>
            </x-admin-form-image>

            <x-admin-form-image name="placeholder" title="{{ __('admin/setting.placeholder_image') }}" :value="old('placeholder', system_setting('base.placeholder', ''))">
              <div class="help-text font-size-12 lh-base">{{ __('admin/setting.placeholder_image_info') }}</div>
            </x-admin-form-image>
          </div>
        </div>


        <x-admin::form.row title="">
          <button type="submit" class="btn btn-primary mt-4">{{ __('common.submit') }}</button>
        </x-admin::form.row>
      </form>
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
      $http.get(`countries/${country_id}/zones`).then((res) => {
        if (res.data.zones.length > 0) {
          $('select[name="zone_id"]').html('');
          res.data.zones.forEach((zone) => {
            $('select[name="zone_id"]').append(`
              <option ${zone_id == zone.id ? 'selected' : ''} value="${zone.id}">${zone.name}</option>
            `);
          });
        } else {
          $('select[name="zone_id"]').html(`
            <option value="">请选择</option>
          `);
        }
      })
    }

    $(function() {
      getZones(country_id);

      $('select[name="country_id"]').on('change', function () {
        getZones($(this).val());
      });
    });
  </script>
@endpush




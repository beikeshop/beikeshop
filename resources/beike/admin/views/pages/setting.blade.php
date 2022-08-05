@extends('admin::layouts.master')

@section('title', '系统设置')


@section('content')
  <div id="plugins-app-form" class="card h-min-600">
    <div class="card-body">
      <h6 class="border-bottom pb-3 mb-4">基础设置</h6>
      <form action="" method="POST" id="app">
        @csrf
        <x-admin::form.row title="默认地址">
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
              <div class="text-muted font-size-12 lh-base">默认国家设置</div>
            </div>
            <div>
              <select class="form-select wp-200 zones-select" name="zone_id" aria-label="Default select example"></select>
              <div class="text-muted font-size-12 lh-base">默认省份设置</div>
            </div>
          </div>
        </x-admin::form.row>

        <x-admin::form.row title="默认语言">
          <select class="form-select wp-200 me-3" name="locale" aria-label="Default select example">
            @foreach ($languages as $language)
              <option
                value="{{ $language->code }}"
                {{ $language->code == system_setting('base.locale', 'zh_cn') ? 'selected': '' }}>
                {{ $language->name }}
              </option>
            @endforeach
          </select>
          <div class="text-muted font-size-12 lh-base">默认语言设置</div>
        </x-admin::form.row>

        <x-admin::form.row title="默认货币">
          <select class="form-select wp-200 me-3" name="currency" aria-label="Default select example">
            @foreach ($currencies as $currency)
              <option
                value="{{ $currency->code }}"
                {{ $currency->code == system_setting('base.currency', 'USD') ? 'selected': '' }}>
                {{ $currency->name }}
              </option>
            @endforeach
          </select>
          <div class="text-muted font-size-12 lh-base">默认货币设置</div>
        </x-admin::form.row>

        <x-admin-form-input name="admin_name" title="后台目录" value="{{ old('admin_name', system_setting('base.admin_name', 'admin')) }}">
          <div class="text-muted font-size-12 lh-base">管理后台目录,默认为admin</div>
        </x-admin-form-input>

        <x-admin::form.row title="模版主题">
          <select class="form-select wp-200 me-3" name="theme" aria-label="Default select example">
            @foreach ($themes as $theme)
              <option
                value="{{ $theme['value'] }}"
                {{ $theme['value'] == system_setting('base.theme', 'default') ? 'selected': '' }}>
                {{ $theme['label'] }}
              </option>
            @endforeach
          </select>
          <div class="text-muted font-size-12 lh-base">主题模板选择</div>
        </x-admin::form.row>

        <x-admin-form-switch name="tax" title="启用税费" value="{{ old('tax', system_setting('base.tax', '0')) }}">
          <div class="text-muted font-size-12 lh-base">是否启用税费计算</div>
        </x-admin-form-switch>

        <x-admin::form.row title="税费地址">
          <select class="form-select wp-200 me-3" name="tax_address" aria-label="Default select example">
            @foreach ($tax_address as $address)
              <option
                value="{{ $address['value'] }}"
                {{ $address['value'] == system_setting('base.address', 'shipping') ? 'selected': '' }}>
                {{ $address['label'] }}
              </option>
            @endforeach
          </select>
          <div class="text-muted font-size-12 lh-base">按什么地址计算税费</div>
        </x-admin::form.row>

        <x-admin::form.row title="">
          <button type="submit" class="btn btn-primary mt-4">提交</button>
        </x-admin::form.row>
      </form>
    </div>
  </div>
@endsection

@push('footer')
  <script>
    const country_id = {{ system_setting('base.country_id', '1') }};

    // 获取身份
    const getZones = (country_id) => {
      $http.get(`countries/${country_id}/zones`).then((res) => {
        console.log(res);
        if (res.data.zones.length > 0) {
          $('select[name="zone_id"]').html('');
          res.data.zones.forEach((zone) => {
            $('select[name="zone_id"]').append(`
              <option value="${zone.id}">${zone.name}</option>
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




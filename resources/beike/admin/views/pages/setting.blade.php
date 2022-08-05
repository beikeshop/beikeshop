@extends('admin::layouts.master')

@section('title', '系统设置')

@section('content')
  <div id="plugins-app-form" class="card h-min-600">
    <div class="card-body">
      <h6 class="border-bottom pb-3 mb-4">基础设置</h6>
      <form action="{{ admin_route('settings.store') }}" method="POST" id="app">
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
              <div class="help-text font-size-12 lh-base">默认国家设置</div>
            </div>
            <div>
              <select class="form-select wp-200 zones-select" name="zone_id" aria-label="Default select example"></select>
              <div class="help-text font-size-12 lh-base">默认省份设置</div>
            </div>
          </div>
        </x-admin::form.row>

        <x-admin-form-select title="默认语言" name="locale" :value="old('locale', system_setting('base.locale', 'zh_cn'))" :options="$languages->toArray()" key="code" label="name">
          <div class="help-text font-size-12 lh-base">默认语言设置</div>
        </x-admin-form-select>

        <x-admin-form-select title="默认货币" name="currency" :value="old('currency', system_setting('base.currency', 'USD'))" :options="$currencies->toArray()" key="code" label="name">
          <div class="help-text font-size-12 lh-base">默认货币设置</div>
        </x-admin-form-select>

        <x-admin-form-input name="admin_name" title="后台目录" value="{{ old('admin_name', system_setting('base.admin_name', 'admin')) }}">
          <div class="help-text font-size-12 lh-base">管理后台目录,默认为admin</div>
        </x-admin-form-input>

        <x-admin-form-select title="模版主题" name="theme" :value="old('theme', system_setting('base.theme', 'default'))" :options="$themes">
          <div class="help-text font-size-12 lh-base">主题模板选择</div>
        </x-admin-form-select>

        <x-admin-form-switch name="tax" title="启用税费" value="{{ old('tax', system_setting('base.tax', '0')) }}">
          <div class="help-text font-size-12 lh-base">是否启用税费计算</div>
        </x-admin-form-switch>

        <x-admin-form-select title="税费地址" name="tax_address" :value="old('tax_address', system_setting('base.address', 'shipping'))" :options="$tax_address">
          <div class="help-text font-size-12 lh-base">按什么地址计算税费</div>
        </x-admin-form-select>

        <x-admin::form.row title="">
          <button type="submit" class="btn btn-primary mt-4">提交</button>
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
    const zone_id = {{ system_setting('base.zone_id', '1') }};

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




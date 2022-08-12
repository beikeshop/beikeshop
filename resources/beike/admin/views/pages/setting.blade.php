@extends('admin::layouts.master')

@section('title', '系统设置')

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
            <a class="nav-link active" data-bs-toggle="tab" href="#tab-general">基础设置</a>
          </li>
          <li class="nav-item" role="presentation">
            <a class="nav-link" data-bs-toggle="tab" href="#tab-store">商店设置</a>
          </li>
          <li class="nav-item" role="presentation">
            <a class="nav-link" data-bs-toggle="tab" href="#tab-image">图片设置</a>
          </li>
        </ul>

        <div class="tab-content">
          <div class="tab-pane fade show active" id="tab-general">
            <x-admin-form-input name="meta_title" title="Meta 标题" value="{{ old('meta_title', system_setting('base.meta_title', '')) }}" />
            <x-admin-form-textarea name="meta_description" title="Meta 描述" value="{{ old('meta_description', system_setting('base.meta_description', '')) }}" />
            <x-admin-form-textarea name="meta_keyword" title="Meta 关键词" value="{{ old('meta_keyword', system_setting('base.meta_keyword', '')) }}" />
            <x-admin-form-input name="telephone" title="联系电话" value="{{ old('telephone', system_setting('base.telephone', '')) }}" />
            <x-admin-form-input name="email" title="E-Mail" value="{{ old('email', system_setting('base.email', '')) }}" />
          </div>

          <div class="tab-pane fade" id="tab-store">
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

            <x-admin-form-select title="默认语言" name="locale" :value="old('locale', system_setting('base.locale', 'zh_cn'))" :options="$admin_languages" key="code" label="name">
              <div class="help-text font-size-12 lh-base">默认语言设置</div>
            </x-admin-form-select>

            <x-admin-form-select title="默认货币" name="currency" :value="old('currency', system_setting('base.currency', 'USD'))" :options="$currencies->toArray()" key="code" label="name">
              <div class="help-text font-size-12 lh-base">默认货币设置</div>
            </x-admin-form-select>

            <x-admin-form-input name="admin_name" title="后台目录" required value="{{ old('admin_name', system_setting('base.admin_name', 'admin')) }}">
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
          </div>

          <div class="tab-pane fade" id="tab-image">
            <x-admin-form-image name="logo" title="网站 Logo" :value="old('logo', system_setting('base.logo', ''))">
              <div class="help-text font-size-12 lh-base">网站前台显示 380*100</div>
            </x-admin-form-image>

            <x-admin-form-image name="favicon" title="favicon" :value="old('web_icon', system_setting('base.web_icon', ''))">
              <div class="help-text font-size-12 lh-base">显示在浏览器选项卡上的小图标，必须为PNG格式大小为：32*32</div>
            </x-admin-form-image>

            <x-admin-form-image name="placeholder" title="网站 Logo" :value="old('placeholder', system_setting('base.placeholder', ''))">
              <div class="help-text font-size-12 lh-base">无图片或图片未找到时显示的占位图，建议尺寸：500*500</div>
            </x-admin-form-image>
          </div>
        </div>


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




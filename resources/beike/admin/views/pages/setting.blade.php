@extends('admin::layouts.master')

@section('title', __('admin/setting.index'))

@section('page-bottom-btns')
  <button type="button" class="btn btn-lg w-min-100 btn-primary submit-form" form="app">{{ __('common.save') }}</button>
  <button class="btn btn-lg btn-default w-min-100 ms-3" onclick="bk.back()">{{ __('common.return') }}</button>
@endsection

@push('header')
  <script src="{{ asset('vendor/cropper/cropper.min.js') }}"></script>
  <link rel="stylesheet" href="{{ asset('vendor/cropper/cropper.min.css') }}">
@endpush

@section('content')
  <div id="plugins-app-form" class="card h-min-600">
    <div class="card-body">
      <form action="{{ admin_route('settings.store') }}" class="needs-validation" novalidate method="POST" id="app" v-cloak>
        @csrf
        @if (session('success'))
          <x-admin-alert type="success" msg="{{ session('success') }}" class="mt-4"/>
        @endif
        @if (session('error'))
          <div class="alert alert-danger">
            {{ session('error') }}
          </div>
        @endif
        <ul class="nav nav-tabs nav-bordered mb-5" role="tablist">
          <li class="nav-item" role="presentation">
            <a class="nav-link active" data-bs-toggle="tab" href="#tab-general">{{ __('admin/setting.basic_settings') }}</a>
          </li>
          <li class="nav-item" role="presentation">
            <a class="nav-link" data-bs-toggle="tab" href="#tab-store">{{ __('admin/setting.store_settings') }}</a>
          </li>
          <li class="nav-item" role="presentation">
            <a class="nav-link" data-bs-toggle="tab" href="#tab-checkout">{{ __('admin/setting.checkout_settings') }}</a>
          </li>
          <li class="nav-item" role="presentation">
            <a class="nav-link" data-bs-toggle="tab" href="#tab-image">{{ __('admin/setting.picture_settings') }}</a>
          </li>
          <li class="nav-item" role="presentation">
            <a class="nav-link" data-bs-toggle="tab" href="#tab-express-company">{{ __('order.express_company') }}</a>
          </li>
          <li class="nav-item" role="presentation">
            <a class="nav-link" data-bs-toggle="tab" href="#tab-mail">{{ __('admin/setting.mail_settings') }}</a>
          </li>
          @hook('admin.setting.nav.after')
        </ul>

        <div class="tab-content">
          <div class="tab-pane fade show active" id="tab-general">
            @hook('admin.setting.general.before')
            <x-admin-form-input name="meta_title" title="{{ __('admin/setting.meta_title') }}" value="{{ old('meta_title', system_setting('base.meta_title', '')) }}" required />
            <x-admin-form-textarea name="meta_description" title="{{ __('admin/setting.meta_description') }}" value="{{ old('meta_description', system_setting('base.meta_description', '')) }}" />
            <x-admin-form-textarea name="meta_keywords" title="{{ __('admin/setting.meta_keywords') }}" value="{{ old('meta_keywords', system_setting('base.meta_keywords', '')) }}" />
            <x-admin-form-input name="telephone" title="{{ __('admin/setting.telephone') }}" value="{{ old('telephone', system_setting('base.telephone', '')) }}" />
            <x-admin-form-input name="email" title="{{ __('admin/setting.email') }}" value="{{ old('email', system_setting('base.email', '')) }}" />
            <x-admin::form.row title="{{ __('admin/setting.license_code') }}">
              <div class="input-group wp-400">
                <input type="text" class="form-control text-uppercase bg-light" name="license_code" placeholder="{{ __('admin/setting.license_code') }}" value="{{ old('license_code', system_setting('base.license_code', '')) }}" readonly="readonly">
                <button class="btn btn-outline-primary get-license-code" type="button">{{ __('admin/setting.license_code_get') }}</button>
              </div>
            </x-admin::form.row>

            <x-admin-form-switch name="guide" title="{{ __('admin/guide.heading_title') }}" value="{{ old('guide', system_setting('base.guide', '1')) }}" />
            @hook('admin.setting.general.after')
          </div>

          <div class="tab-pane fade" id="tab-store">
            @hook('admin.setting.store.before')
            <x-admin::form.row title="{{ __('admin/setting.default_address') }}">
              <div class="d-lg-flex">
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

            <x-admin-form-select title="{{ __('admin/setting.default_currency') }}" name="currency" :value="old('currency', system_setting('base.currency', 'USD'))" :options="$currencies->toArray()" key="code" label="name">
              <div class="help-text font-size-12 lh-base">{{ __('admin/setting.default_currency') }}</div>
            </x-admin-form-select>

            <x-admin-form-select title="{{ __('admin/setting.default_language') }}" name="locale" :value="old('locale', system_setting('base.locale', 'zh_cn'))" :options="$languages" key="code" label="name">
              <div class="help-text font-size-12 lh-base">{{ __('admin/setting.default_language') }}</div>
            </x-admin-form-select>

            @php
              $weights = [['code'=>'kg','name'=>'kg'], ['code'=>'g','name'=>'g'], ['code'=>'oz','name'=>'oz'], ['code'=>'lb','name'=>'lb']];
            @endphp
            <x-admin-form-select title="{{ __('admin/setting.weight_unit') }}" name="weight" :value="old('weight', system_setting('base.weight', 'kg'))" :options="$weights" key="code" label="name">
              <div class="help-text font-size-12 lh-base">{{ __('admin/setting.weight_unit') }}</div>
            </x-admin-form-select>

            <x-admin-form-select title="{{ __('admin/setting.default_customer_group') }}" name="default_customer_group_id" :value="old('locale', system_setting('base.default_customer_group_id', ''))" :options="$customer_groups" key="id" label="name">
                <div class="help-text font-size-12 lh-base">{{ __('admin/setting.default_customer_group') }}</div>
            </x-admin-form-select>

            <x-admin-form-input name="admin_name" title="{{ __('admin/setting.admin_name') }}" required value="{{ old('admin_name', system_setting('base.admin_name', 'admin')) }}">
              <div class="help-text font-size-12 lh-base">{{ __('admin/setting.admin_name_info') }}</div>
            </x-admin-form-input>

            <x-admin-form-input name="product_per_page" title="{{ __('admin/setting.product_per_page') }}" required value="{{ old('product_per_page', system_setting('base.product_per_page', 20)) }}">
            </x-admin-form-input>

            <x-admin-form-input name="cdn_url" title="{{ __('admin/setting.cdn_url') }}" value="{{ old('cdn_url', system_setting('base.cdn_url', '')) }}">
            </x-admin-form-input>

            <x-admin-form-textarea name="head_code" title="{{ __('admin/setting.head_code') }}" value="{!! old('head_code', system_setting('base.head_code', '')) !!}">
              <div class="help-text font-size-12 lh-base">{{ __('admin/setting.head_code_info') }}</div>
            </x-admin-form-textarea>

            @hook('admin.setting.store.after')

          </div>

          <div class="tab-pane fade" id="tab-image">

            @hook('admin.setting.image.before')

            <x-admin::form.row title="Logo">
              <div class="open-crop cursor-pointer bg-light wh-80 border d-flex justify-content-center align-items-center me-2 mb-2 position-relative" ratio="380/100">
                <img src="{{ image_resize(old('logo', system_setting('base.logo', ''))) }}" class="img-fluid">
              </div>
              <input type="hidden" value="{{ old('logo', system_setting('base.logo', '')) }}" name="logo">
              <div class="help-text font-size-12 lh-base">{{ __('common.recommend_size') }} 380*100</div>
            </x-admin::form.row>

            <x-admin::form.row title="favicon">
              <div class="open-crop cursor-pointer bg-light wh-80 border d-flex justify-content-center align-items-center me-2 mb-2 position-relative" ratio="32/32">
                <img src="{{ image_resize(old('favicon', system_setting('base.favicon', ''))) }}" class="img-fluid">
              </div>
              <input type="hidden" value="{{ old('favicon', system_setting('base.favicon', '')) }}" name="favicon">
              <div class="help-text font-size-12 lh-base">{{ __('admin/setting.favicon_info') }}</div>
            </x-admin::form.row>

            <x-admin::form.row :title="__('admin/setting.placeholder_image')">
              <div class="open-crop cursor-pointer bg-light wh-80 border d-flex justify-content-center align-items-center me-2 mb-2 position-relative" ratio="500/500">
                <img src="{{ image_resize(old('placeholder', system_setting('base.placeholder', ''))) }}" class="img-fluid">
              </div>
              <input type="hidden" value="{{ old('placeholder', system_setting('base.placeholder', '')) }}" name="placeholder">
              <div class="help-text font-size-12 lh-base">{{ __('admin/setting.placeholder_image_info') }}</div>
            </x-admin::form.row>

            @hook('admin.setting.image.after')
          </div>

          <div class="tab-pane fade" id="tab-express-company">
            @hook('admin.setting.express.before')
            <x-admin::form.row title="{{ __('order.express_company') }}">
              <table class="table table-bordered w-max-600">
                <thead>
                  <th>{{ __('order.express_company') }}</th>
                  <th>Code</th>
                  @hook('admin.setting.express.table.thead.th')
                  <th></th>
                </thead>
                <tbody>
                  <tr v-for="item, index in express_company" :key="index">
                    <td>
                      <input required placeholder="{{ __('order.express_company') }}" type="text" :name="'express_company['+ index +'][name]'" v-model="item.name" class="form-control">
                      <div class="invalid-feedback">{{ __('common.error_required', ['name' => __('order.express_company')]) }}</div>
                    </td>
                    <td>
                      <input required placeholder="{{ __('admin/setting.express_code_help') }}" type="text" :name="'express_company['+ index +'][code]'" v-model="item.code" class="form-control">
                      <div class="invalid-feedback">{{ __('common.error_required', ['name' => 'Code']) }}</div>
                    </td>
                    @hook('admin.setting.express.table.tbody.td')
                    <td><i @click="express_company.splice(index, 1)" class="bi bi-x-circle fs-4 text-danger cursor-pointer"></i></td>
                  </tr>
                  <tr>
                    <td colspan="2"><input v-if="!express_company.length" name="express_company" class="d-none"></td>
                    <td><i class="bi bi-plus-circle cursor-pointer fs-4" @click="addCompany"></i></td>
                  </tr>
                </tbody>
              </table>
            </x-admin::form.row>
            @hook('admin.setting.express.after')
          </div>

          <div class="tab-pane fade" id="tab-mail">

            @hook('admin.setting.mail.before')

            <x-admin-form-switch name="use_queue" title="{{ __('admin/setting.use_queue') }}" value="{{ old('use_queue', system_setting('base.use_queue', '0')) }}">
              {{-- <div class="help-text font-size-12 lh-base">{{ __('admin/setting.enable_tax_info') }}</div> --}}
            </x-admin-form-switch>
            <x-admin::form.row title="{{ __('admin/setting.mail_engine') }}">
              <select name="mail_engine" v-model="mail_engine" class="form-select wp-200 me-3">
                <option :value="item.code" v-for="item, index in source.mailEngines" :key="index">@{{ item.name }}</option>
              </select>
              <div v-if="mail_engine == 'log'" class="help-text font-size-12 lh-base">{{ __('admin/setting.mail_log') }}</div>
            </x-admin::form.row>

            <div v-if="mail_engine == 'smtp'">
              <x-admin-form-input name="smtp[host]" required title="{{ __('admin/setting.smtp_host') }}" value="{{ old('host', system_setting('base.smtp.host', '')) }}">
              </x-admin-form-input>
              <x-admin-form-input name="smtp[username]" required title="{{ __('admin/setting.smtp_username') }}" value="{{ old('username', system_setting('base.smtp.username', '')) }}">
              </x-admin-form-input>
              <x-admin-form-input name="smtp[password]" required title="{{ __('admin/setting.smtp_password') }}" value="{{ old('password', system_setting('base.smtp.password', '')) }}">
                <div class="help-text font-size-12 lh-base">{{ __('admin/setting.smtp_password_info') }}</div>
              </x-admin-form-input>
              <x-admin-form-input name="smtp[encryption]" required title="{{ __('admin/setting.smtp_encryption') }}" value="{{ old('encryption', system_setting('base.smtp.encryption', 'TLS')) }}">
                <div class="help-text font-size-12 lh-base">{{ __('admin/setting.smtp_encryption_info') }}</div>
              </x-admin-form-input>
              <x-admin-form-input name="smtp[port]" required title="{{ __('admin/setting.smtp_port') }}" value="{{ old('port', system_setting('base.smtp.port', '465')) }}">
              </x-admin-form-input>
              <x-admin-form-input name="smtp[timeout]" required title="{{ __('admin/setting.smtp_timeout') }}" value="{{ old('timeout', system_setting('base.smtp.timeout', '5')) }}">
              </x-admin-form-input>
            </div>

            <div v-if="mail_engine == 'sendmail'">
              <x-admin-form-input name="sendmail[path]" :placeholder="222" required title="{{ __('admin/setting.sendmail_path') }}" value="{{ old('path', system_setting('base.sendmail.path', '')) }}">
                <div class="help-text font-size-12 lh-base">系统 sendmail 执行路径, 一般为 /usr/sbin/sendmail -bs</div>
              </x-admin-form-input>
            </div>

            <div v-if="mail_engine == 'mailgun'">
              <x-admin-form-input name="mailgun[domain]" required title="{{ __('admin/setting.mailgun_domain') }}" value="{{ old('domain', system_setting('base.mailgun.domain', '')) }}">
              </x-admin-form-input>
              <x-admin-form-input name="mailgun[secret]" required title="{{ __('admin/setting.mailgun_secret') }}" value="{{ old('secret', system_setting('base.mailgun.secret', '')) }}">
              </x-admin-form-input>
              <x-admin-form-input name="mailgun[endpoint]" required title="{{ __('admin/setting.mailgun_endpoint') }}" value="{{ old('endpoint', system_setting('base.mailgun.endpoint', '')) }}">
              </x-admin-form-input>
            </div>

            @hook('admin.setting.mail.after')

          </div>

          <div class="tab-pane fade" id="tab-checkout">
            <x-admin-form-switch name="show_price_after_login" title="{{ __('admin/setting.show_price_after_login') }}" value="{{ old('show_price_after_login', system_setting('base.show_price_after_login', '0')) }}">
              <div class="help-text font-size-12 lh-base show-price-error-text">{{ __('admin/setting.show_price_after_login_tips') }}</div>
            </x-admin-form-switch>

            <x-admin-form-switch name="guest_checkout" title="{{ __('admin/setting.guest_checkout') }}" value="{{ old('guest_checkout', system_setting('base.guest_checkout', '1')) }}" />

            <x-admin-form-switch name="customer_approved" title="{{ __('admin/setting.customer_approved') }}" value="{{ old('customer_approved', system_setting('base.customer_approved', '0')) }}">
            </x-admin-form-switch>

            <x-admin-form-switch name="tax" title="{{ __('admin/setting.enable_tax') }}" value="{{ old('tax', system_setting('base.tax', '0')) }}">
              <div class="help-text font-size-12 lh-base">{{ __('admin/setting.enable_tax_info') }}</div>
            </x-admin-form-switch>

            <x-admin-form-select title="{{ __('admin/setting.tax_address') }}" name="tax_address" :value="old('tax_address', system_setting('base.tax_address', 'shipping'))" :options="$tax_address">
            </x-admin-form-select>

            <x-admin-form-input name="rate_api_key" title="{{ __('admin/setting.rate_api_key') }}" value="{{ old('rate_api_key', system_setting('base.rate_api_key', '')) }}">
              <div class="help-text font-size-12 lh-base">
                <a class="text-secondary" href="https://www.exchangerate-api.com/" target="_blank">www.exchangerate-api.com</a>
              </div>
            </x-admin-form-input>

            <x-admin::form.row :title="__('admin/setting.order_auto_cancel')">
              <div class="input-group wp-400">
                <input type="number" value="{{ old('order_auto_cancel', system_setting('base.order_auto_cancel', '')) }}" name="order_auto_cancel" class="form-control" placeholder="{{ __('admin/setting.order_auto_cancel') }}">
                <span class="input-group-text">{{ __('common.text_hour') }}</span>
              </div>
            </x-admin::form.row>

            <x-admin::form.row :title="__('admin/setting.order_auto_complete')">
              <div class="input-group wp-400">
                <input type="number" value="{{ old('order_auto_complete', system_setting('base.order_auto_complete', '')) }}" name="order_auto_complete" class="form-control" placeholder="{{ __('admin/setting.order_auto_complete') }}">
                <span class="input-group-text">{{ __('common.text_hour') }}</span>
              </div>
            </x-admin::form.row>
          </div>

          @hook('admin.setting.after')
        </div>

        <x-admin::form.row title="">
          <button type="submit" class="btn btn-primary d-none mt-4">{{ __('common.submit') }}</button>
        </x-admin::form.row>
      </form>
    </div>
  </div>

  <div class="modal fade" id="modal" tabindex="-1" data-bs-backdrop="static" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <div class="d-flex align-items-center">
            <h5 class="modal-title" id="exampleModalLabel">{{ __('shop/account/edit.crop') }}</h5>
            <div class="cropper-size ms-4">{{ __('common.cropper_size') }}：<span></span></div>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="img-container">
            <img id="cropper-image" src="{{ image_resize('/') }}" class="img-fluid">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('shop/common.cancel') }}</button>
          <button type="button" class="btn btn-primary cropper-crop">{{ __('shop/common.confirm') }}</button>
        </div>
      </div>
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
      $http.get(`countries/${country_id}/zones`, null, {hload: true}).then((res) => {
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
    });

  </script>

  <script>
    let ratio = 1;
    let $crop = null
    var cropper;

    $(function() {
      $('.open-crop').click(function() {
        var image = document.getElementById('cropper-image');
        $crop = $(this);
        ratio = $(this).attr('ratio')
        var cropper;
        var $input = $('<input type="file" accept="image/*" class="d-none">');
        $input.click();
        $input.change(function() {
          var files = this.files;
          var done = function(url) {
            image.src = url;
            $('#modal').modal('show');
          };

          if (files && files.length > 0) {
            var reader = new FileReader();
            reader.onload = function(e) {
              done(reader.result);
            };
            reader.readAsDataURL(files[0]);
          }
        });
      });

      $('input[name="show_price_after_login"]').change(function () {
        if ($(this).val() == 1 && $('input[name="guest_checkout"]').prop('checked') == true) {
          $('input[name="guest_checkout"]').prop('checked', true);
          $('.show-price-error-text').addClass('text-danger fw-bold');
          setTimeout(() => {
            $('.show-price-error-text').removeClass('text-danger fw-bold');
          }, 1200);
        }
      });

      $('input[name="guest_checkout"]').change(function () {
        if ($(this).val() == 1 && $('input[name="show_price_after_login"]').prop('checked') == true) {
          $('input[name="show_price_after_login"]').prop('checked', 1);
          $('.show-price-error-text').addClass('text-danger fw-bold');
          setTimeout(() => {
            $('.show-price-error-text').removeClass('text-danger fw-bold');
          }, 1200);
        }
      });
    });

    $('#modal').on('shown.bs.modal', function() {
      var image = document.getElementById('cropper-image');
      cropper = new Cropper(image, {
        initialAspectRatio: ratio.split('/')[0] / ratio.split('/')[1],
        autoCropArea: 1,
        viewMode: 1,
        // 回调 获取尺寸
        crop: function(event) {
          $('.cropper-size span').html(parseInt(event.detail.width) + ' * ' + parseInt(event.detail.height))
        }
      });
    }).on('hidden.bs.modal', function() {
      cropper.destroy();
      cropper = null;
    });

    $('.cropper-crop').click(function(event) {
      var canvas;

      $('#modal').modal('hide');

      if (cropper) {
        canvas = cropper.getCroppedCanvas({
          imageSmoothingQuality: 'high',
        });
        canvas.toBlob(function(blob) {
          var formData = new FormData();

          formData.append('file', blob, 'avatar.png');
          formData.append('type', 'avatar');
          $http.post('{{ shop_route('file.store') }}', formData).then(res => {
            $crop.find('img').attr('src', res.data.url);
            $crop.next('input').val(res.data.value);
          })
        });
      }
    });
  </script>

  <script>
    let app = new Vue({
      el: '#app',
      data: {
        mail_engine: @json(old('mail_engine', system_setting('base.mail_engine', ''))),
        express_company: @json(old('express_company', system_setting('base.express_company', []))),

        source: {
          mailEngines: [
            {name: '{{ __('admin/builder.text_no') }}', code: ''},
            {name: 'SMTP', code: 'smtp'},
            {name: 'Sendmail', code: 'sendmail'},
            {name: 'Mailgun', code: 'mailgun'},
            {name: 'Log', code: 'log'},
          ]
        },
      },
      methods: {
        addCompany() {
          if (typeof this.express_company == 'string') {
            this.express_company = [];
          }

          this.express_company.push({name: '', code: ''})
        },
      }
    });
  </script>
@endpush




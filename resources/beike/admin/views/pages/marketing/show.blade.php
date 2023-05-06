@extends('admin::layouts.master')

@section('title', __('admin/marketing.marketing_show'))

@section('body-class', 'page-marketing-info')

@push('header')
<script src="{{ asset('vendor/qrcode/qrcode.min.js') }}"></script>
<link rel="stylesheet" href="{{ asset('vendor/sweetalert2/sweetalert2.min.css') }}">
<script src="{{ asset('vendor/sweetalert2/sweetalert2.min.js') }}"></script>
@endpush

@section('content')
@php
$data = $plugin['data'];
@endphp
<div class="card mb-4" id="app">
  <div class="card-header">
    <h5 class="card-title">{{ __('admin/marketing.marketing_show') }}</h5>
  </div>
  <div class="card-body">
    <div class="d-lg-flex plugin-info">
      <div class="d-flex justify-content-between align-items-center plugin-icon-wrap">
        <img src="{{ $data['icon_big'] }}" class="img-fluid plugin-icon">
        <img src="{{ $data['icon_big'] }}" class="img-fluid plugin-icon-shadow">
      </div>
      <div class="ms-lg-5 mt-2">
        <h2 class="card-title mb-4">{{ $data['name'] }}</h2>
        <div class="plugin-item d-lg-flex align-items-center mb-4 lh-1 text-secondary">
          <div class="mx-3 ms-0">{{ __('admin/marketing.download_count') }}：{{ $data['downloaded'] }}</div><span
            class="vr lh-1 bg-secondary"></span>
          <div class="mx-3">{{ __('page_category.views') }}：{{ $data['viewed'] }}</div><span
            class="vr lh-1 bg-secondary"></span>
          <div class="mx-3">{{ __('admin/marketing.last_update') }}：{{ $data['updated_at'] }}</div><span
            class="lh-1 bg-secondary"></span>
        </div>

        <div class="mb-4">
          <div class="mb-2 fw-bold">{{ __('product.price') }}：</div>
          <div class="fs-3 fw-bold">{{ $data['price_format'] }}</div>
        </div>

        <div class="mb-4">
          <div class="mb-2 fw-bold">{{ __('admin/marketing.text_version') }}：</div>
          <div>{{ $data['version'] }}</div>
        </div>

        <div class="mb-4">
          <div class="mb-2 fw-bold">{{ __('admin/marketing.text_compatibility') }}：</div>
          <div>{{ $data['version_name_format'] }}</div>
        </div>

        <div class="mb-4">
          <div class="mb-2 fw-bold">{{ __('admin/marketing.text_author') }}：</div>
          <div class="d-inline-block">
            <a href="{{ config('app.url') }}/account/{{ $data['developer']['id'] }}" target="_blank"
              class="d-flex align-items-center text-dark">
              <div class="border wh-50 rounded-5 d-flex justify-content-between align-items-center"><img
                  src="{{ $data['developer']['avatar'] }}" class="img-fluid rounded-5"></div>
              <div class="ms-2">
                <div class="mb-1 fw-bold">{{ $data['developer']['name'] }}</div>
                <div>{{ $data['developer']['email'] }}</div>
              </div>
            </a>
          </div>
        </div>

        <div class="mb-4">
          @if ($data['available'])
          @if ($data['downloadable'])
          <button class="btn btn-primary btn-lg" @click="downloadPlugin"><i class="bi bi-cloud-arrow-down-fill"></i> {{
            __('admin/marketing.download_plugin') }}</button>
          <div class="mt-3 d-none download-help"><a href="{{ admin_route('plugins.index') }}" class=""><i
                class="bi bi-cursor-fill"></i> <span></span></a></div>
          @else
          <div class="mb-2 fw-bold">{{ __('admin/marketing.select_pay') }}</div>
          <div class="mb-4">
            <el-radio-group v-model="payCode" size="small" class="radio-group">
              <el-radio class="me-1" label="wechatpay" border><img src="{{ asset('image/wechat.png') }}"
                  class="img-fluid"></el-radio>
              <el-radio class="" label="alipay" border><img src="{{ asset('image/alipay.png') }}" class="img-fluid">
              </el-radio>
            </el-radio-group>
          </div>
          <button class="btn btn-primary btn-lg w-min-100 fw-bold" @click="marketingBuy">{{
            __('admin/marketing.btn_buy') }}</button>
          @endif
          @else
          <div class="alert alert-warning" role="alert">
            {!! __('admin/marketing.version_compatible_text') !!}
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>

  <div class="code-pop" style="display: none;">
    <div class="text-center py-3 fs-5">{{ __('admin/marketing.text_pay') }}：<span class="fs-3 text-danger fw-bold">@{{
        wechatpay_price }}</span></div>
    <div class="d-flex justify-content-center align-items-center" id="code-info"></div>
  </div>

  <el-dialog title="{{ __('admin/marketing.set_token') }}" :close-on-click-modal="false"
    :visible.sync="setTokenDialog.show" width="820px" @close="dialogOnClose">
    {{-- <el-input type="textarea" :rows="3" placeholder="{{ __('admin/marketing.set_token') }}"
      v-model="setTokenDialog.token">
    </el-input>
    <div class="mt-3 text-secondary fs-6">{{ __('admin/marketing.get_token_text') }} <a
        href="{{ config('beike.api_url') }}/account/websites?domain={{ $domain }}" class="link-primary"
        target="_blank">{{ __('admin/marketing.get_token') }}</a></div>
    <div class="d-flex justify-content-end align-items-center mt-4">
      <span slot="footer" class="dialog-footer">
        <el-button @click="setTokenDialog.show = false">{{ __('common.cancel') }}</el-button>
        <el-button type="primary" @click="submitToken">{{ __('common.confirm') }}</el-button>
      </span>
    </div> --}}
    <div class="login-wrap d-flex mb-4 px-2">
      <div class="card w-50 border-0 p-0 shadow-none">
        <div class="p-0 card-header">
          <h5 class="bg-light px-2 py-3 text-black mb-3">{{ __('shop/login.login') }}</h5>
        </div>
        <div class="card-body p-0">
          <el-form ref="loginForm" :model="loginForm" :rules="loginRules">
            <el-form-item label="{{ __('shop/login.email') }}" prop="email">
              <el-input @keyup.enter.native="checkedBtnLogin('loginForm')" v-model="loginForm.email"
                placeholder="{{ __('shop/login.email_address') }}"></el-input>
            </el-form-item>

            <el-form-item label="{{ __('shop/login.password') }}" prop="password">
              <el-input @keyup.enter.native="checkedBtnLogin('loginForm')" type="password" v-model="loginForm.password"
                placeholder="{{ __('shop/login.password') }}"></el-input>
            </el-form-item>

            <div class="mt-5 mb-3">
              <button type="button" @click="checkedBtnLogin('loginForm')" class="btn btn-primary btn-lg w-100 fw-bold"><i
                  class="bi bi-box-arrow-in-right"></i> {{ __('shop/login.login') }}</button>
            </div>
          </el-form>
        </div>
      </div>

      <div class="d-flex vr-wrap mx-5">
        <div class="vr bg-secondary"></div>
      </div>
      <div class="card w-50 border-0 p-0 shadow-none">
        <div class="p-0 card-header">
          <h5 class="bg-light px-2 py-3 text-black mb-3">{{ __('shop/login.new') }}</h5>
        </div>
        <div class="card-body p-0">
          <el-form ref="registerForm" :model="registerForm" :rules="registeRules">
            <el-form-item label="{{ __('admin/customer.user_name') }}" prop="name">
              <el-input @keyup.enter.native="checkedBtnLogin('registerForm')" v-model="registerForm.name"
                placeholder="{{ __('admin/customer.user_name') }}"></el-input>
            </el-form-item>

            <el-form-item label="{{ __('address.phone') }}" prop="telephone">
              <el-input @keyup.enter.native="checkedBtnLogin('registerForm')" v-model="registerForm.telephone"
                placeholder="{{ __('address.phone') }}"></el-input>
            </el-form-item>

            <el-form-item label="{{ __('shop/login.email') }}" prop="email">
              <el-input @keyup.enter.native="checkedBtnLogin('registerForm')" v-model="registerForm.email"
                placeholder="{{ __('shop/login.email_address') }}"></el-input>
            </el-form-item>

            <el-form-item label="{{ __('shop/login.password') }}" prop="password">
              <el-input @keyup.enter.native="checkedBtnLogin('registerForm')" type="password"
                v-model="registerForm.password" placeholder="{{ __('shop/login.password') }}"></el-input>
            </el-form-item>

            <div class="mt-5 mb-3">
              <button type="button" @click="checkedBtnLogin('registerForm')"
                class="btn btn-primary btn-lg w-100 fw-bold"><i class="bi bi-person"></i> {{ __('shop/login.register')
                }}</button>
            </div>
          </el-form>
        </div>
      </div>
    </div>
  </el-dialog>
</div>

@if ($data['description'])
<div class="card h-min-200">
  <div class="card-header">
    <h5 class="card-title">{{ __('admin/marketing.download_description') }}</h5>
  </div>
  <div class="card-body">
    {!! $data['description'] !!}
  </div>
</div>
@endif
@endsection

@push('footer')
<script>
  let app = new Vue({
    el: '#app',

    data: {
      payCode: 'wechatpay',
      wechatpay_price: '',
      radio3: '1',
      setTokenDialog: {
        show: false,
        token: @json(system_setting('base.developer_token') ?? ''),
      },

      loginForm: {
          email: '',
          password: '',
        },

        registerForm: {
          email: '',
          name: '',
          telephone: '',
          qq: '',
          password: '',
        },

        loginRules: {
          email: [
            {required: true, message: '{{ __('shop/login.enter_email') }}', trigger: 'change'},
            {type: 'email', message: '{{ __('shop/login.email_err') }}', trigger: 'change'},
          ],
          password: [
            {required: true, message: '{{ __('shop/login.enter_password')}}', trigger: 'change'}
          ]
        },

        registeRules: {
          name: [
            {required: true, message: '{{ __('common.error_required', ['name' => __('admin/customer.user_name')]) }}', trigger: 'change'},
          ],
          telephone: [
            {required: true, message: '{{ __('common.error_required', ['name' => __('address.phone')]) }}', trigger: 'change'},
          ],
          email: [
            {required: true, message: '{{ __('common.error_required', ['name' => __('shop/login.email')]) }}', trigger: 'change'},
            {type: 'email', message: '{{ __('shop/login.email_err') }}', trigger: 'change'},
          ],
          password: [
            {required: true, message: '{{ __('common.error_required', ['name' => __('shop/login.password')]) }}', trigger: 'change'},
          ],
        },
    },

    methods: {
      checkedBtnLogin(form) {
        let _data = this.loginForm, url = '/login'

        if (form == 'registerForm') {
          _data = this.registerForm, url = '/register'
        }

        this.$refs['loginForm'].clearValidate();
        this.$refs['registerForm'].clearValidate();

        this.$refs[form].validate((valid) => {
          if (!valid) {
            layer.msg('{{ __('shop/login.check_form') }}', () => {})
            return;
          }

          return;

          $http.post(url, _data).then((res) => {
            layer.msg(res.message)
          })
        });
      },

      dialogOnClose() {
        Object.keys(this.loginForm).forEach(key => this.loginForm[key] = '');
        Object.keys(this.registerForm).forEach(key => this.registerForm[key] = '');

        setTimeout(() => {
          this.$refs['loginForm'].clearValidate();
          this.$refs['registerForm'].clearValidate();
        }, 0);
      },

      downloadPlugin() {
        if (!this.setTokenDialog.token) {
          return this.setTokenDialog.show = true;
        }

        $http.post('{{ admin_route('marketing.download', ['code' => $data['code']]) }}').then((res) => {
          $('.download-help').removeClass('d-none').find('span').text(res.message);
        })
      },

      marketingBuy() {
        if (!this.setTokenDialog.token) {
          console.log(1);
          return this.setTokenDialog.show = true;
        }

        $http.post('{{ admin_route('marketing.buy', ['code' => $data['code']]) }}', {
          payment_code: this.payCode, return_url: '{{ admin_route('marketing.show', ['code' => $data['code']]) }}'}).then((res) => {
          if (res.status == "fail") {
            layer.msg(res.message, () => {})
            return;
          }

          if (res.data.payment_code == 'wechatpay') {
            this.wechatpay_price = res.data.price_format
            this.getQrcode(res.data.pay_url);
          }

          if (res.data.payment_code == 'alipay') {
            window.open(res.data.pay_url, '_blank');

            Swal.fire({
              title: '{{ __('admin/marketing.ali_pay_success') }}',
              text: '{{ __('admin/marketing.ali_pay_text') }}',
              icon: 'question',
              confirmButtonColor: '#fd560f',
              confirmButtonText: '{{ __('common.confirm') }}',
              willClose: function () {
                window.location.reload();
              },
            })
          }
        })
      },

      getQrcode(url) {
        const self = this;
        new QRCode('code-info', {
          text: url,
          width: 270,
          height: 270,
          correctLevel : QRCode.CorrectLevel.M
        });

        setTimeout(() => {
          Swal.fire({
            title: '{{ __('admin/marketing.wxpay') }}',
            width: 400,
            height: 470,
            heightAuto: false,
            html: $('.code-pop').html(),
            showConfirmButton: false,
            didOpen: function () {
              // 微信支付二维码 轮询监控支付状态
              self.chekOrderStatus();
              self.timer = window.setInterval(() => {
                setTimeout(self.chekOrderStatus(), 0);
              }, 1000)
            },
            didClose: function () {
              $('#code-info').html('');
            },
            didDestroy: function () {
              window.clearInterval(self.timer)
            },
          })
        }, 100)
      },

      chekOrderStatus() {
        $http.get('{{ admin_route('marketing.show', ['code' => $data['code']]) }}', null, {hload: true}).then((res) => {
          console.log(res.plugin.data.downloadable)
          if (res.plugin.data.downloadable) {
            window.clearInterval(this.timer)
            Swal.fire({
              title: '{{ __('admin/marketing.pay_success_title') }}',
              text: '{{ __('admin/marketing.pay_success_text') }}',
              icon: 'success',
              focusConfirm: false,
              confirmButtonColor: '#75bc4d',
              confirmButtonText: '{{ __('common.confirm') }}',
              didClose: function () {
                window.location.reload();
              },
            })
          }
        })
      },

      submitToken() {
        if (!this.setTokenDialog.token) {
          return;
        }

        $http.post('{{ admin_route('settings.store_token') }}', {developer_token: this.setTokenDialog.token}).then((res) => {
          this.setTokenDialog.show = false;
          layer.msg(res.message);
        })
      }
    },

    destroyed() {
      window.clearInterval(this.timer)
    }
  })
</script>
@endpush
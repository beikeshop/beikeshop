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


<div class="mb-2 text-secondary crumb"><a class="text-secondary" href="{{ admin_route('home.index') }}"><i class="bi bi-house"></i> {{__('common.home')}}</a> / <a class="text-secondary" href="{{ admin_route('marketing.index') }}">{{__('admin/common.marketing')}}</a> / {{ $data['name'] }}</div>
<div class="card mb-4" id="app" v-cloak>
  <div class="card-body">
    <div class="d-lg-flex plugin-info">
      <div class="d-flex justify-content-between align-items-center plugin-icon-wrap">
        @if ($data['origin_price'])
        <div class="sale-wrap"><img src="{{ $data['sale_icon'] }}" class="img-fluid"></div>
        @endif
        <img src="{{ $data['icon_big'] }}" class="img-fluid plugin-icon">
        <img src="{{ $data['icon_big'] }}" class="img-fluid plugin-icon-shadow">
      </div>
      <div class="ms-lg-5 wp-600 mt-3 mt-lg-0 text-box marketing-right">
        <div>
          <div class="mb-3 d-flex justify-content-between align-items-center">
            <h2 class="card-title">{{ $data['name'] }}</h2>
            <div>
              <button class="btn btn-outline-secondary btn-sm btn-back" onclick="history.go(-1);"><i class="bi bi-arrow-left"></i> {{__('common.return')}}</button>
            </div>
          </div>
          <div class="plugin-item d-lg-flex align-items-center mb-3 lh-1 text-secondary">
            <div class="mx-3 ms-0">{{ __('admin/marketing.download_count') }}：{{ $data['downloaded'] }}</div><span
              class="vr lh-1 bg-secondary"></span>
            <div class="mx-3">{{ __('page_category.views') }}：{{ $data['viewed'] }}</div><span
              class="vr lh-1 bg-secondary"></span>
            <div class="mx-3">{{ __('admin/marketing.last_update') }}：{{ $data['updated_at'] }}</div><span
              class="lh-1 bg-secondary"></span>
          </div>
          <div class="mb-2">
            <div class="mb-2">
              <div class="fs-3 me-1 d-inline-block fw-bold" style="margin-left: -4px">
                <span>{{ $data['price_format'] }}</span>
                @if ($data['origin_price'])
                  <span class="origin-price text-decoration-line-through text-secondary">{{ $data['origin_price_format'] }}</span>
                @endif
                <span></span>
              </div>
              @if ($data['free_service_months'])
              <span>( {{ __('admin/marketing.free_days') }} {{ $data['free_service_months'] ?? 0 }} {{ $data['is_subscribe'] ?  __('admin/marketing.free_days_dy') : __('admin/marketing.free_days_over') }} )</span>
              @endif
            </div>
            @if (isset($data['plugin_services']) && count($data['plugin_services']) && $data['is_subscribe'])
            <div class="mb-2">
              <div class="mb-1 fw-bold">
                {{ __('admin/marketing.subscription_price') }}：
              </div>
              <div>
                @foreach ($data['plugin_services'] as $item)
                {{ $item['price_format'] }}/{{ $item['months'] }}{{__('admin/marketing.munths')}} &nbsp;
                @endforeach
              </div>
            </div>
            @endif
            @if ($data['service_date_to'] ?? 0)
            <div class="mb-2">
              <div class="mb-1 fw-bold">{{ __('admin/marketing.service_date_to') }}：</div>
              <div>{{ $data['service_date_to'] }} ( <span class="{{ $data['days_remaining'] < 7 ? 'red' : '' }}"> {{ $data['days_remaining'] }} {{ __('admin/marketing.days') }}</span> )</div>
            </div>
            @endif
            <div class="mb-2">
              <div class="mb-1 fw-bold">{{ __('admin/marketing.text_version') }}：</div>
              <div>{{ $data['version'] }}</div>
            </div>
            <div class="mb-2">
              <div class="mb-1 fw-bold">{{ __('admin/marketing.text_compatibility') }}：</div>
              <div>{{ $data['version_name_format'] }}</div>
            </div>
            <div class="mb-2">
              <div class="mb-2 fw-bold">{{ __('admin/marketing.text_author') }}：</div>
              <div>
                  @php
                  $lvClass = '';

                  if ($data['developer']['lv'] == 3) {
                    $lvClass = 'lv3-border';
                  } elseif ($data['developer']['lv'] == 2) {
                    $lvClass = 'lv2-border';
                  } elseif ($data['developer']['lv'] == 1) {
                    $lvClass = 'lv1-border';
                  }
                @endphp
                <div class="d-inline-block">
                  <a href="{{ beike_api_url() }}/account/{{ $data['developer']['id'] }}" target="_blank"
                    class="d-flex align-items-center text-dark">
                    <div class="border wh-50 rounded-5 d-flex justify-content-between align-items-center bg-white avatar-wrap {{ $lvClass }} {{ $data['developer']['is_official'] ? 'official' : '' }}" @if ($data['developer']['is_official']) title="{{ __('admin/marketing.official_developer') }}" @elseif ($data['developer']['lv'] == 3) title="{{ __('admin/marketing.lv3_developer') }}" @elseif ($data['developer']['lv'] == 2) title="{{ __('admin/marketing.lv2_developer') }}" @elseif ($data['developer']['lv'] == 1) title="{{ __('admin/marketing.lv1_developer') }}"  @endif>
                      <img src="{{ $data['developer']['avatar'] }}" class="img-fluid rounded-5">
                      @if (!$data['developer']['is_official'])
                        @if ($data['developer']['lv'] == 3)
                        <div class="tags">lv3</div>
                        @elseif ($data['developer']['lv'] == 2)
                        <div class="tags">lv2</div>
                        @elseif ($data['developer']['lv'] == 1)
                        <div class="tags">lv1</div>
                        @endif
                      @else
                      <div class="tags">V</div>
                      @endif
                    </div>
                    <div class="ms-2 d-flex">
                      <div>
                        <div class="mb-1">{{ $data['developer']['name'] }}</div>
                        @if ($data['developer']['is_official'])
                        <div class="mt-1 response-dev">{{ __('admin/marketing.official_developer') }}</div>
                        @elseif ($data['developer']['lv'] == 3)
                        <div class="mt-1 response">{{ __('admin/marketing.lv3_developer') }}</div>
                        @elseif ($data['developer']['lv'] == 2)
                        <div class="mt-1 response">{{ __('admin/marketing.lv2_developer') }}</div>
                        @elseif ($data['developer']['lv'] == 1)
                        <div class="mt-1 response">{{ __('admin/marketing.lv1_developer') }}</div>
                        @endif
                      </div>
                    </div>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>


        <div class="mb-0">
          @if ($data['available'])
            @if (!$data['downloadable'] || (isset($data['plugin_services']) && count($data['plugin_services']) && !$data['is_subscribe']))
            <div class="my-3">
              <el-radio-group v-model="payCode" size="small" class="radio-group">
                <el-radio class="me-1" v-for="item, index in payment_methods" :key="index" :label="item" border><img :src="'image/' + item + '.png'" class="img-fluid"></el-radio>
              </el-radio-group>
            </div>
            @endif

            @if (!system_setting('base.developer_token'))
              <button class="btn btn-primary btn-lg w-min-100 fw-bold" @click="marketingBuy">
                {{ __('admin/marketing.login_download') }}
              </button>
            @elseif ($data['downloadable'])
              <div>
                <button class="btn btn-primary btn-lg" @click="downloadPlugin"><i class="bi bi-cloud-arrow-down-fill"></i> {{
                  __('admin/marketing.download_plugin') }}</button>
                @if (isset($data['plugin_services']) && count($data['plugin_services']))
                  @if (!$data['is_subscribe'])
                  <button class="btn btn-outline-primary btn-lg w-min-100 fw-bold ms-2" @click="openService">{{ __('admin/marketing.btn_buy_service') }}</button>
                  @else
                    <a class="btn btn-outline-primary btn-lg w-min-100 fw-bold ms-2" href="{{ config('beike.api_url') }}/subscribe/{{ $data['code'] }}" target="_blank">{{ __('admin/marketing.buy_subscription') }}</a>
                  @endif
                @endif
                @if ( $data['service_date_to'] ?? 0 && !$data['is_subscribe'])
                <a :href="toBkTicketUrl()" target="_blank" class="btn btn-outline-primary btn-lg fw-bold ms-2 {{ $data['days_remaining'] <= 0 ? 'd-none' : '' }}">{{ __('admin/marketing.plugin_ticket') }}</a>
                @endif
              </div>
              <div class="mt-3 d-none download-help"><a href="{{ admin_route('plugins.index') }}" class=""><i class="bi bi-cursor-fill"></i> <span></span></a></div>
            @else
              <button class="btn btn-primary btn-lg w-min-100 fw-bold" @click="marketingBuy">{{ __('admin/marketing.btn_buy') }}</button>
            @endif
          @else
          <div class="alert alert-warning mb-0" role="alert">
            @php
              $version_name_format_max = max(explode(', ', str_replace('v', '', $data['version_name_format'])));
            @endphp
            @if (config('beike.version') > $version_name_format_max)
            {!! __('admin/marketing.version_compatible_p_text') !!}
            @else
            {!! __('admin/marketing.version_compatible_text') !!}
            @endif
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

  <el-dialog title="{{ __('admin/marketing.bind_bk_token') }}" :close-on-click-modal="false"
    :visible.sync="setTokenDialog.show" width="520px" @close="dialogOnClose">

    <div class="login-wrap mb-4 px-2" style="margin-top: -20px">
      <ul class="nav nav-tabs nav-bordered mb-3" role="tablist">
        <li class="nav-item flex-1 text-center" role="presentation">
          <button class="nav-link active w-100" data-bs-toggle="tab" data-bs-target="#tab-login" type="button" >{{ __('shop/login.login') }}</button>
        </li>
        <li class="nav-item flex-1 text-center" role="presentation">
          <button class="nav-link w-100" data-bs-toggle="tab" data-bs-target="#tab-register" type="button">{{ __('shop/login.register') }}</button>
        </li>
      </ul>

      <div class="tab-content">
        <div class="tab-pane fade show active" id="tab-login">
          <div class="alert alert-info" role="alert">
            <i class="bi bi-question-circle"></i> {!! __('admin/marketing.bk_login_hint') !!}
          </div>

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

        <div class="tab-pane fade" id="tab-register">
          <div class="alert alert-info" role="alert">
            <i class="bi bi-question-circle"></i> {!! __('admin/marketing.bk_register_hint') !!}
          </div>

          <el-form ref="registerForm" :model="registerForm" :rules="registeRules">
            <el-form-item label="{{ __('admin/customer.user_name') }}" prop="name">
              <el-input @keyup.enter.native="checkedBtnLogin('registerForm')" v-model="registerForm.name"
                placeholder="{{ __('admin/customer.user_name') }}"></el-input>
            </el-form-item>

            <el-form-item label="{{ __('address.phone') }}" prop="telephone">
              <el-input @keyup.enter.native="checkedBtnLogin('registerForm')" class="v-input-calling-code" v-model="registerForm.telephone"
                placeholder="{{ __('address.phone') }}">
                <el-dropdown slot="prepend" placement="bottom-start" trigger="click" class="v-dropdown-calling-code">
                  <span class="el-dropdown-link">
                    <img :src="'https://beikeshop.com/image/flag/' + callingCode.icon + '.png'" class="img-fluid" style="width: 16px">
                    @{{ callingCode.region }} + @{{ callingCode.code }} <i class="el-icon-arrow-down el-icon--right"></i>
                  </span>
                  <el-dropdown-menu slot="dropdown">
                    <div class="calling-code-dropdown el">
                      <div class="position-relative">
                        <i class="bi bi-search position-absolute top-0 start-0 ms-2" style="margin-top: 6px">&#xe65b;</i>
                        <input type="text" placeholder="{{ __('admin/marketing.code_keyword') }}" class="form-control ps-4" v-model="codeKeyword" @input="searchCode">
                      </div>
                      <hr>
                      <el-dropdown-item>
                        <ul class="code-list ps-0">
                          <li v-for="item, index in callingCodes" :key="index" @click="checkedCode(index)">
                            <span>
                              <img :src="'https://beikeshop.com/image/flag/' + item.icon + '.png'" class="img-fluid">
                              @{{ item.region }}
                            </span>
                            <span>@{{ item.code }}</span>
                          </li>
                        </ul>
                      </el-dropdown-item>
                    </div>
                  </el-dropdown-menu>
                </el-dropdown>
              </el-input>
            </el-form-item>

            <div class="drag-verify-wrap mt-2">
              <v-drag-verify
                ref="dragVerify"
                :is-passing.sync="isPassing"
                text="{{ __('admin/marketing.is_passing') }}"
                success-text="{{ __('admin/marketing.is_passing_succee') }}"
                @passcallback="passcallback"
                handler-icon="bi bi-chevron-double-right"
                success-icon="bi bi-check-circle"
              >
              </v-drag-verify>
            </div>

            <el-form-item label="{{ __('admin/marketing.verification_code') }}" prop="sms_code">
              <el-input v-model="registerForm.sms_code" :maxlength="6" class="send-code-wrap" placeholder="{{ __('admin/marketing.input_send_placeholder') }}">
                <el-button slot="append" @click="getSmsCode" :disabled="retryCodeTime || !isPassing ? true : false">
                  <span v-if="!isSendSms">{{ __('admin/marketing.btn_send_code') }}</span>
                  <span v-else>{{ __('admin/marketing.btn_send_code_retry') }}</span>
                  <span v-if="retryCodeTime">(@{{ retryCodeTime }})</span>
                </el-button>
              </el-input>
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

  <el-dialog title="{{ $data['name'] }}--{{ __('admin/marketing.btn_buy_service') }}" :close-on-click-modal="false"
    :visible.sync="serviceDialog.show" width="520px" @close="serviceDialogOnClose">
    <div class="service-wx-pop" v-if="service_wechatpay_price">
      <div class="text-center">
        <span class="fw-bold fs-2">{{ __('admin/marketing.wxpay') }}</span>
      </div>
      <div class="text-center py-3 fs-5">{{ __('admin/marketing.text_pay') }}：<span class="fs-3 text-danger fw-bold">@{{
          service_wechatpay_price }}</span></div>
      <div class="d-flex justify-content-center align-items-center" id="service-info"></div>
    </div>
    <div id="service-content" v-else>
      <el-radio-group v-model="serviceDialog.id" size="small" class="radio-group row d-flex">
        <div class="col-6 mb-3" v-for="item,index in serviceDialog.plugin_services">
          <el-radio class="w-100 d-flex justify-content-left align-items-center py-4 ps-2"  :label="item.id" border>
            <span style="font-size: .85rem">@{{ item.months }}{{ __('admin/marketing.munths') }} / @{{ item.price_format }}</span>
          </el-radio>
        </div>
      </el-radio-group>
      <div class="d-flex justify-content-center">
        <button class="btn btn-primary btn-lg w-min-100 px-5 fw-bold" @click="marketingBuyService">{{
          __('admin/marketing.btn_buy') }}</button>
      </div>
    </div>
  </el-dialog>
</div>

<div class="card p-4 mb-4">
  <div class="text-danger">
    <div class="d-flex">
      <div class="me-2">{{ __('admin/marketing.attention_show_1') }}</div>
      <div>
        {{ __('admin/marketing.attention_show_2') }}
        <br>
        {{ __('admin/marketing.attention_show_3') }}
      </div>
    </div>
  </div>
</div>

<div class="card h-min-200 p-4">
  <ul class="nav nav-tabs nav-bordered mb-3" role="tablist">
    <li class="nav-item" role="presentation">
      <a class="nav-link active" data-bs-toggle="tab" href="#tab-description">{{ __('admin/marketing.download_description') }}</a>
    </li>
    {{-- @if (!$data['is_subscribe'])
    <li class="nav-item" role="presentation">
      <a class="nav-link" data-bs-toggle="tab" href="#tab-histories">{{ __('admin/marketing.service_buy_histories') }}</a>
    </li>
    @endif --}}
  </ul>

  <div class="tab-content">
    <div class="tab-pane fade show active" id="tab-description">
      @if ($data['description'])
      {!! $data['description'] !!}
      @endif
    </div>
    @if (!$data['is_subscribe'])
    <div class="tab-pane fade" id="tab-histories">
      @if ($plugin['service_buy_histories'] ?? 0)
        <div class="table-push">
          <table class="table">
            <thead>
              <tr>
                <th>ID</th>
                <th>{{ __('admin/marketing.month') }}</th>
                <th>{{ __('admin/marketing.amount') }}</th>
                <th>{{ __('admin/marketing.payment_method') }}</th>
                <th>{{ __('admin/marketing.create_date') }}</th>
                <th>{{ __('admin/marketing.over_date') }}</th>
              </tr>
            </thead>
            <tbody>
              @if (count($plugin['service_buy_histories']))
              @foreach ($plugin['service_buy_histories'] as $item)
                <tr>
                  <td>{{ $item['id'] }}</td>
                  <td>{{ $item['service_months'] }} {{ __('admin/marketing.munths') }}</td>
                  <td>{{ $item['amount_format'] }}</td>
                  <td>{{ $item['payment_code'] }}</td>
                  <td>{{ $item['created_at_format'] }}</td>
                  <td>{{ $item['service_date_to_format'] }}</td>
                </tr>
              @endforeach
              @else
                <tr>
                  <td colspan="9" class="border-0">
                    <x-admin-no-data />
                  </td>
                </tr>
              @endif
            </tbody>
          </table>
        </div>
      @else
        <x-admin-no-data />
      @endif
    </div>
    @endif
  </div>
</div>
@endsection

@include('admin::pages.marketing.drag-verify')
@push('footer')
<script>
  let app = new Vue({
    el: '#app',

    data: {
      payCode: 'wechatpay',
      service_wechatpay_price: '',
      service_id: '',
      wechatpay_price: '',
      retryCodeTime: 0,
      isSendSms: false,
      isPassing: false,
      isSwalOpen: false,
      setTokenDialog: {
        show: false,
        token: @json(system_setting('base.developer_token') ?? ''),
      },

      serviceDialog: {
        show: false,
        id: '',
        plugin_services: @json($data['plugin_services'] ?? []),
      },

      loginForm: {
        email: '',
        password: '',
      },

      registerForm: {
        email: '',
        name: '',
        telephone: '',
        sms_code: '',
        calling_code: document.documentElement.lang === 'zh_cn' ? '86' : '1',
        qq: '',
        password: '',
      },

      callingCodes: @json($plugin['calling_codes'] ?? []),
      source: {
        callingCodes: @json($plugin['calling_codes'] ?? []),
      },

      codeKeyword: '',

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
        sms_code: [
          {required: true, message: '{{ __('common.error_required', ['name' => __('admin/marketing.verification_code')]) }}', trigger: 'change'},
        ],
      },
    },

    computed: {
      callingCode() {
        return this.source.callingCodes.find(item => item.code === this.registerForm.calling_code) || 'zh_cn';
      },

      payment_methods() {
        const payment_methods = @json($plugin['payment_methods']);
        return payment_methods.split(',');
      },
    },

    mounted() {
      this.source.callingCodes.forEach(item => {
        item.region_code = item.region + '' + item.code;
      });

      if ($(window).width() > 992) {
        const marketingRightHeight = $('.marketing-right').height() < 400 ? 400 : $('.marketing-right').height();
        $('.plugin-icon-wrap').css({
          'height': marketingRightHeight + 'px',
          'width': marketingRightHeight + 'px',
          'flex': '0 0 ' + marketingRightHeight + 'px',
        });
      }

      this.checkDomain()
    },

    methods: {
      searchCode(e) {
        this.callingCodes = this.source.callingCodes.filter(item => item.region_code.toLowerCase().includes(this.codeKeyword.toLowerCase()));
      },

      // 根据 token 获取domain，然后判断返回的domain是否与当前域名一致
      checkDomain() {
        if (!this.setTokenDialog.token) {
          return;
        }

        $http.post('{{ admin_route('marketing.check_domain') }}', {token: this.setTokenDialog.token, location_host: config.app_url}, {hload: true}).then((res) => {
          if (res.status == 'success') {
            if (res.message == 'fail') {
              layer.alert(res.data, {icon: 2, area: ['400px'], btn: ['{{ __('common.confirm') }}'], title: '{{__("common.text_hint")}}'});
            }
          }
        })
      },

      checkedCode(index) {
        this.registerForm.calling_code = this.callingCodes[index].code;
      },

      toBkTicketUrl() {
        let code = "{{ $data['code'] }}"
        return `${config.api_url}/account/plugin_tickets/create?domain=${config.app_url}&plugin=${code}`
      },

      checkedBtnLogin(form) {
        let _data = this.loginForm, url = `${config.api_url}/api/login?domain=${config.app_url}&v=1&locale={{ (admin_locale() == 'zh_cn' ? 'zh_cn' : 'en') }}`

        if (form == 'registerForm') {
          _data = this.registerForm, url = `${config.api_url}/api/register?domain=${config.app_url}&v=1&locale={{ (admin_locale() == 'zh_cn' ? 'zh_cn' : 'en') }}`
        }

        this.$refs['loginForm'].clearValidate();
        this.$refs['registerForm'].clearValidate();

        this.$refs[form].validate((valid) => {
          if (!valid) {
            layer.msg('{{ __('shop/login.check_form') }}', () => {})
            return;
          }

          $http.post(url, _data).then((res) => {
            if (res.status == 'fail') {
              layer.msg(res.message, ()=>{});
              return;
            }

            layer.msg(res.message);

            this.setTokenDialog.token = res.data.token;

            $http.post('{{ admin_route('settings.store_token') }}', {developer_token: this.setTokenDialog.token}).then((res) => {
              this.setTokenDialog.show = false;
              window.location.reload();
            })
          })
        });
      },

      serviceDialogOnClose() {
        window.clearInterval(this.timer)
        this.serviceDialog.id = ''
        this.service_wechatpay_price = ''
        $('#service-info').html('')
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

        $http.post('{{ admin_route('marketing.download', ['code' => $data['code']]) }}', null, {hmsg:true}).then((res) => {
          $('.download-help').removeClass('d-none').find('span').text(res.message);
        }).catch((err) => {
          if (err.response.data.message == 'plugin_pending') {
            layer.alert('{{__('admin/marketing.pluginstatus_pending')}}', {btn: ['{{ __('common.confirm') }}'], title: '{{__("common.text_hint")}}'});
          } else if (err.response.data.message == 'Not a zip archive') {
            layer.alert('{{ __('admin/marketing.not_zip_archive') }}', {icon: 2, area: ['400px'], btn: ['{{ __('common.confirm') }}'], title: '{{__("common.text_hint")}}'});
          } else {
            layer.msg(err.response.data.message || err.message,{time: 3000}, ()=>{});
          }
        })
      },

      openService() {
        if (!this.setTokenDialog.token) {
          return this.setTokenDialog.show = true;
        }

        this.serviceDialog.show = true
      },

      marketingBuyService() {
        if (!this.setTokenDialog.token) {
          return this.setTokenDialog.show = true;
        }

        if (!this.serviceDialog.id) {
          layer.msg('{{ __('admin/marketing.no_choose') }}')
          return
        }

        $http.post(`marketing/${this.serviceDialog.id}/buy_service`, {
          payment_code: this.payCode, return_url: '{{ admin_route('marketing.show', ['code' => $data['code']]) }}'}).then((res) => {

          if (res.status == "fail") {
            layer.msg(res.message, () => {})
            return;
          }

          this.serviceDialog.show = false

          if (res.data.payment_code == 'stripe' || res.data.payment_code == 'lianlian') {
            window.open(`${res.data.pay_url}`, '_blank');
            this.paySuccessAlert();
          }

          if (res.data.payment_code == 'wechatpay') {
            this.service_wechatpay_price = res.data.amount
            this.service_id = res.data.id
            this.getQrcode(res.data.pay_url,'service');
          }

          if (res.data.payment_code == 'alipay') {
            window.open(res.data.pay_url, '_blank');
            this.paySuccessAlert();
          }
        })
      },

      marketingBuy() {
        if (!this.setTokenDialog.token) {
          return this.setTokenDialog.show = true;
        }

        $http.post('{{ admin_route('marketing.buy', ['code' => $data['code']]) }}', {
          payment_code: this.payCode, return_url: '{{ admin_route('marketing.show', ['code' => $data['code']]) }}'}).then((res) => {
          if (res.status == "fail") {
            layer.msg(res.message, () => {})
            return;
          }

          if (this.payCode == 'stripe' || this.payCode == 'lianlian') {
            window.open(`${res.data.pay_url}`, '_blank');
            this.paySuccessAlert();
          }

          if (res.data.payment_code == 'wechatpay') {
            this.wechatpay_price = res.data.price_format
            this.getQrcode(res.data.pay_url,'plugin');
          }

          if (res.data.payment_code == 'alipay') {
            window.open(res.data.pay_url, '_blank');
            this.paySuccessAlert();
          }
        })
      },

      getSmsCode() {
        var phone = this.registerForm.telephone;
        var callingCode = this.registerForm.calling_code;

        if (!this.isPassing) {
          layer.msg('{{ __('admin/marketing.error_is_passing') }}', ()=>{});
          return;
        }

        $http.post(`${config.api_url}/api/send_code?locale={{ (admin_locale() == 'zh_cn' ? 'zh_cn' : 'en') }}`, {phone: phone, calling_code: callingCode}).then((res) => {
          layer.msg(res.message);
          this.isSendSms = true;
          this.retryCodeTime = 60;

          var timer = setInterval(() => {
            this.retryCodeTime--;
            if (this.retryCodeTime <= 0) {
              clearInterval(timer);
              this.retryCodeTime = 0;
            }
          }, 1000);
        })
      },

      getQrcode(url,type) {
        const self = this;
        if (type == 'plugin') {
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
        }

        if (type == 'service') {
          this.serviceDialog.show = true
          this.$nextTick(() => {
            new QRCode('service-info', {
              text: url,
              width: 270,
              height: 270,
              correctLevel : QRCode.CorrectLevel.M
            });
          })

          setTimeout(() => {
            self.chekServiceOrderStatus();
            self.timer = window.setInterval(() => {
              setTimeout(self.chekServiceOrderStatus(), 0);
            }, 1000)
          }, 100)
        }
      },

      chekOrderStatus() {
        $http.get('{{ admin_route('marketing.show', ['code' => $data['code']]) }}', null, {hload: true}).then((res) => {
          if (res.plugin.data.downloadable) {
            window.clearInterval(this.timer)
            if (!this.isSwalOpen) {
              this.isSwalOpen = true
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
          }
        })
      },

      paySuccessAlert() {
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
      },

      chekServiceOrderStatus() {
        let that = this
        $http.get(`marketing/service_orders/${this.service_id}`, null, {hload: true}).then((res) => {
          if (res.data.status == 'fail') {
            window.clearInterval(this.timer)
            layer.msg(res.data.message, () => {})
          }

          if (res.data.data.status == 'paid') {
            window.clearInterval(this.timer)
            that.serviceDialog.id = ''
            that.service_wechatpay_price = ''
            $('#service-info').html('')
            that.serviceDialog.show = false

            if (!this.isSwalOpen) {
              this.isSwalOpen = true
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
      },

      passcallback() {
        this.isPassing = true;
      },
    },

    destroyed() {
      window.clearInterval(this.timer)
    },
  })
</script>
<style>
  #tab-register .el-form-item {
    margin-bottom: 7px;
  }

  #tab-register .el-form-item__label {
    line-height: 30px;
  }
</style>
@endpush

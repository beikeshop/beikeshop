@extends('layout.master')

@section('body-class', 'page-login')

@push('header')
  <script src="{{ asset('vendor/vue/2.6.14/vue.js') }}"></script>
  <script src="{{ asset('vendor/element-ui/2.15.6/js.js') }}"></script>
  <link rel="stylesheet" href="{{ asset('vendor/element-ui/2.15.6/css.css') }}">
@endpush


@section('content')
  <div class="{{ request('iframe') ? 'container-fluid form-iframe mt-5' : 'container' }}" id="page-login" v-cloak>
    @if (!request('iframe'))
      <x-shop-breadcrumb type="static" value="login.index" />
      <div class="hero-content pb-5 text-center"><h1 class="hero-heading">{{ __('shop/login.index') }}</h1></div>
    @endif

    <div class="login-wrap">
      <div class="card">
        <el-form ref="loginForm" :model="loginForm" :rules="loginRules" :inline-message="true">
          <div class="login-item-header card-header">
            <h6 class="text-uppercase mb-0">{{ __('shop/login.login') }}</h6>
          </div>
          <div class="card-body px-md-2">
            <el-form-item label="{{ __('shop/login.email') }}" prop="email">
              <el-input @keyup.enter.native="checkedBtnLogin('loginForm')" v-model="loginForm.email" placeholder="{{ __('shop/login.email_address') }}"></el-input>
            </el-form-item>

            <el-form-item label="{{ __('shop/login.password') }}" prop="password">
              <el-input @keyup.enter.native="checkedBtnLogin('loginForm')" type="password" v-model="loginForm.password" placeholder="{{ __('shop/login.password') }}"></el-input>
            </el-form-item>

            <a class="text-muted forgotten-link" href="{{ shop_route('forgotten.index') }}"><i class="bi bi-question-circle"></i> {{ __('shop/login.forget_password') }}</a>

            <div class="mt-4 mb-3">
              <button type="button" @click="checkedBtnLogin('loginForm')" class="btn btn-dark btn-lg w-100 fw-bold"><i class="bi bi-box-arrow-in-right"></i> {{ __('shop/login.login') }}</button>
            </div>
          </div>
        </el-form>

        @if($social_buttons)
          <div class="social-wrap px-2">
            <div class="title mb-4"><span>第三方登录</span></div>
            @foreach($social_buttons as $button)
              {!! $button !!}
            @endforeach
          </div>
        @endif
      </div>

      <div class="d-flex">
        <div class="vr bg-secondary"></div>
      </div>
      <div class="card">
        <div class="login-item-header card-header">
          <h6 class="text-uppercase mb-0">{{ __('shop/login.new') }}</h6>
        </div>
        <div class="card-body px-md-2">
            <el-form ref="registerForm" :model="registerForm" :rules="registeRules">
              <el-form-item label="{{ __('shop/login.email') }}" prop="email">
                <el-input @keyup.enter.native="checkedBtnLogin('registerForm')" v-model="registerForm.email" placeholder="{{ __('shop/login.email_address') }}"></el-input>
              </el-form-item>

              <el-form-item label="{{ __('shop/login.password') }}" prop="password">
                <el-input @keyup.enter.native="checkedBtnLogin('registerForm')" type="password" v-model="registerForm.password" placeholder="{{ __('shop/login.password') }}"></el-input>
              </el-form-item>

              <el-form-item label="{{ __('shop/login.confirm_password') }}" prop="password_confirmation">
                <el-input @keyup.enter.native="checkedBtnLogin('registerForm')" type="password" v-model="registerForm.password_confirmation" placeholder="{{ __('shop/login.confirm_password') }}"></el-input>
              </el-form-item>


              <div class="mt-5 mb-3">
                <button type="button" @click="checkedBtnLogin('registerForm')" class="btn btn-dark btn-lg w-100 fw-bold"><i class="bi bi-person"></i> {{ __('shop/login.register') }}</button>
              </div>
            </el-form>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('add-scripts')
  <script>
    var validatePass = (rule, value, callback) => {
      if (value === '') {
        callback(new Error('{{ __('shop/login.enter_password') }}'));
      } else {
        if (value !== '') {
          app.$refs.registerForm.validateField('password_confirmation');
        }
        callback();
      }
    };

    var validatePass2 = (rule, value, callback) => {
      if (value === '') {
        callback(new Error('{{ __('shop/login.please_confirm') }}'));
      } else if (value !== app.registerForm.password) {
        callback(new Error('{{ __('shop/login.password_err') }}'));
      } else {
        callback();
      }
    };

    let app = new Vue({
      el: '#page-login',

      data: {
        loginForm: {
          email: '',
          password: '',
        },

        registerForm: {
          email: '',
          password: '',
          password_confirmation: '',
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
          email: [
            {required: true, message: '{{ __('shop/login.enter_email') }}', trigger: 'change'},
            {type: 'email', message: '{{ __('shop/login.email_err') }}', trigger: 'change'},
          ],
          password: [
            {required: true, trigger: 'change'}
          ],
          password_confirmation: [
            {required: true, validator: validatePass2, trigger: 'change'}
          ]
        }
      },

      beforeMount () {
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

            $http.post(url, _data).then((res) => {
              layer.msg(res.message)
              @if (!request('iframe'))
                location = "{{ shop_route('account.index') }}"
              @else
                var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
                setTimeout(() => {
                  parent.layer.close(index); //再执行关闭
                  parent.window.location.reload()
                }, 400);
              @endif
            })
          });
        }
      }
    })
  </script>
@endpush

@extends('layout.master')

@section('body-class', 'page-login')

@push('header')
  <script src="{{ asset('vendor/vue/2.6.14/vue.js') }}"></script>
  <script src="{{ asset('vendor/element-ui/2.15.6/js.js') }}"></script>
  <link rel="stylesheet" href="{{ asset('vendor/element-ui/2.15.6/css.css') }}">
@endpush


@section('content')
  <div class="{{ request('iframe') ? 'container-fluid mt-5' : 'container' }}" id="page-login" v-cloak>
    @if (!request('iframe'))
      <x-shop-breadcrumb type="static" value="login.index" />
      <div class="hero-content pb-5 text-center"><h1 class="hero-heading">{{ __('shop/login.index') }}</h1></div>
    @endif

    <div class="justify-content-center row {{ !request('iframe') ? 'mb-5' : '' }}">
      <div class="col-lg-{{ request('iframe') ? '6' : '5' }} col-md-6 col-sm-12">
        <div class="card">
          <el-form ref="loginForm" :model="loginForm" :rules="loginRules">
            <div class="login-item-header card-header">
              <h6 class="text-uppercase mb-0">{{ __('shop/login.login') }}</h6>
            </div>
            <div class="card-body">
              <p class="lead">{{ __('shop/login.already') }}</p>
              {{-- <p class="text-muted">Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis
                egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit
                amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo.</p> --}}
              <hr>

              <el-form-item label="{{ __('shop/login.email') }}" prop="email">
                <el-input v-model="loginForm.email" placeholder="{{ __('shop/login.email_address') }}"></el-input>
              </el-form-item>

              <el-form-item label="{{ __('shop/login.password') }}" prop="password" class="mb-4">
                <el-input type="password" v-model="loginForm.password" placeholder="{{ __('shop/login.password') }}"></el-input>
              </el-form-item>

              <a class="text-muted" href="{{ shop_route('forgotten.index') }}"><i class="bi bi-question-circle"></i> {{ __('shop/login.forget_password') }}</a>

              <div class="mt-4 mb-3">
                <button type="button" @click="checkedBtnLogin('loginForm')" class="btn btn-outline-dark"><i class="bi bi-box-arrow-in-right"></i> {{ __('shop/login.login') }}</button>
              </div>
            </div>
          </el-form>
        </div>
      </div>
      <div class="col-lg-{{ request('iframe') ? '6' : '5' }} col-md-6 col-sm-12">
        <div class="card">
          <div class="login-item-header card-header">
            <h6 class="text-uppercase mb-0">{{ __('shop/login.new') }}</h6>
          </div>
          <div class="card-body">
            <p class="lead">{{ __('shop/login.not_already') }}</p>
            {{-- <p class="text-muted">With registration with us new world of fashion, fantastic discounts and much more opens to
              you! The whole process will not take you more than a minute!</p>
            <p class="text-muted">If you have any questions, please feel free to <a href="/contact">contact us</a>, our
              customer service center is working for you 24/7.</p> --}}
              <hr>

              <el-form ref="registerForm" :model="registerForm" :rules="registeRules">
                <el-form-item label="{{ __('shop/login.email') }}" prop="email">
                  <el-input v-model="registerForm.email" placeholder="{{ __('shop/login.email_address') }}"></el-input>
                </el-form-item>

                <el-form-item label="{{ __('shop/login.password') }}" prop="password">
                  <el-input type="password" v-model="registerForm.password" placeholder="{{ __('shop/login.password') }}"></el-input>
                </el-form-item>

                <el-form-item label="{{ __('shop/login.confirm_password') }}" prop="password_confirmation">
                  <el-input type="password" v-model="registerForm.password_confirmation" placeholder="{{ __('shop/login.confirm_password') }}"></el-input>
                </el-form-item>


                <div class="mt-5 mb-3">
                  <button type="button" @click="checkedBtnLogin('registerForm')" class="btn btn-outline-dark"><i class="bi bi-person"></i> {{ __('shop/login.register') }}</button>
                </div>
              </el-form>
          </div>
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
            {required: true, validator: validatePass, trigger: 'change'}
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

          this.$refs['loginForm'].resetFields();
          this.$refs['registerForm'].resetFields();

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
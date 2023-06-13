<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
  <base href="{{ $admin_base_url }}">
  <meta name="asset" content="{{ asset('/') }}">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link href="{{ mix('/build/beike/admin/css/bootstrap.css') }}" rel="stylesheet">
  <script src="{{ asset('vendor/jquery/jquery-3.6.0.min.js') }}"></script>
  <script src="{{ asset('vendor/layer/3.5.1/layer.js') }}"></script>
  <script src="{{ asset('vendor/vue/2.7/vue' . (!config('app.debug') ? '.min' : '') . '.js') }}"></script>
  <script src="{{ asset('vendor/element-ui/index.js') }}"></script>
  <link rel="stylesheet" href="{{ asset('vendor/element-ui/index.css') }}">
  {{-- <link href="{{ mix('build/css/admin/login.css') }}" rel="stylesheet"> --}}
  <script src="{{ mix('build/beike/admin/js/app.js') }}"></script>
  <link href="{{ mix('build/beike/admin/css/app.css') }}" rel="stylesheet">
  <title>forgotten</title>
</head>
<body class="page-login">
  <div class="d-flex align-items-center vh-100 pt-2 pt-sm-5 pb-4 pb-sm-5" id="page-forgotten">
    <div class="container">
      <div class="card">
        <div class="w-480">
          <div class="card-header mt-3 mb-3">
            <h4 class="fw-bold">{{ __('shop/forgotten.follow_prompt') }}</h4>
            <div class="text-muted fw-normal" v-if="!isCode">{{ __('shop/forgotten.email_forCode') }}</div>
            <div class="text-muted fw-normal" v-else>{{ __('shop/forgotten.enter_password') }}</div>
          </div>

          <div class="card-body">
            <el-form ref="form" :model="form" :rules="rules" label-position="top">
              <div class="card-body p-0">
{{--                 <h4 class="fw-bold">{{ __('shop/forgotten.follow_prompt') }}</h4>
                <p class="text-muted" v-if="!isCode">{{ __('shop/forgotten.email_forCode') }}</p>
                <p class="text-muted" v-else>{{ __('shop/forgotten.enter_password') }}</p> --}}

                <el-form-item label="{{ __('shop/forgotten.email') }}" prop="email" v-if="!isCode">
                  <el-input v-model="form.email" placeholder="{{ __('shop/forgotten.email_address') }}"></el-input>
                </el-form-item>

                <el-form-item label="{{ __('shop/forgotten.verification_code') }}" prop="code" class="mb-3" v-if="isCode">
                  <el-input  v-model="form.code" placeholder="{{ __('shop/forgotten.verification_code') }}"></el-input>
                </el-form-item>

                <el-form-item label="{{ __('shop/forgotten.password') }}" prop="password" class="mb-3" v-if="isCode">
                  <el-input type="password" v-model="form.password" placeholder="{{ __('shop/forgotten.password') }}"></el-input>
                </el-form-item>

                <el-form-item label="{{ __('shop/forgotten.confirm_password') }}" prop="password_confirmation" v-if="isCode">
                  <el-input type="password" v-model="form.password_confirmation" placeholder="{{ __('shop/forgotten.confirm_password') }}"></el-input>
                </el-form-item>

                <div class="mt-5 mb-3 d-flex justify-content-between">
                  <button type="button" @click="submitForm('form')" class="btn w-50 btn-dark">
                    {{-- @{{ !isCode ? '发送验证码'  :  '提交'  }} --}}
                    <template v-if="!isCode">{{ __('shop/forgotten.send_code') }}</template>
                    <template v-else>{{ __('common.submit') }}</template>
                  </button>
                </div>
                <a href="javascript:void(0)" v-if="isCode" @click="isCode = false" class="text-muted">{{ __('shop/forgotten.to_back') }}</a>
              </div>
            </el-form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script>
    var validatePass = (rule, value, callback) => {
      if (value === '') {
        callback(new Error('{{ __('shop/forgotten.enter_password') }}'));
      } else {
        if (value !== '') {
          app.$refs.form.validateField('password_confirmation');
        }
        callback();
      }
    };

    var validatePass2 = (rule, value, callback) => {
      if (value === '') {
        callback(new Error('{{ __('shop/forgotten.please_confirm') }}'));
      } else if (value !== app.form.password) {
        callback(new Error('{{ __('shop/forgotten.password_err') }}'));
      } else {
        callback();
      }
    };

    let app = new Vue({
      el: '#page-forgotten',

      data: {
        form: {
          email: bk.getQueryString('email', ''),
          code: bk.getQueryString('code', ''),
          password: '',
          password_confirmation: '',
        },

        isCode: !!bk.getQueryString('code'),

        rules: {
          email: [
            {required: true, message: '{{ __('shop/forgotten.enter_email') }}', trigger: 'blur'},
            {type: 'email', message: '{{ __('shop/forgotten.email_err') }}', trigger: 'blur'},
          ],
          code: [
            {required: true, message: '{{ __('shop/forgotten.enter_code') }}', trigger: 'blur'}
          ],
          password: [
            {required: true, validator: validatePass, trigger: 'blur'}
          ],
          password_confirmation: [
            {required: true, validator: validatePass2, trigger: 'blur'}
          ]
        },
      },

      mounted () {
      },

      methods: {
        submitForm(form) {
          let _data = this.form, url = 'forgotten/password'

          if (!this.isCode) {
            url = 'forgotten/send_code'
          }

          this.$refs[form].validate((valid) => {
            if (!valid) {
              return;
            }

            $http.post(url, this.form).then((res) => {
              if (this.isCode) {
                layer.msg(res.message)
              } else {
                this.$alert(res.message, '{{ __('common.text_hint') }}');
              }

              this.$refs[form].clearValidate();

              if (this.isCode) {
                location = "{{ admin_route('login.show') }}"
              }
              this.isCode = true
            })
          });
        }
      }
    })
  </script>
</body>
</html>





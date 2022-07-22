@extends('layout.master')

@section('body-class', 'page-login')

@push('header')
  <script src="{{ asset('vendor/vue/2.6.14/vue.js') }}"></script>
  <script src="{{ asset('vendor/element-ui/2.15.6/js.js') }}"></script>
  <link rel="stylesheet" href="{{ asset('vendor/element-ui/2.15.6/css.css') }}">
@endpush


@section('content')
  <div class="container" id="page-login" v-cloak>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb justify-content-center">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Library</li>
      </ol>
    </nav>
    <div class="hero-content pb-5 text-center"><h1 class="hero-heading">用户登录与注册</h1></div>
    <div class="justify-content-center row mb-5">
      <div class="col-lg-5">
        <div class="card">
          <el-form ref="loginForm" :model="loginForm" :rules="loginRules">
            <div class="login-item-header card-header">
              <h6 class="text-uppercase mb-0">Login</h6>
            </div>
            <div class="card-body">
              <p class="lead">Already our customer?</p>
              <p class="text-muted">Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis
                egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit
                amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo.</p>
              <hr>

              <el-form-item label="邮箱" prop="email">
                <el-input v-model="loginForm.email" placeholder="邮箱地址"></el-input>
              </el-form-item>

              <el-form-item label="密码" prop="password" class="mb-2">
                <el-input type="password" v-model="loginForm.password" placeholder="密码"></el-input>
              </el-form-item>

              <a class="text-muted" href="{{ shop_route('forgotten.index') }}"><i class="bi bi-question-circle"></i> 忘记密码</a>

              <div class="mt-4 mb-3">
                <button type="button" @click="checkedBtnLogin('loginForm')" class="btn btn-outline-dark"><i class="bi bi-box-arrow-in-right"></i> 登录</button>
              </div>
            </div>
          </el-form>
        </div>
      </div>
      <div class="col-lg-5">
        <div class="card">
          <div class="login-item-header card-header">
            <h6 class="text-uppercase mb-0">New account</h6>
          </div>
          <div class="card-body">
            <p class="lead">Not our registered customer yet?</p>
            <p class="text-muted">With registration with us new world of fashion, fantastic discounts and much more opens to
              you! The whole process will not take you more than a minute!</p>
            <p class="text-muted">If you have any questions, please feel free to <a href="/contact">contact us</a>, our
              customer service center is working for you 24/7.</p>
              <hr>

              <el-form ref="registerForm" :model="registerForm" :rules="registeRules">
                <el-form-item label="邮箱" prop="email">
                  <el-input v-model="registerForm.email" placeholder="邮箱地址"></el-input>
                </el-form-item>

                <el-form-item label="密码" prop="password">
                  <el-input type="password" v-model="registerForm.password" placeholder="密码"></el-input>
                </el-form-item>

                <el-form-item label="确认密码" prop="password_confirmation">
                  <el-input type="password" v-model="registerForm.password_confirmation" placeholder="确认密码"></el-input>
                </el-form-item>


                <div class="mt-5 mb-3">
                  <button type="button" @click="checkedBtnLogin('registerForm')" class="btn btn-outline-dark"><i class="bi bi-person"></i> 注册</button>
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
        callback(new Error('请输入密码'));
      } else {
        if (value !== '') {
          app.$refs.registerForm.validateField('password_confirmation');
        }
        callback();
      }
    };

    var validatePass2 = (rule, value, callback) => {
      if (value === '') {
        callback(new Error('请输入确认密码'));
      } else if (value !== app.registerForm.password) {
        callback(new Error('两次输入密码不一致!'));
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
            {required: true, message: '请输入邮箱', trigger: 'blur'},
            {type: 'email', message: '请输入正确邮箱地址', trigger: 'blur'},
          ],
          password: [
            {required: true, message: '请输入密码', trigger: 'blur'}
          ]
        },

        registeRules: {
          email: [
            {required: true, message: '请输入邮箱', trigger: 'blur'},
            {type: 'email', message: '请输入正确邮箱地址', trigger: 'blur'},
          ],
          password: [
            {required: true, validator: validatePass, trigger: 'blur'}
          ],
          password_confirmation: [
            {required: true, validator: validatePass2, trigger: 'blur'}
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

          this.$refs[form].validate((valid) => {
            if (!valid) {
              layer.msg('请检查表单是否填写正确', () => {})
              return;
            }

            $http.post(url, _data).then((res) => {
              this.$message.success(res.message);
              location = "{{ shop_route('account.index') }}"
            })
          });
        }
      }
    })
  </script>
@endpush
@extends('layout.master')

@section('body-class', 'page-forgotten')

@push('header')
  <script src="{{ asset('vendor/vue/2.6.14/vue.js') }}"></script>
  <script src="{{ asset('vendor/element-ui/2.15.6/js.js') }}"></script>
  <link rel="stylesheet" href="{{ asset('vendor/element-ui/2.15.6/css.css') }}">
@endpush


@section('content')
  <div class="container" id="page-forgotten" v-cloak>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Library</li>
      </ol>
    </nav>
    {{-- <div class="hero-content pb-5 text-center"><h1 class="hero-heading">找回密码</h1></div> --}}
    <div class="row my-5 justify-content-md-center">
      <div class="col-lg-5 col-xxl-4">
        <div class="card">
          <el-form ref="form" :model="form" :rules="rules">
            <div class="card-body p-0">
              <h4 class="fw-bold">请根据提示找回您的密码</h4>
              <p class="text-muted" v-if="!isCode">请输入邮箱地址获取验证码</p>
              <p class="text-muted" v-else>请输入新密码</p>

              <el-form-item label="邮箱" prop="email" v-if="!isCode">
                <el-input v-model="form.email" placeholder="邮箱地址"></el-input>
              </el-form-item>

              <el-form-item label="验证码" prop="code" class="mb-3" v-if="isCode">
                <el-input  v-model="form.code" placeholder="密码"></el-input>
              </el-form-item>

              <el-form-item label="密码" prop="password" class="mb-3" v-if="isCode">
                <el-input type="password" v-model="form.password" placeholder="密码"></el-input>
              </el-form-item>

              <el-form-item label="确认密码" prop="password_confirmation" v-if="isCode">
                <el-input type="password" v-model="form.password_confirmation" placeholder="确认密码"></el-input>
              </el-form-item>

              <div class="mt-5 mb-3 d-flex justify-content-between">
                <button type="button" @click="submitForm('form')" class="btn w-50 btn-dark">@{{ !isCode ? '发送验证码' : '提交' }}</button>
              </div>
              <a href="javascript:void(0)" v-if="isCode" @click="isCode = false" class="text-muted">返回上一步</a>
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
        callback(new Error('请输入密码'));
      } else {
        if (value !== '') {
          app.$refs.form.validateField('password_confirmation');
        }
        callback();
      }
    };

    var validatePass2 = (rule, value, callback) => {
      if (value === '') {
        callback(new Error('请输入确认密码'));
      } else if (value !== app.form.password) {
        callback(new Error('两次输入密码不一致!'));
      } else {
        callback();
      }
    };

    let app = new Vue({
      el: '#page-forgotten',

      data: {
        form: {
          email: '',
          code: '',
          password: '',
          password_confirmation: '',
        },

        isCode: false,

        rules: {
          email: [
            {required: true, message: '请输入邮箱', trigger: 'blur'},
            {type: 'email', message: '请输入正确邮箱地址', trigger: 'blur'},
          ],
          code: [
            {required: true, message: '请输入验证码', trigger: 'blur'}
          ],
          password: [
            {required: true, validator: validatePass, trigger: 'blur'}
          ],
          password_confirmation: [
            {required: true, validator: validatePass2, trigger: 'blur'}
          ]
        },
      },

      beforeMount () {
      },

      methods: {
        submitForm(form) {
          let _data = this.form, url = 'forgotten/password'

          if (!this.isCode) {
            url = 'forgotten/send_code'
          }

          this.$refs[form].validate((valid) => {
            if (!valid) {
              // layer.msg('请检查表单是否填写正确', () => {})
              return;
            }

            $http.post(url, this.form).then((res) => {
              this.$message.success(res.message);
              this.$refs[form].clearValidate();

              if (this.isCode) {
                location = "{{ shop_route('login.index') }}"
              }
              this.isCode = true
            })
          });
        }
      }
    })
  </script>
@endpush
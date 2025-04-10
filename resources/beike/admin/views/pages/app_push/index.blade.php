@extends('admin::layouts.master')

@section('title', __('admin/app_push.page_title'))

@section('content')

@push('header')
<link rel="stylesheet" type="text/css" href="{{ asset('/build/beike/admin/css/design.css') }}">
@endpush

@section('page-title-after')
<a class="btn btn-outline-primary btn-sm" href="{{ beike_url() }}/solution/app" target="_blank">{{ __('admin/app_builder.to_buy') }}</a>
@endsection

<div class="card" id="app" v-cloak>
  <div class="card-body h-min-600">
    <div class="alert alert-info mb-2">
      {!! __('admin/app_push.push_tip_1') !!}<br>
    </div>

    <div class="mb-5 bg-light pt-3">
      <el-form :inline="true" :model="apiUrlForm" ref="apiUrlForm" label-width="100px">
        <el-form-item label="API URL" prop="unipush_api_url" class="mb-0">
          <el-input v-model="apiUrlForm.unipush_api_url" class="w-min-600" size="small" placeholder="API URL"></el-input>
        </el-form-item>
        <el-form-item class="mb-0">
          <el-button type="primary" plain size="small" @click="apiUrlFormSubmit('apiUrlForm')">{{ __('common.submit') }}</el-button>
        </el-form-item>
      </el-form>

      <div class="d-flex pb-4 mt-2">
        <div class="wp-100"></div>
        <el-checkbox v-model="apiUrlForm.order_auto_app_push" true-label="1" false-label="0">{{ __('admin/app_push.order_status_auto_push') }}</el-checkbox>
      </div>
    </div>

    <div class="mb-5">
      <div class="mb-3 fs-4">{{ __('admin/app_push.push_title') }}</div>
      <div class="w-max-600">
        <el-form ref="form" :rules="rules" :model="form" label-width="100px">
          <el-form-item label="{{ __('admin/app_push.title') }}" prop="title">
            <el-input class="mb-0" v-model="form.title" placeholder="{{ __('admin/app_push.title') }}"></el-input>
          </el-form-item>

          <el-form-item label="{{ __('admin/app_push.content') }}" prop="content">
            <el-input class="mb-0" v-model="form.content" placeholder="{{ __('admin/app_push.content') }}"></el-input>
          </el-form-item>

          <el-form-item label="{{ __('admin/app_push.link') }}" prop="value" class="link-wrap">
            {{-- <el-input class="mb-0" v-model="form.value" placeholder="参数"></el-input> --}}
            <link-selector v-model="form.link" :is-title="false" :hide-types="['static', 'brand', 'page_category']">
            </link-selector>
            <div class="help-text font-size-12 lh-base">{{ __('admin/app_push.link_tip') }}</div>
          </el-form-item>

          <el-form-item label="{{ __('admin/app_push.push_clientid') }}" prop="push_clientid">
            <el-input class="mb-0" v-model="form.push_clientid" placeholder="{{ __('admin/app_push.push_clientid') }}"></el-input>
            <div class="help-text font-size-12 lh-base">{{ __('admin/app_push.push_clientid_tip') }}</div>
          </el-form-item>

          <el-form-item>
            <el-button type="primary" @click="submit('form')">{{ __('admin/app_push.push_title') }}</el-button>
          </el-form-item>
        </el-form>
      </div>
    </div>
  </div>
</div>

@include('admin::pages.design.builder.component.link_selector')

<script>
  const url = "{{ admin_route('app_push.push') }}";

  var app = new Vue({
    el: '#app',

    data: {
      form: {
        id: null,
        title: '',
        content: '',
        link: {
          type: 'product',
          value:''
        },
        push_clientid: '',
      },

      apiUrlForm: {
        unipush_api_url: @json(system_setting('base.unipush_api_url') ?? ''),
        order_auto_app_push: @json(system_setting('base.order_auto_app_push')),
      },

      rules: {
        title: [{required: true, message: '{{ __('common.error_input_required') }}',trigger: 'blur'}, ],
        content: [{required: true, message: '{{ __('common.error_input_required') }}', trigger: 'blur'}, ],
      },
    },

    // mounted() {

    // },

    watch: {
      'apiUrlForm.order_auto_app_push': function (val) {
        $http.put('settings/values', {order_auto_app_push: val});
      }
    },

    methods: {
      submit(form) {
        this.$refs[form].validate((valid) => {
          if (!valid) {
            this.$message.error('{{ __('common.error_form') }}');
            return;
          }

          if (!this.apiUrlForm.unipush_api_url) {
            this.$message.error('{{ __('admin/app_push.api_url_err') }}');
            return;
          }

          $http.post(url, this.form).then((res) => {
            layer.msg(res.message);
          })
        });
      },

      apiUrlFormSubmit(form) {
        this.$refs[form].validate((valid) => {
          if (!valid) {
            this.$message.error('{{ __('common.error_form') }}');
            return;
          }

          $http.put('settings/values', {unipush_api_url: this.apiUrlForm.unipush_api_url}).then((res) => {
            layer.msg(res.message);
          })
        })
      }
    }
  })
</script>

<style>
  .link-wrap .el-form-item__content {
    line-height: initial;
    /* display: flex;
    align-items: center; */
  }

  .link-wrap .link-selector-wrap .selector-type .title {
    height: 40px;
    display: flex;
    border-color: #dcdfe6;
    border-radius: 4px;
    align-items: center;
  }

  .link-wrap .link-selector-wrap .selector-type .title:before {
    top: 14px;
  }
</style>
@endsection
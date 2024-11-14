@extends('admin::layouts.master')

@section('title', __('admin/design_builder.design_header_index'))

@push('header')
  <script src="{{ asset('vendor/vue/Sortable.min.js') }}"></script>
  <script src="{{ asset('vendor/vue/vuedraggable.js') }}"></script>
  <link rel="stylesheet" type="text/css" href="{{ asset('/build/beike/admin/css/design.css') }}">
@endpush

@section('page-title-right')
  <button type="button" class="btn w-min-100 btn-primary save-btn">{{ __('common.save') }}</button>
@endsection

@section('content')
  <div class="card" id="app" v-cloak>
    <div class="card-body h-min-600 position-relative">
      <el-form ref="form" label-width="120px" style="width: 800px;" class="header-ads-form">
        <p class="fw-bold">{{ __('admin/builder.header_ads') }}</p>
        <el-form-item label="{{ __('admin/builder.header_ads_bg') }}">
          <div class="d-flex align-items-center">
            <el-color-picker v-model="form.header_ads.bg_color" size="small" class="mb-1"></el-color-picker>

            <div class="d-flex align-items-center ms-4">
              <span class="me-2" style="color: #606266">{{ __('admin/builder.text_font_color') }}</span>
              <el-color-picker v-model="form.header_ads.color" size="small" class="mb-1"></el-color-picker>
            </div>
          </div>
        </el-form-item>
        <el-form-item label="{{ __('admin/builder.header_ads_info') }}">
          <table class="table table-bordered mb-0">
            <thead>
              <tr>
                <th>{{ __('admin/builder.modules_content') }}</th>
                <th width="200">{{ __('admin/builder.modules_link') }}</th>
                <th width="70" class="text-end">{{ __('admin/common.action') }}</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, index) in form.header_ads.items" :key="index">
                <td>
                  <text-i18n v-model="item.title"></text-i18n>
                </td>
                <td>
                  <link-selector v-model="item.link"></link-selector>
                </td>
                <td class="text-end">
                  <el-button size="small" @click="form.header_ads.items.splice(index, 1)">{{ __('admin/builder.text_delete') }}</el-button>
                </td>
              </tr>
            </tbody>
            <tfoot>
              <tr>
                <td colspan="3" class="text-end">
                  <el-button size="small" @click="addAbItem">{{ __('admin/builder.text_add') }}</el-button>
                </td>
              </tr>
            </tfoot>
          </table>
        </el-form-item>
        <el-form-item label="{{ __('common.status') }}">
          <el-switch v-model="form.header_ads.active" active-color="#71c20b"></el-switch>
        </el-form-item>
      </el-form>
    </div>
  </div>
@endsection

@push('footer')
  @include('admin::pages.design.builder.component.image_selector')
  @include('admin::pages.design.builder.component.link_selector')
  @include('admin::pages.design.builder.component.text_i18n')
  @include('admin::pages.design.builder.component.rich_text_i18n')

  <script>
    let app = new Vue({
      el: '#app',
      data: {
        form: @json($design_settings),
      },

      // computed: {},

      // watch: {},

      created() {},

      mounted() {},

      methods: {
        saveButtonClicked() {
          $http.put('design_header/builder', this.form).then((res) => {
            layer.msg(res.message)
          })
        },

        addAbItem() {
          this.form.header_ads.items.push({
            title: languagesFill(''),
            link: {
              type: 'product',
              value: '',
            },
          })
        }
      },
    })

    let saveBtn = document.querySelector('.save-btn')
    saveBtn.addEventListener('click', () => {
      app.saveButtonClicked()
    })
  </script>

  <style>
    .header-ads-form .table {
      line-height: initial;
    }
  </style>
@endpush

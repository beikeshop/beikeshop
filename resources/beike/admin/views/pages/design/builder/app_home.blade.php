@extends('admin::layouts.master')

@section('title', __('admin/common.design_app_home_index'))

@section('body-class', 'design-app-home')

@push('header')
<script src="{{ asset('vendor/vue/Sortable.min.js') }}"></script>
<script src="{{ asset('vendor/vue/vuedraggable.js') }}"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('/build/beike/admin/css/design.css') }}">
<style>
@font-face {font-family: 'ds-icon';
  src: url('/fonts/design/iconfont.woff') format('woff'), /* chrome、firefox */
  url('/fonts/design/iconfont.ttf') format('truetype'); /* chrome、firefox、opera、Safari, Android, iOS 4.2+*/
}

.ds-icon {
  font-family:"ds-icon" !important;
  font-size:16px;
  font-style:normal;
  -webkit-font-smoothing: antialiased;
  -webkit-text-stroke-width: 0;
  -moz-osx-font-smoothing: grayscale;
}
</style>
@endpush

@section('page-title-right')
<button type="button" class="btn w-min-100 btn-primary save-btn">{{ __('common.save') }}</button>
@endsection

@section('content')
<div class="card" id="app" v-cloak>
  <div class="card-body">
    <div class="module-wrap">
      <div class="c-title">{{ __('admin/app_builder.module_list') }}</div>
      <draggable class="modules-list dragArea list-group"
        :options="{group:{ name: 'people', pull: 'clone', put: false }}" :list="source.modules"
        :clone="cloneDefaultField" @end="perviewEnd">
        <div class="list-item" v-for="module, index in source.modules" :key="index">
          <div class="icon"><i class="ds-icon" v-html="module.icon"></i></div>
          <div class="name">@{{ module.title }}</div>
        </div>
      </draggable>
    </div>
    <div class="perview-wrap">
      <div class="c-title">{{ __('admin/app_builder.per_view') }}</div>
      <div class="perview-content">
        <div class="head"><img src="{{ asset('image/app-app/builder-mb-bg.png') }}" class="img-fluid"></div>
        <div class="hint" v-if="!form.modules.length">
          <i class="bi bi-brightness-high fs-2"></i>
          <div class="mt-2">{{ __('admin/app_builder.module_left_drag') }}</div>
        </div>
        <draggable class="view-modules-list dragArea list-group" :options="{animation: 300, group:'people'}"
          :list="form.modules" group="people">
          <div :class="['list-item', design.editingModuleIndex == index ? 'active' : '']"
            @click="design.editingModuleIndex = index"
            v-for="module, index in form.modules" :key="index">
            <div class="module-tool">
              <div class="module-delete" @click="deleteDodule(index)"><i class="bi bi-trash"></i></div>
            </div>
            <div v-if="module.code == 'slideshow'">
              <img :src="module.content.images[0].image[source.locale]" class="img-fluid">
            </div>
            <div v-if="module.code == 'image100'">
              <img :src="module.content.images[0].image[source.locale]" class="img-fluid">
            </div>
            <div v-if="module.code == 'icons'" :class="['quick-icon-wrapper', 'quick-icon-' + module.content.images.length]">
              <div v-if="!module.content.images.length" class="hint-right-edit">{{ __('admin/app_builder.module_right_edit') }}</div>
              <div class="link-item" v-for="item, icon_index in module.content.images" :key="icon_index">
                <img :src="item.image" class="img-fluid">
                <span>@{{ item.text[source.locale] }}</span>
              </div>
            </div>
            <div v-if="module.code == 'product' || module.code == 'category' || module.code == 'latest'">
              <div v-if="module.content.title[source.locale]" class="module-title">@{{ module.content.title[source.locale] }}</div>
              <div v-if="!module.content.products.length" class="hint-right-edit">{{ __('admin/app_builder.module_right_edit') }}</div>
              <div class="product-grid">
                <div class="product-item" v-for="item, product_index in module.content.products" :key="product_index">
                  <img :src="item.image" class="img-fluid">
                  <div class="name">@{{ item.name }}</div>
                  <div class="product-price">666</div>
                </div>
              </div>
            </div>
          </div>
        </draggable>
      </div>
    </div>
    <div class="module-edit">
      <div class="c-title">
        {{ __('admin/app_builder.module_edit') }} - <span v-if="form.modules.length">@{{ form.modules[design.editingModuleIndex].title }}</span>
      </div>
      <div v-if="form.modules.length > 0" class="component-wrap">
        <component :is="editingModuleComponent" :key="design.editingModuleIndex"
          :module="form.modules[design.editingModuleIndex].content" @on-changed="moduleUpdated"></component>
      </div>
    </div>
  </div>
</div>
<script>
  $('.page-title').append('<a class="ms-3 btn btn-outline-primary btn-sm" href="https://beikeshop.com/solution/app" target="_blank">{{ __('admin/app_builder.to_buy') }}</a>')
</script>
@include('admin::pages.design.builder.app_component.image')
@include('admin::pages.design.builder.app_component.slideshow')
@include('admin::pages.design.builder.app_component.icons')
@include('admin::pages.design.builder.app_component.product')
@include('admin::pages.design.builder.app_component.category')
@include('admin::pages.design.builder.app_component.latest')

@include('admin::pages.design.builder.component.image_selector')
@include('admin::pages.design.builder.component.link_selector')
@include('admin::pages.design.builder.component.text_i18n')
<script>
  $(document).ready(function ($) {
    const wh = window.innerHeight - 140;
    const perviewHead = $('.perview-content .head').height();
    $('#app').height(wh);
    $('.perview-content').height(wh - 70);
    $('.view-modules-list').height(wh - 74 - perviewHead);
    $('.modules-list, .component-wrap').height(wh - 70);
  })

  let app = new Vue({
    el: '#app',
    data: {
      form: @json($design_settings),
      source: {
        locale: '{{ locale() }}',
        modules: []
      },
      design: {
        editingModuleIndex: 0,
      }
    },

    computed: {
      // 编辑中的模块编辑组件
      editingModuleComponent() {
        if (!this.editingModuleCode) {
          return false;
        }

        return 'module-editor-' + this.editingModuleCode.replace('_', '-');
      },

      // 编辑中的模块 code
      editingModuleCode() {
        if (this.form.modules.length === 0) {
          return false;
        }

        return this.form.modules[this.design.editingModuleIndex].code;
      },
    },

    watch: {

    },

    methods: {
      saveButtonClicked() {
        $http.put('design_app_home/builder', this.form).then((res) => {
          layer.msg(res.message)
        })
      },

      perviewEnd(e) {
        this.design.editingModuleIndex = e.newIndex;
      },

      cloneDefaultField(e) {
        return JSON.parse(JSON.stringify(e));
      },

      deleteDodule(index) {
        this.form.modules.splice(index, 1);
        if (this.design.editingModuleIndex == index) {
          if (index - 1 < 0) {
            this.design.editingModuleIndex = 0;
            return;
          }
          this.design.editingModuleIndex = index - 1;
        }

        if (this.design.editingModuleIndex >= this.form.modules.length) {
          this.design.editingModuleIndex = this.form.modules.length - 1;
        }
      },

      moduleUpdated(e) {
        this.form.modules[this.design.editingModuleIndex].content = e;
      }
    },

    created() {},
    mounted() {},
  })

  let saveBtn = document.querySelector('.save-btn')
  saveBtn.addEventListener('click', () => {
    app.saveButtonClicked()
  })
</script>
@endsection
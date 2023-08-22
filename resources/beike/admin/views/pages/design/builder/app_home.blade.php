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
<button type="button" class="btn btn-primary save-btn">{{ __('common.save') }}</button>
@endsection

@section('content')
<div class="card" id="app" v-cloak>
  <div class="card-body">
    <div class="module-wrap">
      <div class="c-title">模块列表</div>
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
      <div class="c-title">效果预览</div>
      <div class="perview-content">
        <div class="head"><img src="{{ asset('image/app-app/builder-mb-bg.png') }}" class="img-fluid"></div>
        <div class="hint" v-if="!form.modules.length">
          <i class="bi bi-brightness-high fs-2"></i>
          <div class="mt-2">请从左边拖动模块到这里</div>
        </div>
        <draggable class="view-modules-list dragArea list-group" :options="{animation: 300, group:'people'}"
          :list="form.modules" group="people">
          <div :class="['list-item', design.editingModuleIndex == index ? 'active' : '']"
            @click="design.editingModuleIndex = index"
            v-for="module, index in form.modules" :key="index">
            <div v-if="module.code == 'slideshow'">
              <img :src="module.content.images[0].image[source.locale]" class="img-fluid">
            </div>
            <div v-if="module.code == 'image'">
              <img :src="module.content.images[0].image[source.locale]" class="img-fluid">
            </div>
            <div v-if="module.code == 'icons'" :class="['quick-icon-wrapper', 'quick-icon-' + module.content.images.length]">
              <div v-if="!module.content.images.length" class="hint-right-edit">请在右侧配置模块</div>
              <div class="link-item" v-for="item, icon_index in module.content.images" :key="icon_index">
                <img :src="item.image" class="img-fluid">
                <span>@{{ item.text[source.locale] }}</span>
              </div>
            </div>
            <div v-if="module.code == 'product' || module.code == 'category' || module.code == 'latest'">
              <div v-if="module.content.title[source.locale]" class="module-title">@{{ module.content.title[source.locale] }}</div>
              <div v-if="!module.content.products.length" class="hint-right-edit">请在右侧配置模块</div>
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
      <div class="c-title">模块编辑</div>
      <div v-if="form.modules.length > 0" class="component-wrap">
        <component :is="editingModuleComponent" :key="design.editingModuleIndex"
          :module="form.modules[design.editingModuleIndex].content" @on-changed="moduleUpdated"></component>
      </div>
    </div>
  </div>
</div>
<script>
  var $languages = @json(locales());
  var $locale = '{{ locale() }}';

  function languagesFill(text) {
    var obj = {};
    $languages.map(e => {
      obj[e.code] = text
    })

    return obj;
  }
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
    console.log(perviewHead);
    $('#app').height(wh);
    $('.perview-content').height(wh - 90);
    $('.view-modules-list').height(wh - 94 - perviewHead);
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
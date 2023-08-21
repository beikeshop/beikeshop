@extends('admin::layouts.master')
@section('title', __('admin/common.design_app_home_index'))
@section('body-class', 'design-app-home')

@push('header')
<script src="{{ asset('vendor/vue/Sortable.min.js') }}"></script>
<script src="{{ asset('vendor/vue/vuedraggable.js') }}"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('/build/beike/admin/css/design.css') }}">
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
          @{{ module.title }}
        </div>
      </draggable>
    </div>
    <div class="perview-wrap">
      <div class="c-title">效果预览</div>
      <draggable class="view-modules-list dragArea list-group" :options="{animation: 300, group:'people'}"
        :list="form.modules" group="people">
        <div :class="['list-item', design.editingModuleIndex == index ? 'active' : '']"
          @click="design.editingModuleIndex = index"
          v-for="module, index in form.modules" :key="index">
          <div v-if="module.code == 'image'">
            <img :src="module.content.images[0].image[source.locale]" class="img-fluid">
          </div>
        </div>
      </draggable>
    </div>
    <div class="edit-wrap">
      <div class="c-title">模块编辑</div>
      <div v-if="form.modules.length > 0">
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

@include('admin::pages.design.builder.component.image_selector')
@include('admin::pages.design.builder.component.link_selector')
@include('admin::pages.design.builder.component.text_i18n')
@include('admin::pages.design.builder.component.rich_text_i18n')

@include('admin::pages.design.builder.app_component.image.image')

<script>
  let idGlobal = 8;
  const wh = window.innerHeight - 140;
  const appBox = document.getElementById('app');
  appBox.style.height = wh + 'px';

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
        $http.put('design_menu/builder', this.form).then((res) => {
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
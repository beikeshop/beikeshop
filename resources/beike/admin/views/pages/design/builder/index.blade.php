<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport"
    content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="asset" content="{{ asset('/') }}">
  <base href="{{$admin_base_url}}">
  <title>{{ __('admin/builder.text_edit_home') }}</title>
  <script src="{{ asset('vendor/jquery/jquery-3.6.0.min.js') }}"></script>
  <script src="{{ asset('vendor/layer/3.5.1/layer.js') }}"></script>
  <script src="{{ asset('vendor/cookie/js.cookie.min.js') }}"></script>
  <script src="{{ asset('vendor/vue/2.7/vue' . (!config('app.debug') ? '.min' : '') . '.js') }}"></script>
  <script src="{{ mix('build/beike/admin/js/app.js') }}"></script>
  <script src="{{ asset('vendor/vue/Sortable.min.js') }}"></script>
  <script src="{{ asset('vendor/vue/vuedraggable.js') }}"></script>
  <script src="{{ asset('vendor/tinymce/5.9.1/tinymce.min.js') }}"></script>
  <script src="{{ asset('vendor/element-ui/index.js') }}"></script>
  <link rel="stylesheet" href="{{ asset('vendor/element-ui/index-blue.css') }}">
  <link rel="shortcut icon" href="{{ image_origin(system_setting('base.favicon')) }}">
  @if (locale() != 'zh_cn')
    <script src="{{ asset('vendor/element-ui/language/' . locale() . '.js') }}"></script>
  @endif
  <link rel="stylesheet" type="text/css" href="{{ asset('/build/beike/admin/css/design.css') }}">
  @stack('header')
  <script>
    @if (locale() != 'zh_cn')
      ELEMENT.locale(ELEMENT.lang['{{ locale() }}'])
    @endif
    const lang = {
      file_manager: '{{ __('admin/file_manager.file_manager') }}',
    }

    const config = {
      beike_version: '{{ config('beike.version') }}',
      api_url: '{{ beike_api_url() }}',
      app_url: '{{ config('app.url') }}',
    }
  </script>
</head>

<body class="page-design">
  <div class="design-content" id="app" v-cloak v-loading="!design.ready">
    <div class="design-head">
      {{-- <div v-if="design.editType != 'add'" @click="showAllModuleButtonClicked"><i class="el-icon-back"></i>{{ __('common.return') }}</div> --}}
      <div @click="viewHome">
        <div class="btn btn-exit"><i class="el-icon-switch-button"></i>{{ __('common.exit') }}</div>
      </div>
      <div class="head-centre">
        <div class="device-wrap">
          <div :class="device == 'pc' ? 'active' : ''" @click="device = 'pc'"><i class="el-icon-monitor"></i></div>
          <div :class="device == 'mb' ? 'active' : ''" @click="device = 'mb'"><i class="el-icon-mobile-phone"></i></div>
        </div>
      </div>
      <div @click="saveButtonClicked">
        <div class="btn btn-save"><i class="el-icon-check"></i>{{ __('common.save') }}</div>
      </div>
    </div>
    <div class="design-box">
      <div :class="['sidebar-edit-wrap', !design.sidebar ? 'v-hide' : '']">
        <div class="switch-design" :class="['hide-design', !design.sidebar ? 'v-hide' : '']" @click="design.sidebar = !design.sidebar"><i class="iconfont">@{{ design.sidebar ? '&#xe659;' : '&#xe65b;' }}</i></div>
        <div class="module-edit" v-if="form.modules.length > 0 && design.editType == 'module'">
          <div class="module-editor-setting-row">
            <span class="btn-back" @click="showAllModuleButtonClicked"><i class="el-icon-arrow-left"></i>{{ __('common.return') }}</span>
            <span class="title">{{ __('admin/builder.text_set_up') }}</span>
          </div>
          <component
            :is="editingModuleComponent"
            :key="design.editingModuleIndex"
            :module="form.modules[design.editingModuleIndex].content"
            @on-changed="moduleUpdated"
          ></component>
        </div>

        <div class="modules-list" v-show="design.editType == 'add'">
          <div style="padding: 5px; color: #666;"><i class="el-icon-microphone"></i> {{ __('admin/builder.modules_instructions') }}</div>

          <el-row id="module-list-wrap">
            <el-col :span="12" v-for="(item, index) in source.modules" :key="index" class="iframe-modules-sortable-ghost">
              <div class="module-list" :data-code="item.code" :data-index="index">
                <div class="module-info">
                  <div class="icon">
                    <i :style="item.style" class="iconfont" v-if="isIcon(item.icon)" v-html="item.icon"></i>
                    <div class="img-icon" v-else><img :src="item.icon" class="img-fluid"></div>
                  </div>
                  <div class="name">@{{ item.name }}</div>
                </div>
              </div>
            </el-col>
          </el-row>
        </div>
        <div class="layout-modules">
          <div class="layout-module-header">
            <div class="layout-module-title">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5"/>
              </svg>
              {{ __('admin/builder.layout_modules') }}
            </div>
            <div class="layout-module-content">
              <draggable class="menus-wrap d-block d-lg-flex mb-2 mb-lg-0" v-if="form.modules.length" :list="form.modules"
                @end="endDrag"
                :animation="330"
                :handle="'.drag-icon'"
              >
                <div
                  v-for="(item, index) in form.modules" :key="index"
                  :class="{
                    'item': true,
                    'active': index == design.editingModuleIndex && design.editType == 'module'
                  }"
                  @click="editModuleButtonClicked(index)">
                  <div class="drag-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#777" class="bi bi-grip-vertical" viewBox="0 0 16 16">
                      <path d="M7 2a1 1 0 1 1-2 0 1 1 0 0 1 2 0m3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0M7 5a1 1 0 1 1-2 0 1 1 0 0 1 2 0m3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0M7 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0m3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0m-3 3a1 1 0 1 1-2 0 1 1 0 0 1 2 0m3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0m-3 3a1 1 0 1 1-2 0 1 1 0 0 1 2 0m3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0"/>
                    </svg>
                  </div>
                  <div>@{{ getModuleName(item.code) }}</div>
                  <div class="delete" @click.stop="deleteModule(index)"><i class="el-icon-delete"></i></div>
                </div>
              </draggable>
            </div>
          </div>
        </div>

        <div class="effect-preview-wrap" v-if="design.modulePreviewImage">
          <div class="title">{{ __('admin/builder.effect_preview') }}</div>
          <div class="preview">
            <img :src="design.modulePreviewImage" class="img-fluid">
          </div>
        </div>
      </div>
      <div class="preview-iframe">
        <iframe src="{{ url('/') }}?design=1" frameborder="0" id="preview-iframe" :width="device == 'pc' ? '100%' : '470px'" height="100%"></iframe>
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

  @foreach($editors as $editor)
  <x-dynamic-component :component="$editor" />
  @endforeach

  @include('admin::pages.design.builder.component.image_selector')
  @include('admin::pages.design.builder.component.link_selector')
  @include('admin::pages.design.builder.component.text_i18n')
  @include('admin::pages.design.builder.component.rich_text_i18n')
  @include('admin::pages.design.builder.component.module_size')

  <script>
    let register = null;
    var previewWindow = null;
    let isDragging = false;

    let app = new Vue({
      el: '#app',
      data: {
        device: 'pc',
        form: {
          modules: [],
        },

        design: {
          type: 'pc',
          editType: 'add',
          sidebar: true,
          editingModuleIndex: null,
          ready: false,
          showModuleList: false,
          moduleLoadCount: 0, // 第一次选择已配置模块时，不需要请求数据，
          modulePreviewImage: '',
        },

        source: {
          modules: [],
          config: []
        },
      },
      // 计算属性
      computed: {
        // 编辑中的模块编辑组件
        editingModuleComponent() {
          return 'module-editor-' + this.editingModuleCode.replaceAll('_', '-');
        },

        // 编辑中的模块 code
        editingModuleCode() {
          return this.form.modules[this.design.editingModuleIndex].code;
        },
      },

      methods: {
        getModuleName(code) {
          const module = this.source.modules.find(e => e.code === code)
          if (module) {
            return module.name;
          }
          return '';
        },

        moduleHover(index) {
          if (index === null) {
            this.design.modulePreviewImage = '';
            return;
          }

          this.design.modulePreviewImage = this.source.modules[index].image;
        },

        endDrag(e) {
          const newIndex = e.newIndex;
          const oldIndex = e.oldIndex;
          const $container = $(previewWindow.document).find('#home-modules-box');
          const $children = $container.children();
          const $moved = $children.eq(oldIndex); // 被拖动的元素
          if (newIndex > oldIndex) {
            // 往后拖：插到 newIndex 后面
            $moved.insertAfter($children.eq(newIndex));
          } else {
            // 往前拖：插到 newIndex 前面
            $moved.insertBefore($children.eq(newIndex));
          }
        },

        moduleUpdated: bk.debounce(function(val) {
          if (!this.design.moduleLoadCount) return this.design.moduleLoadCount = 1;
          this.form.modules[this.design.editingModuleIndex].content = val;
          const data = this.form.modules[this.design.editingModuleIndex]

          $http.post('design/builder/preview?design=1', data, {hload: true}).then((res) => {
            $(previewWindow.document).find('#module-' + data.module_id).replaceWith(res);
            $(previewWindow.document).find('#module-' + data.module_id).addClass('currently-editing');
            $(previewWindow.document).find('.tooltip').remove();
            const tooltipTriggerList = previewWindow.document.querySelectorAll('[data-bs-toggle="tooltip"]')
            const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new previewWindow.bootstrap.Tooltip(tooltipTriggerEl))
          })
        }, 300),

        addModuleButtonClicked(code, moduleItemIndex = null, callback = null) {
          const sourceModule = JSON.parse(JSON.stringify(this.source.modules.find(e => e.code == code)));
          const module_id = bk.randomString(16)
          const _data = {
            code: code,
            content: sourceModule.make,
            module_id: module_id,
            name: sourceModule.name,
            view_path: sourceModule.view_path || '',
          }

          $http.post('design/builder/preview?design=1', _data, {hload: true}).then((res) => {
            $(previewWindow.document).find('.module-item').removeClass('currently-editing');

            if (moduleItemIndex === null) {
              $(previewWindow.document).find('.modules-box').append(res);
              this.form.modules.push(_data);
              this.design.editingModuleIndex = this.form.modules.length - 1;
              this.design.editType = 'module';
            } else {
              $(previewWindow.document).find('.modules-box').children().eq(moduleItemIndex).before(res);
              this.form.modules.splice(moduleItemIndex, 0, _data);
              this.design.editingModuleIndex = moduleItemIndex;
              this.design.editType = 'module';
            }

            setTimeout(() => {
              $(previewWindow.document).find("html, body").animate({
                scrollTop: $(previewWindow.document).find('#module-' + module_id).offset().top - 96
              }, 50);
            }, 200)
          }).finally(() => {
            if (callback) {
              callback();
            }
          })
        },

        // 编辑模块
        editModuleButtonClicked(index) {
          if (this.design.editingModuleIndex == index) {
            return;
          }

          $(previewWindow.document).find('.module-item').removeClass('currently-editing');
          this.design.moduleLoadCount = 0;
          this.design.editingModuleIndex = index;
          this.design.editType = 'module';

          setTimeout(() => {
            $(previewWindow.document).find('#module-' + this.form.modules[index].module_id).addClass('currently-editing');
            $(previewWindow.document).find("html, body").animate({
              scrollTop: $(previewWindow.document).find('#module-' + this.form.modules[index].module_id).offset().top - 96
            }, 50);
          }, 200)
        },

        deleteModule(index) {
          this.$confirm('{{ __('common.confirm_delete') }}', {
            title: "{{ __('common.text_hint') }}",
            btn: ['{{ __('common.cancel') }}', '{{ __('common.confirm') }}'],
          }).then(_ => {
            $(previewWindow.document).find('#module-' + this.form.modules[index].module_id).remove();
            this.form.modules.splice(index, 1);
            this.design.editType = 'add';
            this.design.editingModuleIndex = null;
          }).catch(_ => {});
        },

        saveButtonClicked() {
          $http.put('design/builder', this.form).then((res) => {
            layer.msg(res.message)
          })
        },

        exitDesign() {
          history.back();
        },

        viewHome() {
          this.$confirm('{{ __('common.exit_edit_tips') }}', {
            title: "{{ __('common.text_hint') }}",
            btn: ['{{ __('common.cancel') }}', '{{ __('common.confirm') }}'],
          }).then(_ => {
            location = '/';
          }).catch(_ => {});
        },

        isIcon(code) {
          // 判断 code 是否以 &# 开头
          return code.indexOf('&#') == 0;
        },

        showAllModuleButtonClicked() {
          this.design.editType = 'add';
          this.design.editingModuleIndex = 0;
          $(previewWindow.document).find('.module-item').removeClass('currently-editing');
        },

        previewIframeInit() {
          $('#preview-iframe').on('load', function(event) {
            previewWindow = document.getElementById("preview-iframe").contentWindow;
            app.design.ready = true;

            // 编辑模块
            $(previewWindow.document).on('click', '.modules-box > .module-item', function(event) {
              const module_id = $(this).prop('id').replace('module-', '');
              const modules = app.form.modules;
              const editingModuleIndex = modules.findIndex(e => e.module_id == module_id);
              app.editModuleButtonClicked(editingModuleIndex);
            });

            new Sortable(document.getElementById('module-list-wrap'), {
              group: {
                name: 'shared',
                pull: 'clone',
                put: false // 不允许拖拽进这个列表
              },
              // ghostClass: 'iframe-modules-sortable-ghost',
              animation: 150,
              sort: false, // 设为false，禁止sort
              onStart: function (evt) {
                app.design.modulePreviewImage = ''
                isDragging = true;
              },
              onEnd: function (evt) {
                isDragging = false;
                if (evt.to.id != 'home-modules-box') {
                  return;
                }

                // 获取 当前位置 在modules-box 是第几个
                const index = $(previewWindow.document).find('.modules-box').children().index(evt.item);
                const moduleCode = $(evt.item).find('.module-list').data('code');

                app.addModuleButtonClicked(moduleCode, index, () => {
                  evt.item.parentNode.removeChild(evt.item);
                });
              }
            });

            new Sortable(previewWindow.document.getElementById('home-modules-box'), {
              group: {
                name: 'shared',
                pull: 'clone',
              },
              animation: 150,
              onUpdate: function (evt) {
                const modules = app.form.modules;
                const module = modules.splice(evt.oldIndex, 1)[0];
                modules.splice(evt.newIndex, 0, module);
                app.form.modules = modules;
              }
            });
          });
        }
      },

      created () {
        this.form = @json($design_settings ?: ['modules' => []]);
      },

      mounted () {
        this.previewIframeInit();
      },
    })

    $(function () {
      $(document).on('mouseenter', '.module-list', function() {
        if (isDragging) return;
        app.moduleHover($(this).data('index'));
      });

      $(document).on('mouseleave', '.module-list', function() {
        app.moduleHover(null);
      });

      $(document).on('click', '.module-list', function() {
        app.addModuleButtonClicked($(this).data('code'));
      });
    })
  </script>
  @stack('footer-script')
</body>
</html>
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
  <script src="{{ asset('vendor/vue/2.6.14/vue.js') }}"></script>
  <script src="{{ mix('build/beike/admin/js/app.js') }}"></script>
  <script src="{{ asset('vendor/vue/Sortable.min.js') }}"></script>
  <script src="{{ asset('vendor/vue/vuedraggable.js') }}"></script>
  <script src="{{ asset('vendor/element-ui/2.15.6/js.js') }}"></script>
  <link rel="stylesheet" href="{{ asset('vendor/element-ui/2.15.6/css.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('/build/beike/admin/css/design.css') }}">
  @stack('header')
  <script>
    const lang = {
      file_manager: '{{ __('admin/file_manager.file_manager') }}',
    }
  </script>
</head>

<body class="page-design">
  <div class="design-box">
    <div class="sidebar-edit-wrap" id="app" v-cloak>
      <div class="design-head">
        <div v-if="design.editType != 'add'" @click="showAllModuleButtonClicked"><i class="el-icon-back"></i>{{ __('common.return') }}</div>
        <div @click="viewHome"><i class="el-icon-switch-button"></i>{{ __('common.exit') }}</div>
        <div @click="saveButtonClicked"><i class="el-icon-check"></i>{{ __('common.save') }}</div>
      </div>
      <div class="module-edit" v-if="form.modules.length > 0 && design.editType == 'module'">
        <component
          :is="editingModuleComponent"
          :key="design.editingModuleIndex"
          :module="form.modules[design.editingModuleIndex].content"
          @on-changed="moduleUpdated"
        ></component>
      </div>

      <div class="modules-list">
        <div style="padding: 5px; color: #666;"><i class="el-icon-microphone"></i> {{ __('admin/builder.modules_instructions') }}</div>

        <el-row v-if="design.editType == 'add'">
          <el-col :span="12" v-for="(item, index) in source.modules" :key="index">
            <div @click="addModuleButtonClicked(item.code)" class="module-list">
              <div class="module-info">
                <div class="icon"><i :style="item.style" class="iconfont" v-html="item.icon"></i></div>
                <div class="name">@{{ item.name }}</div>
              </div>
            </div>
          </el-col>
        </el-row>
      </div>
    </div>
    <div class="preview-iframe">
      <iframe src="{{ url('/') }}?design=1" frameborder="0" id="preview-iframe" width="100%" height="100%"></iframe>
    </div>
  </div>


  @foreach($editors as $editor)
    <x-dynamic-component :component="$editor" />
  @endforeach

  <script>
    var $languages = @json($admin_languages);
    var $locale = '{{ locale() }}';

    function languagesFill(text) {
      var obj = {};
      $languages.map(e => {
        obj[e.code] = text
      })

      return obj;
    }

    // iframe 操作
    var previewWindow = null;
    $('#preview-iframe').on('load', function(event) {
      previewWindow = document.getElementById("preview-iframe").contentWindow;
      app.design.ready = true;
      app.design.sidebar = true;

      // 编辑模块
      $(previewWindow.document).on('click', '.module-edit .edit', function(event) {
        const module_id = $(this).parents('.module-item').prop('id').replace('module-', '');
        const modules = app.form.modules;
        const editingModuleIndex = modules.findIndex(e => e.module_id == module_id);
        app.editModuleButtonClicked(editingModuleIndex);
      });

      // 删除模块
      $(previewWindow.document).on('click', '.module-edit .delete', function(event) {
        const module_id = $(this).parents('.module-item').prop('id').replace('module-', '');
        const editingModuleIndex = app.form.modules.findIndex(e => e.module_id == module_id);
        app.design.editType = 'add';
        app.design.editingModuleIndex = 0;
        $(this).parents('.module-item').remove();
        app.form.modules.splice(editingModuleIndex, 1);
      });

      // 模块位置改变，点击.module-edit .up或者.down
      $(previewWindow.document).on('click', '.module-edit .up, .module-edit .down', function(event) {
        const module_id = $(this).parents('.module-item').prop('id').replace('module-', '');
        const modules = app.form.modules;
        const editingModuleIndex = modules.findIndex(e => e.module_id == module_id);
        const up = $(this).hasClass('up');
        app.design.editType = 'add';
        app.design.editingModuleIndex = 0;
        if (up) {
          if (editingModuleIndex > 0) {
            const module = modules[editingModuleIndex];
            modules.splice(editingModuleIndex, 1);
            modules.splice(editingModuleIndex - 1, 0, module);
            // dom操作
            $(this).parents('.module-item').insertBefore($(this).parents('.module-item').prev());
          }
        } else {
          if (editingModuleIndex < modules.length - 1) {
            const module = modules[editingModuleIndex];
            modules.splice(editingModuleIndex, 1);
            modules.splice(editingModuleIndex + 1, 0, module);
            // dom操作
            $(this).parents('.module-item').insertAfter($(this).parents('.module-item').next());
          }
        }
        app.form.modules = modules;
      });
    });
  </script>

  @include('admin::pages.design.builder.component.image_selector')
  @include('admin::pages.design.builder.component.link_selector')
  @include('admin::pages.design.builder.component.text_i18n')

  <script>
    let app = new Vue({
      el: '#app',
      data: {
        form: {
          modules: [
            // {"content":{"style":{"background_color":""},"full":true,"floor":{"2":"","3":""},"images":[{"image":{"2":"catalog/demo/slideshow/2.jpg","3":"catalog/demo/slideshow/2.jpg"},"show":true,"link":{"type":"product","value":"","link":""}},{"image":{"2":"catalog/demo/slideshow/1.jpg","3":"catalog/demo/slideshow/1.jpg"},"show":false,"link":{"type":"product","value":"","link":""}}]},"code":"slideshow","name":"幻灯片","module_id":"b0448efb0989"}
          ]
        },

        design: {
          type: 'pc',
          editType: 'add',
          sidebar: false,
          editingModuleIndex: 0,
          ready: false,
          moduleLoadCount: 0, // 第一次选择已配置模块时，不需要请求数据，
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
          return 'module-editor-' + this.editingModuleCode.replace('_', '-');
        },

        // 编辑中的模块 code
        editingModuleCode() {
          return this.form.modules[this.design.editingModuleIndex].code;
        },

        // editingConfigCodeFormat() {
        //   return 'config-' + this.config.editingConfigCode;
        // },
      },
      // 侦听器
      watch: {},
      // 组件方法
      methods: {
        moduleUpdated: bk.debounce(function() {
          const data = this.form.modules[this.design.editingModuleIndex]

          $http.post('design/builder/preview?design=1', data, {hload: true}).then((res) => {
            $(previewWindow.document).find('#module-' + data.module_id).replaceWith(res);
          })
        }, 300),

        addModuleButtonClicked(code) {
          const sourceModule = this.source.modules.find(e => e.code == code)
          const module_id = bk.randomString(16)
          const _data = {
            code: code,
            content: sourceModule.make,
            module_id: module_id,
            name: sourceModule.name,
          }

          $http.post('design/builder/preview?design=1', _data, {hload: true}).then((res) => {
            $(previewWindow.document).find('.modules-box').append(res);
            this.form.modules.push(_data);
            this.design.editingModuleIndex = this.form.modules.length - 1;
            this.design.editType = 'module';


            setTimeout(() => {
              $(previewWindow.document).find("html, body").animate({
                scrollTop: $(previewWindow.document).find('#module-' + module_id).offset().top - 30
              }, 50);
            }, 200)
          })
        },

        // 编辑模块
        editModuleButtonClicked(index) {
          this.design.editingModuleIndex = index;
          this.design.editType = 'module';
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
          // window.open('/');
          location = '/';
        },

        showAllModuleButtonClicked() {
          this.design.editType = 'add';
          this.design.editingModuleIndex = 0;
        }
      },
      created () {
        this.form = @json($design_settings)
      },
      mounted () {
      },
    })

    // window.addEventListener('message', (event) => {
    //   event.stopPropagation()
    //   if (typeof(event.data.index) !== 'undefined') {
    //     app.editModuleButtonClicked(event.data.index)
    //   }
    // }, false)
  </script>
</body>
</html>

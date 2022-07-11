<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport"
    content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>首页编辑器</title>
  <script src="{{ asset('vendor/jquery/jquery-3.6.0.min.js') }}"></script>
  <script src="{{ asset('vendor/layer/3.5.1/layer.js') }}"></script>
  <script src="{{ asset('/build/beike/shop/default/js/app.js') }}"></script>
  <script src="{{ asset('vendor/vue/2.6.14/vue.js') }}"></script>
  <script src="{{ asset('vendor/vue/Sortable.min.js') }}"></script>
  <script src="{{ asset('vendor/vue/vuedraggable.js') }}"></script>
  <script src="{{ asset('vendor/element-ui/2.15.6/js.js') }}"></script>
  <link rel="stylesheet" href="{{ asset('vendor/element-ui/2.15.6/css.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('/build/beike/shop/default/css/design/app.css') }}">
  @stack('header')
</head>

<body class="page-design">
  <div class="design-box">
    <div class="sidebar-edit-wrap" id="app" v-cloak>
      <div class="design-head">
        <div>保存</div>
        <div>退出</div>
      </div>
      <div class="module-edit" v-if="form.modules.length > 0 && design.editType == 'module'">
        <component
          :is="editingModuleComponent"
          :key="design.editingModuleIndex"
          :module="form.modules[design.editingModuleIndex].content"
          @on-changed="moduleUpdated"
        ></component>
      </div>

      <el-row v-if="design.editType == 'add'" class="modules-list">
        <el-col :span="12" v-for="(item, index) in source.modules" :key="index">
          <div @click="addModuleButtonClicked(item.code)" class="module-list">
            <div class="module-info">
              <div class="icon"><i class="iconfont" v-html="item.icon"></i></div>
              <div class="name">@{{ item.name }}</div>
            </div>
          </div>
        </el-col>
      </el-row>
    </div>
    <div class="preview-iframe">
      <iframe src="{{ url('/') }}" frameborder="0" id="preview-iframe" width="100%" height="100%"></iframe>
    </div>
  </div>

  @foreach($editors as $editor)
    <x-dynamic-component :component="$editor" />
  @endforeach

  <script>
    let app = new Vue({
      el: '#app',
      data: {
        form: {
          modules: [
            {"content":{"style":{"background_color":""},"full":true,"floor":{"2":"","3":""},"images":[{"image":{"2":"catalog/demo/slideshow/2.jpg","3":"catalog/demo/slideshow/2.jpg"},"show":true,"link":{"type":"product","value":"","link":""}},{"image":{"2":"catalog/demo/slideshow/1.jpg","3":"catalog/demo/slideshow/1.jpg"},"show":false,"link":{"type":"product","value":"","link":""}}]},"code":"slideshow","name":"幻灯片","module_id":"b0448efb0989"}
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
        moduleUpdated() {
          console.log('moduleUpdated')
        },

        addModuleButtonClicked(code) {
          console.log(code)
        },

        // 编辑模块
        editModuleButtonClicked(index) {
          this.design.editingModuleIndex = index;
          this.design.editType = 'module';
        },
      },
      // 在实例初始化之后，组件属性计算之前，如data属性等
      beforeCreate () {
      },
      // 在实例创建完成后被立即同步调用
      created () {
      },
      // 在挂载开始之前被调用:相关的 render 函数首次被调用
      beforeMount () {
      },
      // 实例被挂载后调用
      mounted () {
      },
    })

    window.addEventListener('message', (event) => {
      event.stopPropagation()
      if (typeof(event.data.index) !== 'undefined') {
        app.editModuleButtonClicked(event.data.index)
      }
    }, false)
  </script>
</body>
</html>

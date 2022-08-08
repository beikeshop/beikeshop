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
  <title>首页编辑器</title>
  <script src="{{ asset('vendor/jquery/jquery-3.6.0.min.js') }}"></script>
  <script src="{{ asset('vendor/layer/3.5.1/layer.js') }}"></script>
  <script src="{{ mix('build/beike/admin/js/app.js') }}"></script>
  <script src="{{ asset('vendor/vue/2.6.14/vue.js') }}"></script>
  <script src="{{ asset('vendor/vue/Sortable.min.js') }}"></script>
  <script src="{{ asset('vendor/vue/vuedraggable.js') }}"></script>
  <script src="{{ asset('vendor/element-ui/2.15.6/js.js') }}"></script>
  <link rel="stylesheet" href="{{ asset('vendor/element-ui/2.15.6/css.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('/build/beike/admin/css/design.css') }}">
  @stack('header')
</head>

<body class="page-design">
  <div class="design-box">
    <div class="sidebar-edit-wrap" id="app" v-cloak>
      <div class="design-head">
        <div @click="viewHome"><i class="el-icon-view"></i>查看页面</div>
        <div @click="saveButtonClicked"><i class="el-icon-check"></i>保存</div>
      </div>
      <div class="module-edit">
        <el-collapse value="intro" @change="footerItemChange" accordion>
          <el-collapse-item title="服务图标" name="service_icon">
            <div class="module-edit-group">
              <div class="module-edit-group">
                <div class="module-edit-title">启用</div>
                <el-switch v-model="form.services.enable"></el-switch>
              </div>
              <draggable
                ghost-class="dragabble-ghost"
                :list="form.services.items"
                :options="{animation: 330, handle: '.icon-rank'}"
              >
                <div class="pb-images-selector" v-for="(item, index) in form.services.items" :key="index">
                  <div class="selector-head" @click="selectorShow(index)">
                    <div class="left">
                      <el-tooltip class="icon-rank" effect="dark" content="拖动排序" placement="left">
                        <i class="el-icon-rank"></i>
                      </el-tooltip>

                      <img :src="thumbnail(item.image, 40, 40)" class="img-responsive">
                    </div>

                    {{-- <div class="right"><i :class="'fa fa-angle-'+(item.show ? 'up' : 'down')"></i></div> --}}
                    <div class="right">
                      <i :class="'el-icon-arrow-'+(item.show ? 'up' : 'down')"></i>
                    </div>
                  </div>
                  <div :class="'pb-images-list ' + (item.show ? 'active' : '')">
                    <pb-image-selector v-model="item.image" :is-language="false"></pb-image-selector>
                    <div class="tag">建议尺寸 100 x 100</div>
                    <div class="module-edit-title">标题</div>
                    <text-i18n v-model="item.title"></text-i18n>
                    <div class="module-edit-title">副标题</div>
                    <text-i18n v-model="item.sub_title"></text-i18n>
                  </div>
                </div>
              </draggable>
            </div>
          </el-collapse-item>
        </el-collapse>
      </div>
    </div>
    <div class="preview-iframe">
      <iframe src="{{ url('/') }}?design=1" frameborder="0" id="preview-iframe" width="100%" height="100%"></iframe>
    </div>
  </div>


  <script>
    var $languages = @json($languages);
    var $language_id = '{{ locale() }}';

    // function languagesFill(text) {
    //   var obj = {};
    //   $languages.map(e => {
    //     obj[e.code] = text
    //   })

    //   return obj;
    // }

    Vue.prototype.thumbnail = function thumbnail(image, width, height) {
      if (!image) {
        return 'image/placeholder.png';
      }

      return '{{ asset('') }}' + image;
    };

    function randomString(length) {
      let str = '';
      for (; str.length < length; str += Math.random().toString(36).substr(2));
      return str.substr(0, length);
    }

    // iframe 操作
    var previewWindow = null;
    $('#preview-iframe').on('load', function(event) {
      previewWindow = document.getElementById("preview-iframe").contentWindow;
      app.design.ready = true;
      app.design.sidebar = true;


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

        },

        design: {
          type: 'pc',
          ready: false,
        },

        source: {
        },
      },
      // 计算属性
      computed: {
      },
      // 侦听器
      watch: {},
      // 组件方法
      methods: {
        moduleUpdated(module) {
          const data = this.form.modules[this.design.editingModuleIndex]

          $http.post('design/builder/preview?design=1', data, {hload: true}).then((res) => {
            $(previewWindow.document).find('#module-' + data.module_id).replaceWith(res);
          })
        },

        footerItemChange(val) {
          // $footer = $("#preview-iframe").contents().find('footer');
          // $footer.find("div").removeClass('footer-active');
          // if (!val) return;
          // $footer.find(`.${val}`).addClass('footer-active');
        },

        // addModuleButtonClicked(code) {
        //   const sourceModule = this.source.modules.find(e => e.code == code)
        //   const module_id = randomString(16)
        //   const _data = {
        //     code: code,
        //     content: sourceModule.make,
        //     module_id: module_id,
        //     name: sourceModule.name,
        //   }

        //   $http.post('design/builder/preview?design=1', _data, {hload: true}).then((res) => {
        //     $(previewWindow.document).find('.modules-box').append(res);
        //     this.form.modules.push(_data);
        //     this.design.editingModuleIndex = this.form.modules.length - 1;
        //     this.design.editType = 'module';


        //     setTimeout(() => {
        //       $(previewWindow.document).find("html, body").animate({
        //         scrollTop: $(previewWindow.document).find('#module-' + module_id).offset().top - 30
        //       }, 50);
        //     }, 200)
        //   })
        // },

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

        selectorShow(index) {
          this.form.services.items.find((e, key) => {if (index != key) return e.show = false});
          this.form.services.items[index].show = !this.form.services.items[index].show;
          this.$forceUpdate();
        },

        exitDesign() {
          history.back();
        },

        viewHome() {
          window.open('/');
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

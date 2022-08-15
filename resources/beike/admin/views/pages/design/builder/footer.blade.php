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
  <title>页尾编辑器</title>
  <script src="{{ asset('vendor/jquery/jquery-3.6.0.min.js') }}"></script>
  <script src="{{ asset('vendor/layer/3.5.1/layer.js') }}"></script>
  <script src="{{ asset('vendor/vue/2.6.14/vue.js') }}"></script>
  <script src="{{ mix('build/beike/admin/js/app.js') }}"></script>
  <script src="{{ asset('vendor/vue/Sortable.min.js') }}"></script>
  <script src="{{ asset('vendor/vue/vuedraggable.js') }}"></script>
  <script src="{{ asset('vendor/tinymce/5.9.1/tinymce.min.js') }}"></script>
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
          <el-collapse-item title="服务图标" name="services-wrap">
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
          <el-collapse-item title="Logo/介绍" name="footer-content-left">
            <div class="module-edit-group">
              <div class="module-edit-title">logo</div>
              <pb-image-selector v-model="form.content.intro.logo" :is-language="false"></pb-image-selector>
            </div>
            <div class="module-edit-group">
              <div class="module-edit-title">简介</div>
              <rich-text-i18n v-model="form.content.intro.text"></rich-text-i18n>
            </div>
          </el-collapse-item>
          @for ($i = 1; $i <= 3; $i++)
          <el-collapse-item title="链接栏{{ $i }}" name="footer-content-link{{ $i }}">
            <div class="module-edit-group">
              <div class="module-edit-title">配置标题</div>
              <text-i18n v-model="form.content.link{{ $i }}.title"></text-i18n>
            </div>
            <div class="module-edit-group">
              <div class="module-edit-title">链接</div>

              <draggable
                ghost-class="dragabble-ghost"
                :list="form.content.link{{ $i }}.links"
                :options="{animation: 330, handle: '.icon-rank'}">
                <div v-for="(item, index) in form.content.link{{ $i }}.links" :key="index" class="footer-link-item">
                  <el-tooltip class="icon-rank" effect="dark" content="拖动排序" placement="left">
                    <i class="el-icon-rank"></i>
                  </el-tooltip>
                  <link-selector :is-custom-name="true" :hide-types="['product', 'category', 'brand']" v-model="form.content.link{{ $i }}.links[index]"></link-selector>
                  <div class="remove-item" @click="removeLink('link{{ $i }}', index)"><i class="iconfont">&#xe63a;</i></div>
                </div>
              </draggable>
            </div>
            <el-button class="add-item" size="mini" type="primary" plain @click="topLinkAddLinkButtonClicked({{ $i }})">添加链接</el-button>
          </el-collapse-item>
          @endfor

          <el-collapse-item title="联系我们" name="footer-content-contact">
            <div class="module-edit-group">
              <div class="module-edit-title">联系电话</div>
              <el-input placeholder="联系电话" size="small" v-model="form.content.contact.telephone"></el-input>
            </div>
            <div class="module-edit-group">
              <div class="module-edit-title">地址</div>
              <el-input placeholder="地址" size="small" v-model="form.content.contact.address"></el-input>
            </div>
            <div class="module-edit-group">
              <div class="module-edit-title">邮箱</div>
              <el-input placeholder="邮箱" size="small" v-model="form.content.contact.email"></el-input>
            </div>
          </el-collapse-item>

          <el-collapse-item title="版权/图片" name="footer-bottom">
            <div class="module-edit-group">
              <div class="module-edit-title">版权设置</div>
              <rich-text-i18n v-model="form.bottom.copyright"></rich-text-i18n>
            </div>
            <div class="module-edit-group">
              <div class="module-edit-title">图片</div>
              <pb-image-selector v-model="form.bottom.image" :is-language="false"></pb-image-selector>
            </div>
          </el-collapse-item>
        </el-collapse>
      </div>
    </div>
    <div class="preview-iframe">
      <iframe src="{{ url('/') }}" frameborder="0" id="preview-iframe" width="100%" height="100%"></iframe>
    </div>
  </div>


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

      // 页面滚动到最底部
      $(previewWindow.document).ready(function() {
        previewWindow.scrollTo(0, $(previewWindow.document).height());
      });
    });
  </script>

  @include('admin::pages.design.builder.component.image_selector')
  @include('admin::pages.design.builder.component.link_selector')
  @include('admin::pages.design.builder.component.text_i18n')
  @include('admin::pages.design.builder.component.rich_text_i18n')

  <script>
    let app = new Vue({
      el: '#app',
      data: {
        form: @json($design_settings),

        design: {
        },

        source: {
        },
      },
      // 计算属性
      computed: {
      },
      // 侦听器
      watch: {
        form: {
          handler: function(val, oldVal) {
            this.footerUpdate();
          },
          deep: true,
        }
      },
      // 组件方法
      methods: {
        footerUpdate: bk.debounce(function() {
          $http.post('design_footer/builder/preview', this.form, {hload: true}).then((res) => {
            if (previewWindow) {
              $(previewWindow.document).find('footer').replaceWith(res);
            }
          })
        }, 300),

        footerItemChange(val) {
          // console.log(val)
          $footer = $("#preview-iframe").contents().find('footer');
          $footer.find("div").removeClass('footer-active');
          if (!val) return;
          $footer.find(`.${val}`).addClass('footer-active');
        },

        // 编辑模块
        editModuleButtonClicked(index) {
          this.design.editingModuleIndex = index;
          this.design.editType = 'module';
        },

        topLinkAddLinkButtonClicked(index) {
          this.form.content['link' + index].links.push({type: 'page', value: '', text: {}});
        },

        removeLink(item, index) {
          this.form.content[item].links.splice(index, 1);
        },

        saveButtonClicked() {
          $http.put('design_footer/builder', this.form).then((res) => {
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
      },
      mounted () {
      },
    })
  </script>
</body>
</html>

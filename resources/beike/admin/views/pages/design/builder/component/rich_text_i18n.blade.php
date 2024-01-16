<template id="rich-text-i18n-template">
  <div class="rich-text-i18n-template">
    <el-button style="width: 100%;" icon="el-icon-edit-outline" plain @click="richTextDialogChecked('open')">{{ __('admin/builder.modules_edit_content') }}</el-button>
    <el-dialog
      custom-class="rich-text-dialog"
      :modal-append-to-body="false"
      title="{{ __('common.edit') }}"
      :visible.sync="richTextDialog.show"
      width="800px">
      <el-tabs v-if="languages.length > 1" value="language-{{ locale() }}" :stretch="languages.length > 5 ? true : false" type="card">
        <el-tab-pane v-for="(item, index) in languages" :key="index" :label="item.name" :name="'language-' + item.code">
          <span slot="label" style="padding: 0 8px; font-size: 12px">@{{ item.name }}</span>

          <div class="i18n-inner">
            <textarea class="tinymce" :id="randomNumber + '-' +item.code" :data-code="item.code">@{{ richTextDialog.content[item.code] }}</textarea>
          </div>
        </el-tab-pane>
      </el-tabs>

      <div class="i18n-inner" v-else>
        <textarea class="tinymce" :id="randomNumber + '-' + locale" :data-code="locale">@{{ value[languages[0].code] }}</textarea>
      </div>
      <span slot="footer" class="dialog-footer">
        <el-button @click="richTextDialog.show = false">{{ __('common.cancel') }}</el-button>
        <el-button type="primary" @click="richTextDialogSave">{{ __('common.confirm') }}</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script type="text/javascript">
Vue.component('rich-text-i18n', {
  template: '#rich-text-i18n-template',
  props: {
    value: {
      default: null
    },
  },
  data: function () {
    return {
      richTextDialog: {
        show: false,
        content: {}
      },
      languages: $languages,
      locale: '{{ locale() }}',
    }
  },

  created: function () {
    this.initInternalValues();
  },

  // 计算属性
  computed: {
    randomNumber() {
      return (Math.random() * 1000000).toFixed(0);
    }
  },

  methods: {
    initInternalValues() {
      let internalValues = {};

      this.languages.forEach(e => {
        Vue.set(internalValues, e.code, this.value[e.code] || '');
      })

      this.$emit('input', internalValues);
      this.richTextDialog.content = JSON.parse(JSON.stringify(internalValues));
    },

    richTextDialogChecked(type) {
      if (type == 'open') {
        this.richTextDialog.show = true;

        this.$nextTick(() => {
          this.tinymceInit()
          for (let key in this.value) {
            tinymce.get(this.randomNumber + '-' + key).setContent(this.value[key]);
          }
        });
      }
    },

    richTextDialogSave() {
      this.$emit('input', this.richTextDialog.content);
      this.richTextDialog.show = false;
    },

    tinymceInit() {
      const self = this;

      tinymce.init({
        selector: '.tinymce',
        language: '{{ locale() }}',
        branding: false,
        height: 300,
        plugins: "link lists fullscreen table hr wordcount image imagetools code",
        menubar: "",
        toolbar: "undo redo | toolbarImageButton | lineheight | bold italic underline strikethrough | forecolor backcolor | fontselect fontsizeselect | alignleft aligncenter alignright alignjustify | outdent indent | numlist bullist | formatpainter removeformat | charmap emoticons | preview | template link anchor | code",
        toolbar_items_size: 'small',
        image_caption: true,
        imagetools_toolbar: '',
        toolbar_mode: 'wrap',
        font_formats:
          "微软雅黑='Microsoft YaHei';黑体=黑体;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Georgia=georgia,palatino;Helvetica=helvetica;Times New Roman=times new roman,times;Verdana=verdana,geneva",
        fontsize_formats: "10px 12px 14px 18px 24px 36px 48px 56px 72px 96px",
        lineheight_formats: "1 1.1 1.2 1.3 1.4 1.5 1.7 2.4 3 4",
        relative_urls : true,
        setup: function(ed) {
          ed.ui.registry.addButton('toolbarImageButton', {
            icon: 'image',
            onAction:function() {
              bk.fileManagerIframe(images => {
                if (images.length) {
                  images.forEach(e => {
                    if (e.mime == 'video/mp4') {
                      ed.insertContent(`<video src='${e.path}' controls loop muted class="img-fluid" />`);
                    } else {
                      ed.insertContent(`<img src='${e.path}' class="img-fluid" />`);
                    }
                  });
                }
              })
            }
          });
          ed.on('change', function(e) {
            let code = e.target.targetElm.dataset.code
            self.richTextDialog.content[code] = ed.getContent()
          });
        }
      });
    },
  }
});
</script>

<style>
  .rich-text-i18n-template .el-dialog__wrapper {
    z-index: 1000 !important;
  }

  .rich-text-i18n-template .v-modal {
    z-index: 999 !important;
  }

  .rich-text-i18n-template .el-tabs__nav {
    display: flex;
    /* border-color: #ebecf5; */
  }
  .rich-text-i18n-template .i18n-inner {
    margin-top: 20px;
  }
</style>

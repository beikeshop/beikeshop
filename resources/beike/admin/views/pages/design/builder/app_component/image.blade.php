<template id="module-editor-image100-template">
  <div class="image-edit-wrapper">
    <div class="module-editor-row">内容</div>
    <div class="module-edit-group">
      <div class="module-edit-title">选择图片</div>
      <div class="">
        <div class="pb-images-top">
          <pb-image-selector v-model="form.images[0].image"></pb-image-selector>
          <div class="tag">建议尺寸: 1000 x 480</div>
        </div>
        <link-selector :hide-types="['page_category', 'static']" v-model="form.images[0].link"></link-selector>
      </div>
    </div>
  </div>
</template>

<script type="text/javascript">
Vue.component('module-editor-image100', {
  template: '#module-editor-image100-template',

  props: ['module'],

  data: function () {
    return {
      form: null
    }
  },

  watch: {
    form: {
      handler: function (val) {
        this.$emit('on-changed', val);
      },
      deep: true
    }
  },

  created: function () {
    this.form = JSON.parse(JSON.stringify(this.module));
  },

  methods: {

  }
});
</script>

@push('footer')
  <script>
    // 定义模块的配置项
    app.source.modules.push({
      title: '{{__('admin/app_builder.module_image')}}',
      code: 'image100',
      icon: '&#xe663;',
      content: {
        style: {
          background_color: ''
        },
        images: [
          {
            image: languagesFill('catalog/demo/banner/banner-2-en.png'),
            show: true,
            link: {
              type: 'product',
              value:''
            }
          }
        ]
      }
    })
  </script>
@endpush
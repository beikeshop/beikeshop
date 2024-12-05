<template id="module-editor-img-text-banner-template">
  <div>
    <div class="module-edit-group">
      <div class="module-edit-title">{{ __('admin/builder.modules_select_image') }}</div>
      <div class="pb-images-top">
        <pb-image-selector v-model="module.image" :is-language="false"></pb-image-selector>
        <div class="tag">{{ __('admin/builder.text_suggested_size') }} 1000 x 600</div>
      </div>
      <link-selector v-model="module.link" style="margin-bottom: 10px"></link-selector>

      <div class="module-edit-title">{{ __('admin/builder.text_title') }}</div>
      <text-i18n v-model="module.title" style="margin-bottom: 10px"></text-i18n>

      <div class="module-edit-title">{{ __('admin/builder.text_describe') }}</div>
      <text-i18n v-model="module.description" style="margin-bottom: 10px"></text-i18n>

      <div class="module-edit-title">{{ __('admin/builder.scroll_text_bg') }}</div>
      <el-color-picker v-model="module.bg_color" size="small" class="mb-1"></el-color-picker>
    </div>
  </div>
</template>

<script type="text/javascript">

Vue.component('module-editor-img-text-banner', {
  template: '#module-editor-img-text-banner-template',

  props: ['module'],

  data: function () {
    return {
      // full: true
    }
  },

  watch: {
    module: {
      handler: function (val) {
        this.$emit('on-changed', val);
      },
      deep: true,
    }
  },

  methods: {

  }
});

</script>

@push('footer-script')
  <script>
    register = @json($register);

    // 定义模块的配置项
    register.make = {
      style: {
        background_color: ''
      },
      floor: languagesFill(''),
      bg_color: '',
      image: 'catalog/demo/banner/text-image-banner-1.jpg',
      title: languagesFill(''),
      description: languagesFill(''),
      link: {
        type: 'product',
        value:''
      }
    }

    app.source.modules.push(register)
  </script>
@endpush
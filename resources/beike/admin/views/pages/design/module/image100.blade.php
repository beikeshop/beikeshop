<template id="module-editor-image100-template">
  <div class="image-edit-wrapper">
    <module-size v-model="form.module_size"></module-size>
    <div class="module-edit-group">
      <div class="module-edit-title">{{ __('admin/builder.modules_select_image') }}</div>
      <div class="">
        <div class="pb-images-top">
          <pb-image-selector :is-alt="true"  v-model="form.images[0].image"></pb-image-selector>
          <div class="tag">{{ __('admin/builder.text_suggested_size') }}: 1920 x 500</div>
        </div>
        <link-selector v-model="form.images[0].link"></link-selector>
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

@push('footer-script')
  <script>
    register = @json($register);

    // 定义模块的配置项
    register.make = {
      style: {
        background_color: ''
      },
      floor: languagesFill(''),
      module_size: 'container-fluid',// 窄屏、宽屏、全屏
      images: [
        {
          image: {
            src: languagesFill('https://dummyimage.com/1920x500/eeeeee'),
            alt: languagesFill(''),
          },
          show: true,
          link: {
            type: 'product',
            value:''
          }
        }
      ]
    }

    app.source.modules.push(register)
  </script>
@endpush
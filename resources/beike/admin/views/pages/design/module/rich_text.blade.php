<template id="module-editor-rich-text-template">
  <div class="image-edit-wrapper">
    <module-size v-model="form.module_size"></module-size>

    <div class="module-edit-group">
      <div class="module-edit-title">{{ __('admin/builder.modules_content') }}</div>
      <rich-text-i18n v-model="form.text"></rich-text-i18n>
    </div>
  </div>
</template>

<script type="text/javascript">
Vue.component('module-editor-rich-text', {
  template: '#module-editor-rich-text-template',

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
      text: {}
    }

    app.source.modules.push(register)
  </script>
@endpush

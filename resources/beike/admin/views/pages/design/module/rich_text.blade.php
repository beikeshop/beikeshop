<template id="module-editor-rich-text-template">
  <div class="image-edit-wrapper">
    <div class="module-editor-row">{{ __('admin/builder.text_set_up') }}</div>
    <div class="module-edit-group">
      <div class="module-edit-title">{{ __('admin/builder.modules_content') }}</div>
      <rich-text-i18n v-model="module.text"></rich-text-i18n>
    </div>
  </div>
</template>

<script type="text/javascript">
Vue.component('module-editor-rich-text', {
  template: '#module-editor-rich-text-template',

  props: ['module'],

  data: function () {
    return {
      //
    }
  },

  watch: {
    module: {
      handler: function (val) {
        this.$emit('on-changed', val);
      },
      deep: true
    }
  },

  created: function () {
    //
  },

  methods: {

  }
});
</script>

@push('add-script')
  <script>
    register = @json($register);

    // 定义模块的配置项
    register.make = {
      style: {
        background_color: ''
      },
      floor: languagesFill(''),
      text: {}
    }

    app.source.modules.push(register)
  </script>
@endpush

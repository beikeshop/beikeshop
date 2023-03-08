<template id="module-editor-bestseller-template">
  <div class="image-edit-wrapper">
    <div class="module-editor-row">{{ __('admin/builder.text_set_up') }}</div>
    <div class="module-edit-group">
      <div class="module-edit-title">{{ __('admin/builder.text_module_title') }}</div>
      <text-i18n v-model="module.title"></text-i18n>
    </div>
    <div class="module-edit-group">
      <div class="module-edit-title">{{ __('Bestseller::common.limit') }}</div>
      <el-input type="number" v-model="module.limit" size="small"></el-input>
    </div>
  </div>
</template>

<script type="text/javascript">
Vue.component('module-editor-bestseller', {
  template: '#module-editor-bestseller-template',

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
});
</script>

{{-- 定义模块的配置项 --}}
@push('add-script')
  <script>
    register = @json($register);

    register.make = {
      style: {
        background_color: ''
      },
      title: languagesFill('{{ __('admin/builder.text_module_title') }}'),
      limit: 8,
    }

    app.source.modules.push(register)
  </script>
@endpush


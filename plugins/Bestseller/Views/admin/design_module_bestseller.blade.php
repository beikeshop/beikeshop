<template id="module-editor-bestseller-template">
  <div class="image-edit-wrapper">
    <div class="module-editor-row">{{ __('admin/builder.text_set_up') }}</div>
    <div class="module-edit-group">
      <div class="module-edit-title">{{ __('admin/builder.text_module_title') }}</div>
      <text-i18n v-model="module.title"></text-i18n>
    </div>
    <div class="module-edit-group">
      <div class="module-edit-title">数量限制</div>
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

const register = @json($register);

// 定义模块的配置项
register.make = {
  style: {
    background_color: ''
  },
  title: languagesFill('{{ __('admin/builder.text_module_title') }}'),
  limit: 8,
}

setTimeout(() => {
  app.source.modules.push(register)
}, 100)
</script>

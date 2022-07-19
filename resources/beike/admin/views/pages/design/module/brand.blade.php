<template id="module-editor-brand-template">
  <div class="image-edit-wrapper">
    <div class="module-editor-row">设置</div>
    <div class="module-edit-group">
      <div class="module-edit-title">模块标题</div>
      <text-i18n v-model="module.title"></text-i18n>
    </div>

    <div class="module-editor-row">内容</div>
    <div class="module-edit-group">
      <div class="module-edit-title">选择品牌</div>

    </div>
  </div>
</template>

<script type="text/javascript">
Vue.component('module-editor-brand', {
  template: '#module-editor-brand-template',

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

setTimeout(() => {
  const make = {
    style: {
      background_color: ''
    },
    floor: languagesFill(''),
    full: true,
    title: languagesFill('推荐品牌模块'),
    brands: []
  }

  let register = @json($register);

  register.make = make;
  app.source.modules.push(register)
}, 100)
</script>

<template id="module-editor-image100-template">
  <div class="image-edit-wrapper">
    <div class="module-editor-row">设置</div>
    <div class="module-edit-group">
      <div class="module-edit-title">是否全屏</div>
      <el-switch v-model="module.full"></el-switch>
    </div>
    <div class="module-editor-row">内容</div>
    <div class="module-edit-group">
      <div class="module-edit-title">选择图片</div>
      <div class="">
        <div class="pb-images-top">
          <pb-image-selector v-model="module.images[0].image"></pb-image-selector>
          <div class="tag">建议尺寸: 1920 x 500</div>
        </div>
        <link-selector v-model="module.images[0].link"></link-selector>
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

  let register = @json($register);

  register.make = make;
  app.source.modules.push(register)
}, 100)
</script>

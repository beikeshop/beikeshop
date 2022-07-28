<template id="vue-image">
  <div class="set-product-img wh-70" @click="updateImages">
    <img v-if="value" :src="thumbnail(value)" class="img-fluid">
    <i v-else class="bi bi-plus fs-1 text-muted"></i>
  </div>
</template>

<script type="text/javascript">

Vue.component('vue-image', {
  template: '#vue-image',

  props: ['value'],

  data: function () {
    return {
      //
    }
  },

  methods: {
    updateImages() {
      const self = this;
      layer.open({
        type: 2,
        title: '图片管理器',
        shadeClose: false,
        skin: 'file-manager-box',
        scrollbar: false,
        shade: 0.4,
        area: ['1060px', '680px'],
        content: `${document.querySelector('base').href}/file_manager`,
        success: function(layerInstance, index) {
          var iframeWindow = window[layerInstance.find("iframe")[0]["name"]];
          iframeWindow.callback = (images) => {
            if (images) {
              self.$emit('input', images[0].path);
            }
          }
        }
      });
    },

  }
});

Vue.prototype.thumbnail = function thumbnail(image, width, height) {
  // 判断 image 是否以 http 开头
  if (image.indexOf('http') === 0) {
    return image;
  }

  return '{{ asset('/') }}' + image;
};
</script>

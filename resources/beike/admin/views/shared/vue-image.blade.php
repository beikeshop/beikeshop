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
      bk.fileManagerIframe(images => {
        if (images) {
          self.$emit('input', images[0].path);
        }
      })
    },
  }
});
</script>

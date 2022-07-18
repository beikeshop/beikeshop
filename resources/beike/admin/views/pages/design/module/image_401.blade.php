<template id="module-editor-image-401-template">
  <div class="image-edit-wrapper">
    <div class="module-editor-title">{{ modules_content }}</div>
    <div class="module-edit-group">
      <div class="module-edit-title">{{ modules_select_image }}</div>
      <div class="pb-images-selector" v-for="(item, index) in module.images" :key="index">
        <div class="selector-head" @click="itemShow(index)">
          <div class="left">

            <img :src="thumbnail(item.image[{{ current_language_code() }}], 40, 40)" class="img-responsive">
          </div>

          <div class="right"><i :class="'el-icon-arrow-'+(item.show ? 'up' : 'down')"></i></div>
        </div>
        <div :class="'pb-images-list ' + (item.show ? 'active' : '')">
          <div class="pb-images-top">
            <pb-image-selector v-model="item.image"></pb-image-selector>
            <div class="tag">{{ text_suggested_size }} 1060 x 380</div>
          </div>
          <link-selector v-model="item.link"></link-selector>
        </div>
      </div>
    </div>
  </div>
</template>

<script type="text/javascript">
setTimeout(() => {
  app.source.modules.push(@json($register))
}, 100)
Vue.component('module-editor-image-401', {
  delimiters: ['${', '}'],

  template: '#module-editor-image-401-template',

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
    itemShow(index) {
      this.module.images.find((e, key) => {if (index != key) return e.show = false});
      this.module.images[index].show = !this.module.images[index].show;
    },
  }
});
</script>

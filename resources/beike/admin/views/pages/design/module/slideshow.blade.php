<template id="module-editor-slideshow-template">
  <div>
    <div class="module-editor-row">设置</div>
    <div class="module-edit-group">
      <div class="module-edit-title">是否全屏</div>
      <el-switch v-model="module.full"></el-switch>
    </div>

    <div class="module-editor-row">内容</div>
    <div class="module-edit-group">
      <div class="module-edit-title">选择图片</div>
      <draggable
        ghost-class="dragabble-ghost"
        :list="module.images"
        :options="{animation: 330}"
      >
        <div class="pb-images-selector" v-for="(item, index) in module.images" :key="index">
          <div class="selector-head" @click="itemShow(index)">
            <div class="left">
              <el-tooltip class="icon-rank" effect="dark" content="上下拖动排序" placement="left">
                <i class="el-icon-rank"></i>
              </el-tooltip>

              {{-- <img :src="thumbnail(item.image[{{ helper.current_language_id() }}], 40, 40)" class="img-responsive"> --}}
            </div>

            <div class="right">
              <el-tooltip class="" effect="dark" content="删除" placement="left">
                <div class="remove-item" @click.stop="removeImage(index)"><i class="iconfont">&#xe63a;</i></div>
              </el-tooltip>
              <i :class="'fa fa-angle-'+(item.show ? 'up' : 'down')"></i>
            </div>
          </div>
          <div :class="'pb-images-list ' + (item.show ? 'active' : '')">
            <div class="pb-images-top">
              <pb-image-selector v-model="item.image"></pb-image-selector>
              <div class="tag">建议尺寸 1920 x 600</div>
            </div>
            <link-selector v-model="item.link"></link-selector>
          </div>
        </div>
      </draggable>

      <div class="add-item">
        <el-button type="primary" size="small" @click="addImage" icon="el-icon-circle-plus-outline">添加图片</el-button>
      </div>
    </div>
  </div>
</template>

<script type="text/javascript">

setTimeout(() => {
  app.source.modules.push(@json($register))
}, 0)

Vue.component('module-editor-slideshow', {
  template: '#module-editor-slideshow-template',

  props: ['module'],

  data: function () {
    return {
      // full: true
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
    console.log(this.module)
  },

  methods: {
    removeImage(index) {
      this.module.images.splice(index, 1);
    },
    itemShow(index) {
      this.module.images.find((e, key) => {if (index != key) return e.show = false});
      this.module.images[index].show = !this.module.images[index].show;
    },

    addImage() {
      this.module.images.find(e => e.show = false);
      this.module.images.push({image: 'catalog/demo/slideshow/2.jpg', show: true, link: {type: 'product', value:''}});
    }
  }
});
</script>

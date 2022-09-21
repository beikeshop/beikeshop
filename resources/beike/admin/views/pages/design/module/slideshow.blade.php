<template id="module-editor-slideshow-template">
  <div>
    <div class="module-editor-row">{{ __('admin/builder.text_set_up') }}</div>
    <div class="module-edit-group">
      <div class="module-edit-title">{{ __('admin/builder.modules_full_screen') }}</div>
      <el-switch v-model="module.full"></el-switch>
    </div>

    <div class="module-editor-row">{{ __('admin/builder.modules_content') }}</div>
    <div class="module-edit-group">
      <div class="module-edit-title">{{ __('admin/builder.modules_select_image') }}</div>
      <draggable
        ghost-class="dragabble-ghost"
        :list="module.images"
        :options="{animation: 330, handle: '.icon-rank'}"
      >
        <div class="pb-images-selector" v-for="(item, index) in module.images" :key="index">
          <div class="selector-head" @click="itemShow(index)">
            <div class="left">
              <el-tooltip class="icon-rank" effect="dark" content="{{ __('admin/builder.text_drag_sort') }}" placement="left">
                <i class="el-icon-rank"></i>
              </el-tooltip>

              <img :src="thumbnail(item.image['{{ locale() }}'], 40, 40)" class="img-responsive">
            </div>

            <div class="right">
              <el-tooltip class="" effect="dark" content="{{ __('admin/builder.text_delete') }}" placement="left">
                <div class="remove-item" @click.stop="removeImage(index)"><i class="el-icon-delete"></i></div>
              </el-tooltip>
              <i :class="'el-icon-arrow-'+(item.show ? 'up' : 'down')"></i>
            </div>
          </div>
          <div :class="'pb-images-list ' + (item.show ? 'active' : '')">
            <div class="pb-images-top">
              <pb-image-selector v-model="item.image"></pb-image-selector>
              <div class="tag">{{ __('admin/builder.text_suggested_size') }} 1920 x 600</div>
            </div>
            <link-selector v-model="item.link"></link-selector>
          </div>
        </div>
      </draggable>

      <div class="add-item">
        <el-button type="primary" size="small" @click="addImage" icon="el-icon-circle-plus-outline">{{ __('admin/builder.text_add_pictures') }}</el-button>
      </div>
    </div>
  </div>
</template>

<script type="text/javascript">

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
        // console.log(previewWindow)
        // $(previewWindow.document).find('.product-description h1').css('font-size', newValue);
        this.$emit('on-changed', val);
      },
      deep: true,
    }
  },

  created: function () {
    // console.log(this.module)
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
      this.module.images.push({image: languagesFill('/demo/banner.png'), show: true, link: {type: 'product', value:''}});
    }
  }
});

setTimeout(() => {
  const make = {
    style: {
      background_color: ''
    },
    full: true,
    floor: languagesFill(''),
    images: [
      {
        image: languagesFill('catalog/demo/banner/banner-4-en.jpg'),
        show: true,
        link: {
          type: 'product',
          value:''
        }
      },
      {
        image: languagesFill('catalog/demo/banner/banner-3-en.jpg'),
        show: false,
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

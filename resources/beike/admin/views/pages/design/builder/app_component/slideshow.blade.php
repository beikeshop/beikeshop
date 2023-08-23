<template id="module-editor-slideshow-template">
  <div>
    <div class="module-editor-row">{{ __('admin/builder.modules_content') }}</div>
    <div class="module-edit-group">
      <div class="module-edit-title">{{ __('admin/builder.modules_select_image') }}</div>
      <draggable
        ghost-class="dragabble-ghost"
        :list="form.images"
        :options="{animation: 330, handle: '.icon-rank'}"
      >
        <div class="pb-images-selector" v-for="(item, index) in form.images" :key="index">
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
              <div class="tag">{{ __('admin/builder.text_suggested_size') }} 1000 x 500</div>
            </div>
            <link-selector :hide-types="['page_category', 'static']" v-model="item.link"></link-selector>
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
      form: null
    }
  },

  watch: {
    form: {
      handler: function (val) {
        this.$emit('on-changed', val);
      },
      deep: true,
    }
  },

  created: function () {
    this.form = JSON.parse(JSON.stringify(this.module));
  },

  methods: {
    removeImage(index) {
      this.form.images.splice(index, 1);
    },

    itemShow(index) {
      this.form.images.find((e, key) => {if (index != key) return e.show = false});
      this.form.images[index].show = !this.form.images[index].show;
    },

    addImage() {
      this.form.images.find(e => e.show = false);
      this.form.images.push({image: languagesFill('catalog/demo/banner/banner-4-en.jpg'), show: true, link: {type: 'product', value:''}});
    }
  }
});

</script>

@push('footer')
  <script>
    // 定义模块的配置项
    app.source.modules.push({
      title: '{{__('admin/app_builder.module_slideshow')}}',
      code: 'slideshow',
      icon: '&#xe663;',
      content: {
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
    })
  </script>
@endpush
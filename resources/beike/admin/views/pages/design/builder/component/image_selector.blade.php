  <template id="pb-image-selector">
    <div class="pb-image-selector">
      <el-tabs v-if="isLanguage" @tab-click="tabClick" value="language-{{ current_language_id() }}" :stretch="languages.length > 5 ? true : false" type="card" :class="languages.length <= 1 ? 'languages-a' : ''">
        <el-tab-pane v-for="(item, index) in languages" :key="index" :label="item.name" :name="'language-' + item.id">
          <span slot="label" style="padding: 0 4px; font-size: 12px">@{{ item.name }}</span>

          <div class="i18n-inner">
            <div class="img">
              {{-- <img :src="type == 'image' ? thumbnail(value[item.id]) : 'image/video.png'" :id="'thumb-' + id" @click="selectButtonClicked"> --}}
              <el-image :src="type == 'image' ? thumbnail(value[item.id]) : 'image/video.png'" :id="'thumb-' + id" @click="selectButtonClicked">
                <div slot="error" class="image-slot">
                  <i class="el-icon-picture-outline"></i>
                </div>
              </el-image>
            </div>
            <div class="btns">
              <el-button type="primary" size="mini" plain @click="selectButtonClicked">选择</el-button>
              <el-button size="mini" plain style="margin-left: 4px;" @click="removeImage">删除</el-button>
            </div>
            <input type="hidden" value="" v-model="src" :id="'input-' + id">
          </div>
        </el-tab-pane>
      </el-tabs>

      <div class="i18n-inner" v-else>
        <img :src="type == 'image' ? thumbnail(value) : 'image/video.png'" :id="'thumb-' + id" @click="selectButtonClicked" style="max-width: 60px; cursor: pointer;border: 1px solid #eee;">
        <div class="btns">
          <el-button type="primary" size="mini" plain @click="selectButtonClicked">选择</el-button>
          <el-button size="mini" plain style="margin-left: 4px;" @click="removeImage">删除</el-button>
        </div>
        <input type="hidden" value="" v-model="src" :id="'input-' + id">
      </div>
    </div>
  </template>

  <script type="text/javascript">
    Vue.component('pb-image-selector', {
      template: '#pb-image-selector',

      props: {
        value: {
          default: null
        },
        type: {
          default: 'image'
        },
        isLanguage: { // 是否需要多语言配置
          default: true
        },
      },

      data: function () {
        return {
          tabActiveId: $language_id,
          languages: $languages,
          internalValues: {},
          id: 'image-selector-'+ randomString(4),
          loading: null
        }
      },

      created: function () {
        if (this.isLanguage) {
          this.languages.forEach(e => {
            let value = this.value;
            if (typeof(this.value) == 'object') {
              value = this.value[e.id];
            }

            Vue.set(this.internalValues, e.id, value || '');
          })
          this.$emit('input', this.internalValues);
        }
      },

      computed: {
        src: {
          get() {
            return this.value;
          },
          set(newValue) {
            this.$emit('input', newValue);
          }
        }
      },

      methods: {
        removeImage() {
          this.src = '';
        },

        tabClick(e) {
          this.tabActiveId = this.languages[e.index * 1].id;
        },

        selectButtonClicked() {
          let that = this;
          this.loading = true;

          layer.open({
            type: 2,
            title: '图片管理器',
            shadeClose: false,
            skin: 'file-manager-box',
            scrollbar: false,
            shade: 0.8,
            area: ['80%', '80%'],
            content: '/{{ admin_name() }}/file_manager',
            success: function(layerInstance, index) {
              var iframeWindow = window[layerInstance.find("iframe")[0]["name"]];
              iframeWindow.callback = function(images) {
                // if (images.length < 1) {
                //   return;
                // }
                that.loading = false;

                if (that.isLanguage) {
                  that.src[that.tabActiveId] = images.path;
                } else {
                  that.src = images.path;
                }
                // console.log(that.src);
              }
            }
          });
        }
      }
    });
  </script>

  <style scoped>
    .pb-image-selector {
    }

    .languages-a .el-tabs__header {
      display: none;
    }

    .pb-image-selector .btns {
      margin-left: 10px;
    }

    .pb-image-selector .btns .el-button {
      padding: 7px 10px;
    }


    .pb-image-selector .el-tabs__nav {
      display: flex;
      border-color: #ebecf5;
    }

    .pb-image-selector .el-tabs__nav > div {
      background: #ebecf5;
      border-left: 1px solid #d7dbf7 !important;
      padding: 0 !important;
      flex: 1;
      height: 30px;
      line-height: 30px;
      min-width: 50px;
      text-align: center;
    }

    .pb-image-selector .el-tabs__nav > div:first-of-type {
      border-left: none !important;
    }

    .pb-image-selector .el-tabs__nav > div.is-active {
      background: #fff !important;
    }

    .pb-image-selector .i18n-inner {
      /*margin-top: 5px;*/
      display: flex;
      align-items: center;
      background: whitesmoke;
      padding: 5px;
      border-radius: 4px;
    }

    .pb-image-selector .i18n-inner .img {
      width: 46px;
      height: 46px;
      border: 1px solid #eee;
      padding: 2px;
      background: #fff;
    }

    .pb-image-selector .i18n-inner .img img {
      max-width: 100%;
      height: auto;
    }
  </style>
  <template id="pb-image-selector">
    <div class="pb-image-selector">
      <el-tabs v-if="isLanguage" @tab-click="tabClick" value="language-{{ locale() }}" :stretch="languages.length > 5 ? true : false" type="card" :class="languages.length <= 1 ? 'languages-a' : ''">
        <el-tab-pane v-for="(item, index) in languages" :key="index" :label="item.name" :name="'language-' + item.code">
          <span slot="label" style="padding: 0 4px; font-size: 12px">@{{ item.name }}</span>

          <div class="i18n-inner">
            <div class="img">
              {{-- <img :src="type == 'image' ? thumbnail(value[item.code]) : 'image/video.png'" :id="'thumb-' + id" @click="selectButtonClicked"> --}}
              <el-image :src="type == 'image' ? thumbnail(value[item.code]) : 'image/video.png'" :id="'thumb-' + id" @click="selectButtonClicked">
                <div slot="error" class="image-slot">
                  <i class="el-icon-picture-outline"></i>
                </div>
              </el-image>
            </div>
            <div class="btns">
              <el-button type="primary" size="mini" plain @click="selectButtonClicked">{{ __('admin/builder.modules_choose') }}</el-button>
              <el-button size="mini" plain style="margin-left: 4px;" @click="removeImage">{{ __('admin/builder.text_delete') }}</el-button>
            </div>
            <input type="hidden" value="" v-model="src" :id="'input-' + id">
          </div>
        </el-tab-pane>
      </el-tabs>

      <div class="i18n-inner" v-else>
        <div class="img">
          <el-image :src="type == 'image' ? thumbnail(value) : 'image/video.png'" :id="'thumb-' + id" @click="selectButtonClicked">
            <div slot="error" class="image-slot">
              <i class="el-icon-picture-outline"></i>
            </div>
          </el-image>
        </div>

        <div class="btns">
          <el-button type="primary" size="mini" plain @click="selectButtonClicked">{{ __('admin/builder.modules_choose') }}</el-button>
          <el-button size="mini" plain style="margin-left: 4px;" @click="removeImage">{{ __('admin/builder.text_delete') }}</el-button>
        </div>
        <input type="hidden" value="" v-model="src">
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
          tabActiveId: '{{ locale() }}',
          languages: $languages,
          internalValues: {},
          id: 'image-selector-'+ bk.randomString(4),
          loading: null
        }
      },

      created: function () {
        if (this.isLanguage) {
          this.languages.forEach(e => {
            let value = this.value;
            if (typeof(this.value) == 'object') {
              value = this.value[e.code];
            }

            Vue.set(this.internalValues, e.code, value || '');
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
          if (this.isLanguage) {
            this.src[this.tabActiveId] = '';
          } else {
            this.src = '';
          }
        },

        tabClick(e) {
          this.tabActiveId = this.languages[e.index * 1].code;
        },

        selectButtonClicked() {
          this.loading = true;

          bk.fileManagerIframe(images => {
            this.loading = false;

            if (this.isLanguage) {
              this.src[this.tabActiveId] = images[0].path;
            } else {
              this.src = images[0].path;
            }
          })
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
      margin-top: 5px;
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
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
    }

    .image-slot {
      font-size: 26px;
      color: #939ab3;
    }


    .pb-image-selector .i18n-inner .img img {
      max-width: 100%;
      height: auto;
    }

    .pb-image-selector .el-tabs__header {
      margin-bottom: 0;
    }
  </style>

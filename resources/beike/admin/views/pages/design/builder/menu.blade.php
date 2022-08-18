@extends('admin::layouts.master')

@section('title', '编辑导航菜单')

@push('header')
  <script src="{{ asset('vendor/vue/Sortable.min.js') }}"></script>
  <script src="{{ asset('vendor/vue/vuedraggable.js') }}"></script>
  <link rel="stylesheet" type="text/css" href="{{ asset('/build/beike/admin/css/design.css') }}">
@endpush

@section('page-title-right')
  <button type="button" class="btn btn-primary save-btn">保存</button>
@endsection

@section('content')
  <div class="card" id="app" v-cloak>
    <div class="card-body h-min-600 position-relative">
      <div class="design-wrap d-flex">
        <div class="left" style="width: 220px">
          <p class="fw-bold mb-2">主菜单</p>
          {{-- <div class="menus-wrap" v-if="form.menus.length"> --}}
          <draggable class="menus-wrap" v-if="form.menus.length" :list="form.menus"
            :options="{ animation: 330, handle: '.el-icon-rank' }">
            <div
              :class="['border px-2 py-3 mb-2 ', currentMenuIndex == index ?
                  'bg-primary bg-opacity-10' : ''
              ]"
              @click="currentMenuIndex = index" v-for="menu, index in form.menus" :key="index">
              <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center flex-grow-1">
                  <el-tooltip class="icon-rank cursor-scroll" effect="dark" content="拖动排序" placement="left">
                    <i class="el-icon-rank"></i>
                  </el-tooltip>
                  <div class="name ms-2">@{{ menu.name[source.locale] || '请添加数据' }}</div>
                  {{-- <link-selector :is-custom-name="true" :is-title="false" v-model="menu.link"></link-selector> --}}
                </div>
                <div>
                  <div class="remove-item" @click="removeLink(index)"><i class="el-icon-delete"></i></div>
                </div>
              </div>
            </div>
          </draggable>
          {{-- </div> --}}
          <button @click="addLinkClicked" class="btn btn-outline-primary mt-3">添加主菜单</button>
        </div>
        <div class="vr position-absolute bg-secondary" style="height: 90%; left: 260px"></div>
        <div class="flex-1 right ps-5" v-if="currentMenu" :key="currentMenuIndex">
          <div class="d-flex">
            <div class="wp-200 mb-3">
              <div class="mb-2 fw-bold">主菜单名称/链接</div>
              <text-i18n v-model="currentMenu.name" class="mb-2"></text-i18n>
              {{-- <input type="text" v-model="currentMenu.name['zh_cn']"> --}}
              <link-selector :is-title="false" style="border-color: #c0c4cc" v-model="currentMenu.link">
              </link-selector>
            </div>
            {{-- <div>
              <div class="mb-2 fw-bold">子菜单是否为</div>
              <el-switch v-model="currentMenu.isChildren" active-color="#13ce66" inactive-color="#ff4949"></el-switch>
            </div> --}}
            <div class="wp-200 ms-5">
              <div class="mb-2 fw-bold">主菜单标签</div>
              <text-i18n v-model="currentMenu.badge.name" class="mb-3"></text-i18n>
            </div>

            <div class="wp-100 ms-5">
              <div class="mb-2 fw-bold">标签背景色</div>
              <el-color-picker v-model="currentMenu.badge.bg_color"></el-color-picker>
            </div>

            <div class="wp-100">
              <div class="mb-2 fw-bold">标签文字色</div>
              <el-color-picker v-model="currentMenu.badge.text_color"></el-color-picker>
            </div>
          </div>

          {{-- <hr class="bg-secondary"> --}}
          <div class="menu-children-group">
            <div class="d-flex align-items-center border p-2 bg-light mb-3">
              <span class="fw-bold">子菜单 (组)</span>
              <div class="vr lh-1 mx-3 bg-secondary " style="height: 18px;"></div>
              <button class="btn btn-sm btn-link p-0" @click="addChildrenGroup"
                :disabled="currentMenu.childrenGroup.length >= 5">添加菜单组</button>
              <div class="vr mx-3 lh-1 bg-secondary " style="height: 18px;"></div>
              <div>
                {{-- <div class="mb-2 fw-bold">是否全屏</div> --}}
                <span class="me-2">是否全屏</span>
                <el-switch v-model="currentMenu.isFull"></el-switch>
              </div>
            </div>
            <draggable class="children-item d-flex" style="margin: 0 -0.5rem" :list="currentMenu.childrenGroup"
              :options="{ animation: 330, handle: '.el-icon-rank' }">
              <div class="w-25 card border mx-2 mb-3" v-for="group, group_index in currentMenu.childrenGroup"
                :key="group_index">
                <div class="card-header d-flex align-items-center justify-content-between mb-2">
                  <div class=""><i class="el-icon-rank cursor-scroll"></i> 菜单 - @{{ group_index + 1 }}</div>
                  <div class="d-flex">
                    <div class="cursor-pointer me-2" @click="settingChildrenGroup(group_index)"><i class="bi bi-gear"></i></div>
                    <div class="remove-item cursor-pointer" @click="removeChildrenGroup(group_index)"><i
                        class="el-icon-delete"></i></div>
                  </div>
                </div>
                <div class="card-body p-2">
                  <text-i18n v-model="group.name" class="mb-3"></text-i18n>
                  <div class="group-children">
                    <div v-if="group.type == 'image'">
                      <pb-image-selector v-model="group.image.image" class="mb-2"></pb-image-selector>
                      <link-selector v-model="group.image.link"></link-selector>
                    </div>
                    <template v-else>
                      <draggable ghost-class="dragabble-ghost" :list="group.children"
                        :options="{ animation: 330, handle: '.el-icon-rank' }">
                        <div class="children-item mb-2" v-for="children, children_index in group.children"
                          :key="children_index">
                          <div class="d-flex align-items-center justify-content-between">
                            <i class="el-icon-rank cursor-scroll"></i>
                            <link-selector :is-title="false" :is-custom-name="true" v-model="children.link">
                            </link-selector>
                            <div class="remove-item cursor-pointer" @click="removeChildren(group_index, children_index)"><i
                                class="el-icon-delete"></i></div>
                          </div>
                        </div>
                      </draggable>
                      <button @click="addChildrenLink(group_index)" class="btn btn-link btn-sm mt-2">添加链接</button>
                    </template>
                  </div>
                </div>
              </div>
            </draggable>
          </div>
        </div>
      </div>
    </div>

    <el-dialog title="设置" :visible.sync="childrenGroupPop.show" width="500px" v-if="currentMenu && currentMenu.childrenGroup.length">
      <p class="fw-bold mb-2">类型</p>
      <el-select v-model="currentMenu.childrenGroup[childrenGroupPop.groupIndex].type" placeholder="请选择">
        <el-option
          v-for="type in source.types"
          :key="type.value"
          :label="type.name"
          :value="type.value">
        </el-option>
      </el-select>
      <span slot="footer" class="dialog-footer">
        <el-button @click="childrenGroupPop.show = false">取 消</el-button>
        <el-button type="primary" @click="childrenGroupPop.show = false">确 定</el-button>
      </span>
    </el-dialog>
  </div>
@endsection

@push('footer')
  <script>
    var $languages = @json($admin_languages);
  </script>

  @include('admin::pages.design.builder.component.image_selector')
  @include('admin::pages.design.builder.component.link_selector')
  @include('admin::pages.design.builder.component.text_i18n')
  @include('admin::pages.design.builder.component.rich_text_i18n')

  <script>
    let app = new Vue({
      el: '#app',
      data: {
        form: @json($design_settings),
        currentMenuIndex: 0,
        childrenGroupPop: {
          show: false,
          groupIndex: 0,
        },
        source: {
          locale: '{{ locale() }}',
          types: [{
            name: '链接',
            value: 'link'
          },{
            name: '图片',
            value: 'image'
          }],
        },
      },

      computed: {
        // 当前正在编辑的菜单
        currentMenu: function() {
          // 强制刷新视图
          this.$forceUpdate();
          return this.form.menus[this.currentMenuIndex] || null;
        },
      },

      watch: {
        // 深度监听菜单数据变化
        currentMenu: {
          handler: function(val) {
            // 强制刷新
            this.$forceUpdate();
            // this.form.menus[this.currentMenuIndex] = this.currentMenu
          },
          deep: true,
          immediate: true,
        },
        // currentMenu: function() {
        //   console.log(222);
        //   this.form.menus[this.currentMenuIndex] = this.currentMenu
        // },
      },

      methods: {
        addLinkClicked(index) {
          this.form.menus.push({
            isFull: false,
            badge: {
              isShow: false,
              name: {},
              bg_color: '#fd560f',
              text_color: '#ffffff',
            },
            link: {
              type: 'page',
              value: '',
              text: {}
            },
            name: {},
            isChildren: false,
            childrenGroup: [],
          })

          this.currentMenuIndex = this.form.menus.length - 1;
        },

        addChildrenGroup() {
          this.currentMenu.childrenGroup.push({
            name: {},
            type: 'link',
            image: {
              image: {},
              link: {
                type: 'product',
                value: '',
                text: {}
              },
            },
            children: [],
          })
        },

        addChildrenLink(group_index) {
          this.currentMenu.childrenGroup[group_index].children.push({
            name: {},
            link: {
              type: 'page',
              value: '',
              text: {}
            },
          })
        },

        removeChildrenGroup(index) {
          this.currentMenu.childrenGroup.splice(index, 1)
        },

        removeLink(item, index) {
          this.form.menus.splice(index, 1);
        },

        removeChildren(group_index, children_index) {
          this.currentMenu.childrenGroup[group_index].children.splice(children_index, 1)
        },

        settingChildrenGroup(group_index) {
          this.childrenGroupPop.show = true;
          this.childrenGroupPop.groupIndex = group_index;
        },

        saveButtonClicked() {
          $http.put('design_menu/builder', this.form).then((res) => {
            layer.msg(res.message)
          })
        },
      },
      created() {},
      mounted() {},
    })

    let saveBtn = document.querySelector('.save-btn')
    saveBtn.addEventListener('click', () => {
      app.saveButtonClicked()
    })
  </script>
@endpush

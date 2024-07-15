@extends('admin::layouts.master')

@section('title', __('admin/builder.text_to_menu'))

@push('header')
  <script src="{{ asset('vendor/vue/Sortable.min.js') }}"></script>
  <script src="{{ asset('vendor/vue/vuedraggable.js') }}"></script>
  <link rel="stylesheet" type="text/css" href="{{ asset('/build/beike/admin/css/design.css') }}">
@endpush

@section('page-title-right')
  <button type="button" class="btn w-min-100 btn-primary save-btn">{{ __('common.save') }}</button>
@endsection

@section('content')
  <div class="card" id="app" v-cloak>
    <div class="card-body h-min-600 position-relative">
      <div class="design-wrap">
        <p class="fw-bold mb-2">{{ __('admin/builder.main_menu') }}</p>
        <div class="left d-block d-lg-flex">
          {{-- <div class="menus-wrap" v-if="form.menus.length"> --}}
          <draggable class="menus-wrap d-block d-lg-flex mb-2 mb-lg-0" v-if="form.menus.length" :list="form.menus"
            :options="{ animation: 330, handle: '.el-icon-rank' }">
            <div
              :class="['p-2 me-2', currentMenuIndex == index ? 'active' : '']"
              @click="currentMenuIndex = index" v-for="menu, index in form.menus" :key="index">
              <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center flex-grow-1">
                  <el-tooltip class="icon-rank cursor-scroll" effect="dark" content="{{ __('admin/builder.text_drag_sort') }}" placement="top">
                    <i class="el-icon-rank"></i>
                  </el-tooltip>
                  <div class="name mx-2">
                    <template v-if="menu.name[source.locale]">@{{ menu.name[source.locale] }}</template>
                    <template v-else>{{ __('admin/builder.please_add_data') }}</template>
                  </div>
                  {{-- <link-selector :is-custom-name="true" :is-title="false" v-model="menu.link"></link-selector> --}}
                </div>
                <div>
                  <div class="remove-item" @click="removeLink(index)"><i class="el-icon-delete"></i></div>
                </div>
              </div>
            </div>
          </draggable>
          {{-- </div> --}}
          <button @click="addLinkClicked" class="btn btn-outline-primary ms-lg-3">{{ __('admin/builder.add_main_menu') }}</button>
        </div>
        <div class="flex-1 right" v-if="currentMenu" :key="currentMenuIndex">
          <div class="d-lg-flex">
            <div class="wp-200 ">
              <div class="mb-2">{{ __('admin/builder.main_menu_name_link') }}</div>
              <text-i18n v-model="currentMenu.name" class="mb-2"></text-i18n>
              {{-- <input type="text" v-model="currentMenu.name['zh_cn']"> --}}
              <link-selector :is-title="false" style="border-color: #c0c4cc" v-model="currentMenu.link">
              </link-selector>
            </div>

            <div class="wp-200 ms-lg-5">
              <div class="mb-2 mt-3 mt-lg-0">{{ __('admin/builder.main_menu_label') }}</div>
              <text-i18n v-model="currentMenu.badge.name" class=""></text-i18n>
            </div>

            <div class="wp-200 ms-lg-5">
              <div class="mb-2 mt-3 mt-lg-0">{{ __('admin/builder.label_background_color') }}</div>
              <el-color-picker v-model="currentMenu.badge.bg_color" size="small"></el-color-picker>
            </div>

            <div class="wp-200">
              <div class="mb-2 mt-3 mt-lg-0">{{ __('admin/builder.label_text_color') }}</div>
              <el-color-picker v-model="currentMenu.badge.text_color" size="small"></el-color-picker>
            </div>
          </div>

          <hr class="bg-secondary bg-opacity-50">

          <div class="children-group-wrap">
            <div class="d-flex align-items-center mb-3">
              <span class="fw-bold">{{ __('admin/builder.submenu_group') }}</span>
              <div class="vr lh-1 mx-3 bg-secondary " style="height: 18px;"></div>
              <button class="btn btn-sm btn-link p-0" @click="addChildrenGroup"
                :disabled="currentMenu.childrenGroup.length >= 5">{{ __('admin/builder.add_menu_group') }}</button>
              <div class="vr mx-3 lh-1 bg-secondary " style="height: 18px;"></div>
              <div>
                <span class="me-2">{{ __('admin/builder.full_screen') }}</span>
                <el-switch v-model="currentMenu.isFull"></el-switch>
              </div>
            </div>
            <draggable class="children-group d-lg-flex flex-wrap" style="margin: 0 -0.5rem" :list="currentMenu.childrenGroup"
              :options="{ animation: 330, handle: '.el-icon-rank' }">
              <div class="card border mx-2 mb-3 group-item" v-for="group, group_index in currentMenu.childrenGroup"
                :key="group_index">
                <div class="card-header d-flex align-items-center justify-content-between mb-2">
                  <div class="" style="font-weight: 400">
                    <i class="el-icon-rank cursor-scroll"></i> {{ __('admin/builder.menu') }} - @{{ group_index + 1 }}
                    (@{{ groupTypeName(group.type) }})
                  </div>
                  <div class="d-flex">
                    <div class="cursor-pointer me-2" @click="settingChildrenGroup(group_index)"><i class="bi bi-gear"></i></div>
                    <div class="remove-item cursor-pointer" @click="removeChildrenGroup(group_index)"><i
                        class="el-icon-delete"></i></div>
                  </div>
                </div>
                <div class="card-body p-2">
                  <text-i18n v-model="group.name" class="mb-3"></text-i18n>
                  <div class="group-children-info">
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
                      <button @click="addChildrenLink(group_index)" class="btn btn-link btn-sm mt-2">{{ __('admin/builder.add_submenu_link') }}</button>
                    </template>
                  </div>
                </div>
              </div>
            </draggable>
          </div>
        </div>
      </div>
    </div>

    <el-dialog title="{{ __('admin/builder.text_set_up') }}" :visible.sync="childrenGroupPop.show" width="500px" v-if="currentMenu && currentMenu.childrenGroup.length">
      <p class="fw-bold mb-2">{{ __('admin/builder.type') }}</p>
      <el-select v-model="currentMenu.childrenGroup[childrenGroupPop.groupIndex].type" placeholder="">
        <el-option
          v-for="type in source.types"
          :key="type.value"
          :label="type.name"
          :value="type.value">
        </el-option>
      </el-select>
      <span slot="footer" class="dialog-footer">
        <el-button @click="childrenGroupPop.show = false">{{ __('common.cancel') }}</el-button>
        <el-button type="primary" @click="childrenGroupPop.show = false">{{ __('common.confirm') }}</el-button>
      </span>
    </el-dialog>
  </div>
@endsection

@push('footer')
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
            name: '{{ __('admin/builder.modules_link') }}',
            value: 'link'
          },{
            name: '{{ __('admin/builder.text_image') }}',
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

        groupTypeName(value) {
          return this.source.types.find(e => e.value == (value || 'link')).name
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

        removeLink(index) {
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

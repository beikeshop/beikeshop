<!DOCTYPE html>
<html lang="{{ locale() }}">

<head>
  <meta charset="UTF-8">
  <base href="{{ $admin_base_url }}">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="asset" content="{{ asset('/') }}">
  <script src="{{ asset('vendor/vue/2.7/vue.js') }}"></script>
  <script src="{{ asset('vendor/element-ui/index.js') }}"></script>
  <script src="{{ asset('vendor/cookie/js.cookie.min.js') }}"></script>
  <script src="{{ asset('vendor/jquery/jquery-3.6.0.min.js') }}"></script>
  <script src="{{ asset('vendor/layer/3.5.1/layer.js') }}"></script>
  <script src="{{ asset('vendor/vue/batch_select.js') }}"></script>
  <link href="{{ mix('/build/beike/admin/css/bootstrap.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('vendor/element-ui/index-blue.css') }}">
  <link href="{{ mix('build/beike/admin/css/filemanager.css') }}" rel="stylesheet">
  <script src="{{ mix('build/beike/admin/js/app.js') }}"></script>
  <title>beike filemanager</title>
  <script>
    // 获取 iframe 父级 html 标签的 lang 属性
    const htmlLang = parent.document.getElementsByTagName('html')[0].getAttribute('lang');

    if (htmlLang != 'zh_cn') {
      const js = document.createElement('script');
      js.src = `vendor/element-ui/language/${htmlLang}.js`;
      document.head.appendChild(js);
      js.onload = () => {
        ELEMENT.locale(ELEMENT.lang[htmlLang])
      }
    }

    const lang = {
      file_manager: '{{ __('admin/file_manager.file_manager') }}',
    }

    const config = {
      beike_version: '{{ config('beike.version') }}',
      api_url: '{{ beike_api_url() }}',
      app_url: '{{ config('app.url') }}',
    }
  </script>
</head>

<body class="page-filemanager">
  <div class="filemanager-wrap" id="filemanager-wrap-app" v-cloak ref="splitPane">
    <div class="select-tip"></div>
    @if (!is_mobile())
    <div class="filemanager-navbar" :style="'width:' + paneLengthValue">
      <el-tree
        :props="defaultProps"
        node-key="path"
        :data="treeData"
        :current-node-key="folderCurrent"
        :default-expanded-keys="defaultkeyarr"
        :expand-on-click-node="false"
        highlight-current ref="tree"
        draggable
        :allow-drop="allowDrop"
        @node-drag-start="handleDragStart"
        @node-drop="handleDrop"
        @node-click="handleNodeClick"
        @node-expand="(node) => {updateDefaultExpandedKeys(node, 'expand')}"
        @node-collapse="(node) => {updateDefaultExpandedKeys(node, 'collapse')}" class="tree-wrap">
        <div class="custom-tree-node" slot-scope="{ node, data }" :path="data.path" :name="data.name">
          <div class="folder-name" :path="data.path" :name="data.name">@{{ node.label }}</div>
          {{-- v-if="node.isCurrent" --}}
          <div class="right">
            <el-tooltip class="item file-download" effect="dark" content="{{ __('admin/file_manager.download') }}"
            placement="top">
            <span @click.stop="() => {openInputBox('download', node, data)}"><i
              class="el-icon-download"></i></span>
            </el-tooltip>

            <el-tooltip class="item" effect="dark" content="{{ __('admin/file_manager.create_folder') }}"
              placement="top">
              <span @click.stop="() => {openInputBox('addFolder', node, data)}"><i
                  class="el-icon-circle-plus-outline"></i></span>
            </el-tooltip>

            <el-tooltip class="item" effect="dark" content="{{ __('admin/file_manager.rename') }}" placement="top">
              <span v-if="node.level != 1" @click.stop="() => {openInputBox('renameFolder', node, data)}"><i
                  class="el-icon-edit"></i></span>
            </el-tooltip>

            <el-tooltip class="item" effect="dark" content="{{ __('common.delete') }}" placement="top">
              <span v-if="node.level != 1" @click.stop="() => {deleteFolder(node, data)}"><i
                  class="el-icon-delete"></i></span>
            </el-tooltip>
          </div>
        </div>
      </el-tree>
    </div>
    <div class="filemanager-divider" @mousedown="handleMouseDown"></div>
    <div class="filemanager-content" v-loading="loading" element-loading-background="rgba(255, 255, 255, 0.5)">
      <div class="content-head">
        <div class="left d-lg-flex">
          <el-button class="me-4 mb-1 mb-lg-0" size="small" icon="el-icon-check" type="primary" @click="fileChecked"
            :disabled="!!!selectImageIndex.length">{{ __('admin/builder.modules_choose') }}</el-button>
          <el-link :underline="false" :disabled="!!!selectImageIndex.length" icon="el-icon-view" @click="viewImages">{{
            __('common.view') }}</el-link>
          <el-link :underline="false" :disabled="!!!selectImageIndex.length" icon="el-icon-document-copy" @click="copyLink">{{
            __('admin/file_manager.copy_link') }}</el-link>
          <el-link :underline="false" :disabled="!!!selectImageIndex.length" @click="deleteFile" icon="el-icon-delete">
            {{ __('common.delete') }}</el-link>
          <el-link :underline="false" :disabled="selectImageIndex.length == 1 ? false : true"
            @click="openInputBox('image')" icon="el-icon-edit">{{ __('admin/file_manager.rename') }}</el-link>
          <el-link v-if="isMultiple" :underline="false" :disabled="!!!images.length && !!!selectImageIndex.length" @click="selectAll()"
            icon="el-icon-finished">{{ __('common.select_all') }}</el-link>
          @hook('admin.file_manager.content.head.btns.after')
        </div>
        <div class="right">
          @hook('admin.file_manager.content_head.right')
          <el-popover placement="bottom" width="360" class="me-1" trigger="manual" v-model="filterKeywordVisible" ref="keyword-popover">
            <div class="d-flex keyword-popover-area">
              <el-input placeholder="{{ __('common.input') }}" v-model="filterKeyword" class="input-with-select" @keyup.enter.native="searchFile">
                <el-button slot="append" icon="el-icon-search" @click="searchFile"></el-button>
              </el-input>
            </div>
            <el-button slot="reference" size="mini" plain type="primary" class="keyword-popover-area" icon="el-icon-search" @click="filterKeywordVisible = !filterKeywordVisible"></el-button>
          </el-popover>
          <el-popover placement="bottom" width="260" class="me-1" trigger="click">
            <div class="text-center mb-3 fw-bold">{{ __('admin/file_manager.file_sorting') }}</div>
            <div class="mb-3">
              <div class="mb-2">{{ __('admin/file_manager.text_type') }}</div>
              <el-radio-group v-model="filter.sort" size="small">
                <el-radio-button label="created">{{ __('admin/file_manager.text_created') }}</el-radio-button>
                <el-radio-button label="name">{{ __('admin/file_manager.file_name') }}</el-radio-button>
              </el-radio-group>
            </div>

            <div class="mb-3">
              <div class="mb-2">{{ __('admin/file_manager.to_sort') }}</div>
              <el-radio-group v-model="filter.order" size="small">
                <el-radio-button label="desc">{{ __('admin/file_manager.text_desc') }}</el-radio-button>
                <el-radio-button label="asc">{{ __('admin/file_manager.text_asc') }}</el-radio-button>
              </el-radio-group>
            </div>
            <el-button slot="reference" size="small" plain type="primary" icon="el-icon-s-operation"></el-button>
          </el-popover>
          <el-button size="small" plain type="primary" @click="openUploadFile" icon="el-icon-upload2">{{
            __('admin/file_manager.upload_files') }}</el-button>
        </div>
      </div>
      <div v-if="images.length" class="content-center"
        v-batch-select="{ className: '.image-list', selectImageIndex, setSelectStatus: updateSelectStatus, imgMove: imgMove }">
        <div :class="['image-list', file.selected ? 'active' : '']" v-for="file, index in images" :key="index"
          @click="checkedImage(index)" @dblclick="checkedImageDouble(index)">
          <div class="img">
            <i class="el-icon-video-play" v-if="file.mime == 'video/mp4'"></i>
            <img v-else :src="file.url" draggable="false" />
          </div>
          <div class="text">
            <span :title="file.name">@{{ file.name }}</span>
            <i v-if="file.selected" class="el-icon-check"></i>
          </div>
        </div>
      </div>
      <el-empty v-else description="{{ __('admin/file_manager.no_file') }}"></el-empty>
      <div class="content-footer">
        <div class="right"></div>
        <div class="pagination-wrap">
          <el-pagination @size-change="pageSizeChange" @current-change="pageCurrentChange" :current-page="image_page"
            :page-sizes="[20, 40, 60, 80, 100]" :page-size="20" layout="total, sizes, prev, pager, next, jumper"
            :total="image_total">
          </el-pagination>

        </div>
        <div class="right">

        </div>
      </div>
    </div>
    @else
    <div class="text-center mt-5 w-100 fs-4">{{ __('admin/file_manager.show_pc') }}</div>
    @endif

    <el-dialog title="{{ __('admin/file_manager.upload_files') }}" top="12vh" :visible.sync="uploadFileDialog.show"
      width="500px" @close="uploadFileDialogClose" custom-class="upload-wrap">
      <el-upload class="photos-upload" target="photos-upload" id="photos-upload"
        element-loading-text="{{ __('admin/file_manager.image_uploading') }}..."
        element-loading-background="rgba(0, 0, 0, 0.6)" drag action="" :show-file-list="false"
        accept=".jpg,.jpeg,.png,.JPG,.JPEG,.PNG,.mp4,.MP4,.gif,.webp"
        :on-change="handleUploadChange" :http-request="uploadFile" :multiple="true">
        <i class="el-icon-upload"></i>
        <div class="el-upload__text">{{ __('admin/file_manager.click_upload') }}</div>
      </el-upload>
      <div class="upload-image">
        <div v-for="image, index in uploadFileDialog.images" :key="index" class="list">
          <div class="info">
            <div class="name">@{{ index + 1 }}. @{{ image.name }}</div>
            <div class="status">
              <span v-if="image.status == 'complete'" class="text-success">{{ __('admin/file_manager.finish') }}</span>
              <span v-else-if="image.status == 'fail'" class="text-danger">{{ __('admin/file_manager.upload_fail')
                }}</span>
              <span v-else>{{ __('admin/file_manager.uploading') }}</span>
            </div>
          </div>
          <el-progress :percentage="image.progre" :status="image.status == 'fail' ? 'exception' : 'success'"
            :show-text="false" :stroke-width="4"></el-progress>
          <div v-if="image.fail_text" class="mt-1 text-danger" v-text="image.fail_text"></div>
        </div>
      </div>
    </el-dialog>

    <div class="drop_file_hint d-none">{!! __('admin/file_manager.drop_file_hint') !!}</div>
    <div class="drop_folder_hint d-none">{!! __('admin/file_manager.drop_folder_hint') !!}</div>
  </div>

  @stack('admin.file_manager.footer')

  <script>
    var callback = null;

    var app = new Vue({
      el: '#filemanager-wrap-app',
      components: {},
      data: {
        min: 10,
        max: 40,
        paneLengthPercent: 26,
        triggerLength: 10,
        isShift: false,
        mime: @json(request('mime')),
        isMultiple: {{ request('is_multiple', true) }}, // 是否允许多选
        loading: false,
        isBatchSelect: false, // 当前是否正在是否批量选择
        selectImageIndex: [],
        filterKeyword: '',
        filterKeywordVisible: false,
        filter: {
          sort: 'created',
          order: 'desc',
          keyword: ''
        },

        treeData: [{
          name: '{{ __('admin/file_manager.picture_space') }}',
          path: '/',
          children: []
        }],

        copyTreeData: [], // 用于恢复树形结构

        defaultProps: {
          children: 'children',
          label: 'name',
          isLeaf: 'leaf'
        },
        selectIdxs: [],

        uploadFileDialog: {
          show: false,
          total: 0,
          images: []
        },

        folderCurrent: '/',
        defaultkeyarr: ['/'],

        triggerLeftOffset: 0,

        images: [],
        image_total: 0,
        image_page: 1,
        per_page: 20,
        @stack('admin.file_manager.vue.data')
      },
      // 计算属性
      computed: {
        paneLengthValue() {
          return `calc(${this.paneLengthPercent}% - ${this.triggerLength / 2 + 'px'})`
        },
      },
      // 侦听器
      watch: {
        images: {
          handler(val) {
            if (this.isBatchSelect) return;
            // 将选中的图片索引放入 selectImageIndex，未选中则清空
            this.selectImageIndex = val.filter(item => item.selected).map(e => this.images.indexOf(e));
          },
          deep: true
        },

        selectImageIndex(indexs) {
          this.images.forEach((item, index) => {
            item.selected = indexs.includes(index);
          });
        },

        filter: {
          handler(val) {
            this.image_page = 1;
            this.loadData()
          },
          deep: true
        }
      },

      created() {
        const defaultkeyarr = sessionStorage.getItem('defaultkeyarr');
        const folderCurrent = sessionStorage.getItem('folderCurrent');

        if (defaultkeyarr) {
          this.defaultkeyarr = defaultkeyarr.split(',');
        }

        if (folderCurrent) {
          this.folderCurrent = folderCurrent;
        }
      },

      // 实例被挂载后调用
      mounted() {
        this.loadDirectories()
        this.loadData()

        if (this.isMultiple) {
          // 获取键盘事件 是否按住 shift/ctrl 键 兼容 mac 和 windows
          document.addEventListener('keydown', (e) => {
            this.isShift = e.shiftKey;
            this.isCtrl = e.ctrlKey || e.metaKey;
          })

          // 获取键盘事件 是否松开 shift/ctrl 键
          document.addEventListener('keyup', (e) => {
            this.isShift = e.shiftKey;
            this.isCtrl = e.ctrlKey || e.metaKey;
          })

          // 判断鼠标是否点击 .image-list 元素
          document.addEventListener('click', (e) => {
            if (this.isBatchSelect) return;
            const targets = ['filemanager-navbar', 'content-center']
            if (targets.indexOf(e.target.className) > -1) {
              this.selectImageIndex = [];
              this.images.map(e => e.selected = false)
            }
          })
        }
      },

      methods: {
        searchFile() {
          this.image_page = 1;
          this.loadData()
        },

        loadDirectories() {
          $http.get('file_manager/directories').then((res) => {
            this.treeData[0].children = res;
          })
        },

        handleNodeClick(e, node) {
          if (e.path == this.folderCurrent) {
            return;
          }

          // 重置搜索框
          this.filterKeyword = '';
          this.$refs['keyword-popover'].doClose()

          this.folderCurrent = e.path
          this.image_page = 1;
          sessionStorage.setItem('folderCurrent', this.folderCurrent);
          this.loadData(e, node)
        },

        updateSelectStatus(status) {
          this.isBatchSelect = status
        },

        pageCurrentChange(e) {
          this.image_page = e
          this.loadData()
        },

        pageSizeChange(e) {
          this.per_page = e
          this.loadData()
        },

        uploadFileDialogClose() {
          if (this.uploadFileDialog.images.length) {
            this.loadData()
          }

          this.uploadFileDialog.images = [];
        },

        openUploadFile() {
          this.uploadFileDialog.show = true
        },

        handleDrop(draggingNode, dropNode, dropType, ev) {
          const name = draggingNode.data.name;
          const path = draggingNode.data.path;
          const dropPath = dropNode.data.path;
          const dropName = dropNode.data.name;

          $('.drop_folder_hint').find('span:first-child').text(name).siblings('span').text(dropPath);
          this.$confirm($('.drop_folder_hint').html(),"{{ __('common.text_hint') }}", {
            dangerouslyUseHTMLString: true,
            type: "warning"
          }).then(() => {
            $http.post('file_manager/move_directories', {source_path:path, dest_path:dropPath }).then((res) => {
              // 修改path
              dropNode.data.children[dropNode.data.children.length - 1].path = dropPath + '/' + name;
              // 如果当前激活目录是移动的目录，则修改当前激活目录为移动后的目录 path
              if (this.folderCurrent == path) {
                this.folderCurrent = dropPath + '/' + name;
                sessionStorage.setItem('folderCurrent', this.folderCurrent);
              }
            })
          }).catch(() => {
            this.treeData = this.copyTreeData
          });
        },

        handleDragStart(e) {
          this.copyTreeData = JSON.parse(
            JSON.stringify(this.treeData)
          );
        },

        // allowDrop
        allowDrop(draggingNode, dropNode, type) {
          if (type == 'prev' || type == 'next') {
            return;
          }

          return true;
        },

        // 图片拖动到文件夹
        imgMove(path, name, selectImageIndex) {
          console.log(path, selectImageIndex);
          $('.drop_file_hint').find('span:first-child').text(selectImageIndex.length).siblings('span').text(name);
          this.$confirm($('.drop_file_hint').html(),"{{ __('common.text_hint') }}", {
            dangerouslyUseHTMLString:true,
            type: "warning"
          }).then(() => {
            // console.log(path, dropPath, dropName);
            const imagePaths = this.images.filter((item, index) => selectImageIndex.includes(index)).map(e => e.path);
            console.log(path, imagePaths);

            $http.post('file_manager/move_files', {images:imagePaths, dest_path:path }).then((res) => {
              this.loadData()
            })
          })
        },

        // 文件上传
        uploadFile(file) {
          const that = this;
          let newFile = {};

          var formData = new FormData();
          formData.append("file", file.file, file.file.name);
          formData.append("path", this.folderCurrent);

          newFile = {
            // index: this.images.length,
            name: file.file.name,
            progre: 0,
            status: 'padding'
          };

          this.uploadFileDialog.images.push(newFile);

          let index = this.uploadFileDialog.images.length - 1;

          $http.post('file_manager/upload', formData, {hmsg: true}).then((res) => {
            this.uploadFileDialog.images[index].status = 'complete';
            this.uploadFileDialog.images[index].progre = 100;
          }).catch((err) => {
            this.uploadFileDialog.images[index].status = 'fail';
            this.uploadFileDialog.images[index].progre = 80;
            this.uploadFileDialog.images[index].fail_text = err.response.data.message;
          }).finally(() => {
            index += 1
          });
        },

        handleUploadChange(e) {
          console.log(e);
          // console.log('handleUploadChange');
        },

        updateDefaultExpandedKeys(node, type) {
          let defaultkeyarr = sessionStorage.getItem('defaultkeyarr') ? sessionStorage.getItem('defaultkeyarr').split(',') : [];
          const isExist = defaultkeyarr.some(item => item === node.path)

          if (type == 'expand') {
            if (!isExist) {
              defaultkeyarr.push(node.path)
            }
          } else {
            const index = defaultkeyarr.findIndex(e => e == node.path);
            if (index > -1) {
              defaultkeyarr.splice(index, 1);
              // 删除以 node.path 开头的所有元素，除了当前激活目录 -> this.folderCurrent
              defaultkeyarr = defaultkeyarr.filter(e => e == this.folderCurrent || !e.startsWith(node.path));
            }
          }

          sessionStorage.setItem('defaultkeyarr', defaultkeyarr);
        },

        loadData(e, node) {
          this.loading = true;

          $http.get(`file_manager/files?base_folder=${this.folderCurrent}`, {
            page: this.image_page,
            per_page: this.per_page,
            sort: this.filter.sort,
            keyword: this.filterKeyword,
            order: this.filter.order
          }, {
            hload: true
          }).then((res) => {
            this.images = res.images
            this.image_page = res.image_page
            this.image_total = res.image_total

            if (node) {
              node.expanded = true
              this.updateDefaultExpandedKeys(node.data, 'expand')
            }
          }).finally(() => this.loading = false);
        },

        // 按下滑动器
        handleMouseDown(e) {
          document.addEventListener('mousemove', this.handleMouseMove)
          document.addEventListener('mouseup', this.handleMouseUp)

          this.triggerLeftOffset = e.pageX - e.srcElement.getBoundingClientRect().left
        },

        // 按下滑动器后移动鼠标
        handleMouseMove(e) {
          const clientRect = this.$refs.splitPane.getBoundingClientRect()
          let paneLengthPercent = 0

          const offset = e.pageX - clientRect.left - this.triggerLeftOffset + this.triggerLength / 2
          paneLengthPercent = (offset / clientRect.width) * 100

          if (paneLengthPercent < this.min) {
            paneLengthPercent = this.min
          }
          if (paneLengthPercent > this.max) {
            paneLengthPercent = this.max
          }
          this.paneLengthPercent = paneLengthPercent;
        },

        // 松开滑动器
        handleMouseUp() {
          document.removeEventListener('mousemove', this.handleMouseMove)
        },

        checkedImage(index) {
          // 获取当前选中的 index
          const selectedIndex = this.images.findIndex(e => e.selected);

          if (this.isShift) {
            // 获取 selectedIndex 与 index 之间的所有图片
            let selectedImages = this.images.slice(Math.min(selectedIndex, index), Math.max(selectedIndex, index) +
            1);
            selectedImages.map(e => e.selected = true)
            return;
          }

          if (this.isCtrl) {
            this.images[index].selected = !this.images[index].selected;
            return;
          }

          if (this.selectImageIndex.length > 1) {
            this.images.map((e, i) => i != index ? e.selected = false : e.selected = true)
            return;
          }

          this.images.map((e, i) => i != index ? e.selected = false : '')
          this.images[index].selected = true
        },

        checkedImageDouble(index) {
          this.images.map((e, i) => i != index ? e.selected = false : e.selected = true)
          this.fileChecked()
        },

        // 选取
        fileChecked() {
          let typedFiles = this.images.filter(e => e.selected)

          if (this.mime) {
            // 判断 typedFiles 数组内 mime 是否有不是 image 开头的
            if (this.mime == 'image' && typedFiles.some(e => !e.mime.startsWith('image'))) {
              layer.msg('{{ __('admin/file_manager.verify_select_image') }}', () => {});
              return;
            }

            // 判断 typedFiles 数组内 mime 是否有不是 video 开头的
            if (this.mime == 'video' && typedFiles.some(e => !e.mime.startsWith('video'))) {
              layer.msg('{{ __('admin/file_manager.verify_select_video') }}', () => {});
              return;
            }
          }

          if (callback !== null) {
            callback(typedFiles);
          }

          // 关闭弹窗
          var index = parent.layer.getFrameIndex(window.name);
          parent.layer.close(index);
        },

        deleteFile() {
          this.$confirm('{{ __('admin/file_manager.confirm_delete_file') }}', '{{ __('common.text_hint') }}', {
            type: 'warning'
          }).then(() => {
            const selectImageIndex = this.selectImageIndex;
            // 获取images中下标与selectImageIndex相同的图片
            const images = this.images.filter(e => selectImageIndex.includes(this.images.indexOf(e)));
            // images 取 path 组成数组 然后用 | 分割成字符串
            const files = images.map(e => e.name);

            this.loading = true;

            $http.delete('file_manager/files', {
              path: this.folderCurrent,
              files: files
            }, {
              hload: true
            }).then((res) => {
              layer.msg(res.message)
              this.loadData()
            })
          }).catch(_ => {});
        },

        deleteFolder(node, data) {
          if (data.path) {
            this.$confirm('{{ __('admin/file_manager.confirm_delete_folder') }}', '{{ __('common.text_hint') }}', {
              type: 'warning'
            }).then(() => {
              $http.delete(`file_manager/directories`, {
                name: data.path
              }).then((res) => {
                layer.msg(res.message)
                this.$refs.tree.setCurrentKey(node.parent.data.path)
                this.folderCurrent = node.parent.data.path;
                this.$refs.tree.remove(data.path)
              }).finally(() => this.loadData())
            }).catch(_ => {});
          }
        },

        selectAll() {
          // 获取 this.images 中的 selected 是否全部为 true
          const isAllSelected = this.images.every(e => e.selected);
          this.images.map(e => e.selected = !isAllSelected)
        },

        downloadImages() {
          // 获取选中的图片
          const selectedImages = this.images.filter(e => e.selected);
          // 创建 a 标签
          selectedImages.forEach(e => {
            const a = document.createElement('a');
            // 设置 a 标签的 href 属性
            a.href = e.origin_url;
            // 设置 a 标签的 download 属性
            a.download = e.name;
            // 触发 a 标签的 click 事件
            a.click();
          });
        },

        viewImages() {
          const selectedImages = this.images.filter(e => e.selected);
          selectedImages.forEach(e => {
            window.open(e.origin_url);
          });
        },

        copyLink() {
          const selectedImages = this.images.filter(e => e.selected);
          const text = selectedImages.map(e => e.origin_url).join('\n');
          const input = document.createElement('textarea');
          input.value = text;
          document.body.appendChild(input);
          input.select();
          document.execCommand('copy');
          document.body.removeChild(input);
          layer.msg('{{ __('admin/file_manager.copy_success') }}')
        },

        openInputBox(type, node, data) {
          let fileSuffix, fileName = '';

          if (type == 'download') {
            const base = document.getElementsByTagName('base')[0].href;
            window.open(base + `/file_manager/export?path=${data.path}`);
            return;
          }

          if (type == 'image') {
            const image = this.images[this.selectImageIndex].name;
            // 获取文件后缀
            fileSuffix = image.substring(image.lastIndexOf('.') + 1);
            // 获取文件名
            fileName = image.substring(0, image.lastIndexOf('.'));
          }

          this.$prompt('', type == 'addFolder' ? '{{ __('admin/file_manager.new_folder') }}' : '{{ __('admin/file_manager.rename') }}', {
            confirmButtonText: '{{ __('common.confirm') }}',
            cancelButtonText: '{{ __('common.cancel') }}',
            inputPattern: /^.+$/,
            closeOnClickModal: false,
            inputValue: type == 'image' ? fileName : (type == 'renameFolder' ? data.name : '{{ __('admin/file_manager.new_folder') }}'),
            inputErrorMessage: '{{ __('admin/file_manager.can_empty') }}'
          }).then(({
            value
          }) => {
            if (type == 'addFolder') {
              let fileAllPathName = data.path == '/' ? '/' + value : data.path + '/' + value;
              $http.post(`file_manager/directories`, {
                name: fileAllPathName
              }).then((res) => {
                layer.msg(res.message)
                node.expanded = true
                this.$refs.tree.append({
                  name: value,
                  path: fileAllPathName,
                  leaf: true
                }, node);
                this.$refs.tree.setCurrentKey(fileAllPathName)
                this.folderCurrent = fileAllPathName;
                this.images = [];
                this.image_page = 1
                this.image_total = 0
                this.updateDefaultExpandedKeys(node.data, 'expand')
              })
            }

            if (type == 'renameFolder') {
              this.folderCurrent = data.path;

              $http.post(`file_manager/rename`, {
                origin_name: data.path,
                new_name: value
              }).then((res) => {
                layer.msg(res.message)
                data.name = value;
                data.path = data.path.replace(/\/[^\/]*$/, '/' + value);
                this.folderCurrent = this.folderCurrent.replace(/\/[^\/]*$/, '/' + value);
                // 递归修改 data 内所有 children -> path 的对应 level = value
                this.changeChildren(data, node, value);
              })
            }

            if (type == 'image') {
              const name = this.images[this.selectImageIndex].name;
              const origin_name = this.folderCurrent == '/' ? '/' + name : this.folderCurrent + '/' + name;

              $http.post(`file_manager/rename`, {
                origin_name: origin_name,
                new_name: value + '.' + fileSuffix
              }).then((res) => {
                this.images[this.selectImageIndex].name = value + '.' + fileSuffix;
                layer.msg(res.message)
              })
            }
          }).catch(() => {});
        },

        changeChildren(data, node, value) {
          if (data.children) {
            data.children.map(e => {
              if (e.path) {
                // 将字符串转换为数组
                let path = e.path.split('/')
                path[node.level - 1] = value
                // 将数组转换为字符串
                e.path = path.join('/')
              }

              if (e.children) {
                this.changeChildren(e, node, value)
              }
            })
          }
        }
        @stack('admin.file_manager.vue.method')
      },
    })

  $('#filemanager-wrap-app').click(function () {
    if (!$(event.target).hasClass('keyword-popover-area') && !$(event.target).hasClass('el-icon-search')) {
      app.filterKeywordVisible = false;
    }
  })
  </script>
</body>
</html>

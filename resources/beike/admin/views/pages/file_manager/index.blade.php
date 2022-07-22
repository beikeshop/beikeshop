<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <base href="{{ $admin_base_url }}">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script src="{{ asset('vendor/vue/2.6.12/vue.js') }}"></script>
  <script src="{{ asset('vendor/element-ui/2.15.9/index.js') }}"></script>
  {{-- <script src="{{ asset('vendor/element-ui/2.15.6/js.js') }}"></script> --}}
  <script src="{{ asset('vendor/jquery/jquery-3.6.0.min.js') }}"></script>
  <script src="{{ asset('vendor/layer/3.5.1/layer.js') }}"></script>
  <script src="{{ asset('vendor/vue/batch_select.js') }}"></script>
  <link href="{{ mix('/build/beike/admin/css/bootstrap.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('vendor/element-ui/2.15.9/index.css') }}">
  <link href="{{ mix('build/beike/admin/css/filemanager.css') }}" rel="stylesheet">
  <script src="{{ mix('build/beike/admin/js/app.js') }}"></script>
  <title>beike filemanager</title>
</head>
<body class="page-filemanager">
  <div class="filemanager-wrap" id="filemanager-wrap-app" v-cloak ref="splitPane">
    <div class="filemanager-navbar" :style="'width:' + paneLengthValue">
      <el-tree
        :props="defaultProps"
        node-key="path"
        :data="treeData"
        :current-node-key="folderCurrent"
        :default-expanded-keys="defaultkeyarr"
        :expand-on-click-node="false"
        highlight-current
        ref="tree"
        @node-click="handleNodeClick"
        @node-expand="(node) => {updateDefaultExpandedKeys(node, 'expand')}"
        @node-collapse="(node) => {updateDefaultExpandedKeys(node, 'collapse')}"
        class="tree-wrap">
        <div class="custom-tree-node" slot-scope="{ node, data }">
          <div>@{{ node.label }}</div>
          {{-- v-if="node.isCurrent" --}}
          <div class="right" >
            <el-tooltip class="item" effect="dark" content="创建文件夹" placement="top">
              <span @click.stop="() => {openInputBox('addFolder', node, data)}"><i class="el-icon-circle-plus-outline"></i></span>
            </el-tooltip>

            <el-tooltip class="item" effect="dark" content="重命名" placement="top">
              <span v-if="node.level != 1" @click.stop="() => {openInputBox('renameFolder', node, data)}"><i class="el-icon-edit"></i></span>
            </el-tooltip>

            <el-tooltip class="item" effect="dark" content="删除" placement="top">
              <span v-if="node.level != 1" @click.stop="() => {deleteFolder(node, data)}"><i class="el-icon-delete"></i></span>
            </el-tooltip>

          </div>
        </div>
      </el-tree>
    </div>
    <div class="filemanager-divider" @mousedown="handleMouseDown"></div>
    <div class="filemanager-content" v-loading="loading" element-loading-background="rgba(255, 255, 255, 0.5)">
      <div class="content-head">
        <div class="left">
          <el-link :underline="false" :disabled="!!!selectImageIndex.length" icon="el-icon-download" @click="downloadImages">下载</el-link>
          <el-link :underline="false" :disabled="!!!selectImageIndex.length" @click="deleteFile" icon="el-icon-delete">删除</el-link>
          <el-link :underline="false" :disabled="selectImageIndex.length == 1 ? false : true" @click="openInputBox('image')" icon="el-icon-edit">重命名</el-link>
          <el-link :underline="false" :disabled="!!!images.length && !!!selectImageIndex.length" @click="selectAll()" icon="el-icon-finished">全选</el-link>
        </div>
        <div class="right">
          <el-button size="small" type="primary" @click="openUploadFile" icon="el-icon-upload2">上传文件</el-button>
        </div>
      </div>
      <div v-if="images.length" class="content-center" v-batch-select="{ className: '.image-list', selectImageIndex, setSelectStatus: updateSelectStatus }">
        <div :class="['image-list', file.selected ? 'active' : '']" v-for="file, index in images" :key="index" @click="checkedImage(index)">
          <div class="img"><img :src="file.url"></div>
          <div class="text">
            <span :title="file.name">@{{ file.name }}</span>
            <i v-if="file.selected" class="el-icon-check"></i>
          </div>
        </div>
      </div>
      <el-empty v-else description="没有文件"></el-empty>
      <div class="content-footer">
        <div class="right"></div>
        <div class="pagination-wrap">
          <el-pagination
            @current-change="pageCurrentChange"
            :page-size="20"
            layout="prev, pager, next"
            :total="image_total">
          </el-pagination>
        </div>
        <div class="right"><el-button size="small" icon="el-icon-check" type="primary" @click="fileChecked" :disabled="!!!selectImageIndex.length">选择</el-button></div>
      </div>
    </div>

    <el-dialog
      title="上传文件"
      top="12vh"
      :visible.sync="uploadFileDialog.show"
      width="500px"
      @close="uploadFileDialogClose"
      custom-class="upload-wrap">
        <el-upload
          class="photos-upload"
          target="photos-upload"
          id="photos-upload"
          element-loading-text="图片上传中..."
          element-loading-background="rgba(0, 0, 0, 0.6)"
          drag
          action=""
          :show-file-list="false"
          accept=".jpg,.jpeg,.png,.JPG,.JPEG,.PNG"
          :before-upload="beforePhotoUpload"
          :on-success="handlePhotoSuccess"
          :on-change="handleUploadChange"
          :http-request="uploadFile"
          :multiple="true">
          <i class="el-icon-upload"></i>
          <div class="el-upload__text">点击上传，或将图片拖到此处</div>
        </el-upload>
        <div class="upload-image">
          <div v-for="image, index in uploadFileDialog.images" :key="index" class="list">
            <div class="info">
              <div class="name">@{{ index + 1 }}. @{{ image.name }}</div>
              <div class="status">@{{ image.status == 'complete' ? '完成' : '上传中' }}</div>
            </div>
            <el-progress :percentage="image.progre" :show-text="false" :stroke-width="4"></el-progress>
          </div>
        </div>
    </el-dialog>
  </div>

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

      ssss:[],
      loading: false,
      isBatchSelect: false,

      selectImageIndex: [],

      treeData: [{name: '图片空间', path: '/', children: @json($directories)}],

      defaultProps: {
        children: 'children',
        label: 'name',
        isLeaf: 'leaf'
      },
      selectIdxs: [],

      uploadFileDialog: {
        show: false,
        images: []
      },

      folderCurrent: '/',
      defaultkeyarr: ['/'],

      triggerLeftOffset: 0,

      images: [],
      image_total: 0,
      image_page: 1,
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
    },
    // 组件方法
    methods: {
      handleNodeClick(e, node) {
        if (e.path == this.folderCurrent) {
          return;
        }

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

      uploadFileDialogClose() {
        this.uploadFileDialog.images = [];
        $('.content-center').animate({ scrollTop: 1000} , 'fast');
      },

      openUploadFile() {
        this.uploadFileDialog.show = true
      },

      beforePhotoUpload(file) {
        // this.editing.photoLoading = true;
      },

      handlePhotoSuccess(data) {
        // this.editing.photoLoading = false;

        if (data.images) {
          this.images.push(data.images);
        }
      },

      // 文件上传
      uploadFile(file) {
        const that = this;
        let newFile = {};
        if (file.file.type != 'image/png' && file.file.type != 'image/jpeg') {
          return;
        }

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

        $http.post('file_manager/upload', formData).then((res) => {
          this.uploadFileDialog.images[index].status = 'complete';
          this.uploadFileDialog.images[index].progre = 100;
          index += 1;
        })
      },

      handleUploadChange() {
        // console.log('handleUploadChange');
      },

      updateDefaultExpandedKeys(node, type) {
        const isExist = this.defaultkeyarr.some(item => item === node.path)

        if (type == 'expand') {
          if (!isExist) {
            this.defaultkeyarr.push(node.path)
          }
        } else {
          const index = this.defaultkeyarr.findIndex(e => e == node.path);
          if (index > -1) {
            this.defaultkeyarr.splice(index, 1);
          }
        }

        sessionStorage.setItem('defaultkeyarr', this.defaultkeyarr);
      },

      loadData(e, node) {
        this.loading = true;

        $http.get(`file_manager/files?base_folder=${this.folderCurrent}`, {page: this.image_page}, {hload: true}).then((res) => {
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
          let selectedImages = this.images.slice(Math.min(selectedIndex, index), Math.max(selectedIndex, index) + 1);
          selectedImages.map(e => e.selected = true)
          return;
        }

        if (this.isCtrl) {
          this.images[index].selected = !this.images[index].selected;
          return;
        }

        if (this.selectImageIndex.length > 1) {
          this.images.map((e,i) => i != index ? e.selected = false : e.selected = true)
          return;
        }

        this.images.map((e,i) => i != index ? e.selected = false : '')
        this.images[index].selected = !this.images[index].selected
      },

      // 选取
      fileChecked() {
        let typedFiles = this.images.filter(e => e.selected)

        if (callback !== null) {
          callback(typedFiles);
        }

        // 关闭弹窗
        var index = parent.layer.getFrameIndex(window.name);
        parent.layer.close(index);
      },

      deleteFile() {
        this.$confirm('是否要删除选中文件', '提示', {
          type: 'warning'
        }).then(() => {
          const selectImageIndex = this.selectImageIndex;
            // 获取images中下标与selectImageIndex相同的图片
          const images = this.images.filter(e => selectImageIndex.includes(this.images.indexOf(e)));
          // images 取 path 组成数组 然后用 | 分割成字符串
          const files = images.map(e => e.name);

          $http.delete('file_manager/files',  {path: this.folderCurrent, files: files}).then((res) => {
            layer.msg(res.message)
            this.loadData()
          })
        }).catch(_=>{});
      },

      deleteFolder(node, data) {
        if (data.path) {
          this.$confirm('正在进行删除文件夹操作，文件夹内所有文件都将被删除，是否确认？', '提示', {
            type: 'warning'
          }).then(() => {
            $http.delete(`file_manager/directories`, {name: this.folderCurrent}).then((res) => {
              layer.msg(res.message)
              this.$refs.tree.setCurrentKey(node.parent.data.path)
              this.folderCurrent = node.parent.data.path;
              this.$refs.tree.remove(data.path)
              this.loadData()
            })
          }).catch(_=>{});
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

      openInputBox(type, node, data) {
        this.$prompt('', type == 'addFolder' ? '新建文件夹' : '重命名', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          inputPattern: /^.+$/,
          closeOnClickModal: false,
          inputValue: type == 'image' ? this.images[this.selectImageIndex].name : (type == 'renameFolder' ? data.name : '新建文件夹'),
          inputErrorMessage: '不能为空'
        }).then(({ value }) => {
          if (type == 'addFolder') {
            let fileAllPathName = this.folderCurrent + '/' + value;
            $http.post(`file_manager/directories`, {name: fileAllPathName}).then((res) => {
              layer.msg(res.message)
              node.expanded = true
              this.$refs.tree.append({name: value, path: fileAllPathName, leaf: true}, node);
              this.$refs.tree.setCurrentKey(fileAllPathName)
              this.folderCurrent = fileAllPathName;
              this.updateDefaultExpandedKeys(node.data, 'expand')
            })
          }

          if (type == 'renameFolder') {
            $http.post(`file_manager/rename`, {origin_name: this.folderCurrent, new_name: value}).then((res) => {
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

            $http.post(`file_manager/rename`, {origin_name: origin_name, new_name: value}).then((res) => {
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
    },

    created () {
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
    mounted () {
      this.loadData()
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
    },
  })
  </script>
</body>
</html>

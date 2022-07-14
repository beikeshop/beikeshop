<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
  <script src="{{ asset('vendor/vue/2.6.12/vue.js') }}"></script>
  <script src="{{ asset('vendor/element-ui/2.6.2/js.js') }}"></script>
  <script src="{{ asset('vendor/jquery/jquery-3.6.0.min.js') }}"></script>
  <script src="{{ asset('vendor/layer/3.5.1/layer.js') }}"></script>
  <link href="{{ mix('/build/beike/admin/css/bootstrap.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('vendor/element-ui/2.6.2/css.css') }}">
  <link href="{{ mix('build/beike/admin/css/filemanager.css') }}" rel="stylesheet">
  <script src="{{ mix('build/beike/admin/js/app.js') }}"></script>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>beike filemanager</title>
</head>
<body class="page-filemanager">
  <div class="filemanager-wrap" id="filemanager-wrap-app" v-cloak ref="splitPane">
    <div class="filemanager-navbar" :style="'width:' + paneLengthValue">
      <el-tree
        :props="defaultProps"
        node-key="path"
        :data="treeData"
        {{-- :load="loadNod1e" --}}
        {{-- lazy --}}
        :default-expanded-keys="defaultkeyarr"
        :expand-on-click-node="false"
        highlight-current
        ref="tree"
        @node-click="handleNodeClick"
        {{-- @node-expand="nodeExpand" --}}
        @node-expand="(node) => {updateDefaultExpandedKeys(node, 'expand')}"
        @node-collapse="(node) => {updateDefaultExpandedKeys(node, 'collapse')}"
        {{-- @node-collapse="nodeCollapse" --}}
        class="tree-wrap">
        <div class="custom-tree-node" slot-scope="{ node, data }">
          <div>@{{ node.label }}</div>
          <div class="right" v-if="node.isCurrent">
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
          <el-link :underline="false" :disabled="editingImageIndex === null" icon="el-icon-download">下载</el-link>
          <el-link :underline="false" :disabled="editingImageIndex === null" @click="deleteFile" icon="el-icon-delete">删除</el-link>
          <el-link :underline="false" :disabled="editingImageIndex === null" @click="openInputBox('image')" icon="el-icon-edit">重命名</el-link>
        </div>
        <div class="right">
          <el-button size="small" type="primary" @click="openUploadFile" icon="el-icon-upload2">上传文件</el-button>
        </div>
      </div>
      <div class="content-center">
        <div :class="['image-list', file.selected ? 'active' : '']" v-for="file, index in images" :key="index" @click="checkedImage(index)">
          <div class="img"><img :src="file.url"></div>
          <div class="text">
            <span :title="file.name">@{{ file.name }}</span>
            <i v-if="file.selected" class="el-icon-check"></i>
          </div>
        </div>
      </div>
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
        <div class="right"><el-button size="small" icon="el-icon-check" type="primary" @click="fileChecked" :disabled="editingImageIndex === null">选择</el-button></div>
      </div>
    </div>

    <el-dialog
      title="上传文件"
      :visible.sync="uploadFileDialog.show"
      width="500px"
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
            <div class="name">@{{ image.name }}</div>
            <div class="status">上传中</div>
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
      paneLengthPercent: 20,
      triggerLength: 10,

      loading: false,

      editingImageIndex: null,

      treeData: [{name: '图片空间', path: '/', children: @json($folders)}],

      defaultProps: {
        children: 'children',
        label: 'name',
        isLeaf: 'leaf'
      },

      uploadFileDialog: {
        show: false,
        images: [{name:'dasdas.png', percent: 90}]
      },

      folderCurrent: '/',
      defaultkeyarr: ['/'],

      triggerLeftOffset: 0,

      images: @json($images),
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

    },
    // 组件方法
    methods: {
      handleNodeClick(e, node, data) {
        if (e.path == this.folderCurrent) {
          return;
        }
        this.folderCurrent = e.path
        this.image_page = 1;
        this.loadData(e, node)
      },

      pageCurrentChange(e) {
        this.image_page = e
        this.loadData()
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

        newFile = {
          index: this.images.length,
          percent: 0,
        };

        this.uploadFileDialog.push(newFile);

        console.log(file.file)
      },

      handleUploadChange() {
        // console.log('handleUploadChange');
      },

      updateDefaultExpandedKeys(node, type) {
        const isExist = this.defaultkeyarr.some(item => item === node.path)
        if (!isExist) {
          if (type == 'expand') return this.defaultkeyarr.push(node.path)
        } else {
          const index = this.defaultkeyarr.findIndex(e => e == node.path);
          if (type == 'collapse') return this.defaultkeyarr.splice(index, 1);
        }

        sessionStorage.setItem('defaultkeyarr', this.defaultkeyarr);
      },

      loadData(e, node) {
        $http.get(`file_manager?base_folder=${this.folderCurrent}`, {page: this.image_page}).then((res) => {
          if (node) {
            if (!e.children) {
              node.expanded = !node.expanded;
              this.$refs["tree"].updateKeyChildren(this.folderCurrent, res.folders);
            }
          }

          this.images = res.images
          this.image_page = res.image_page
          this.image_total = res.image_total
        })
      },

      loadNode(node, resolve) {
        let treeData = [{name: '图片空间', path: '/'}]
        if (node.level === 0) {
          return resolve(treeData);
        }

        if (node.level === 1) return resolve(@json($folders));

        $http.get(`file_manager?base_folder=${node.data.path}`).then((res) => {
          resolve(res.folders);
        })
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
        this.editingImageIndex = index;
        this.images.map(e => !e.index ? e.selected = false : '')
        this.images[index].selected = !this.images[index].selected
      },

      fileChecked() {
        let typedFiles = this.images[this.editingImageIndex];

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
          this.images.splice(this.editingImageIndex, 1);
          this.$message({type: 'success',message: '删除成功!'});
        }).catch(_=>{});
      },

      deleteFolder(node, data) {
        if (data.path) {
          this.$confirm('正在进行删除文件夹操作，文件夹内所有文件都将被删除，是否确认？', '提示', {
            type: 'warning'
          }).then(() => {
            $http.delete(`file_manager/delete_files`, {name: this.folderCurrent}).then((res) => {
              layer.msg(res.message)
              this.$refs.tree.setCurrentKey(node.parent.data.path)
              this.folderCurrent = node.parent.data.path;
              this.$refs.tree.remove(data.path)
              this.loadData()
            })
          }).catch(_=>{});
        }
      },

      openInputBox(type, node, data) {
        this.$prompt('', type == 'addFolder' ? '新建文件夹' : '重命名', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          inputPattern: /^.+$/,
          closeOnClickModal: false,
          inputValue: type == 'image' ? this.images[this.editingImageIndex].name : (type == 'renameFolder' ? data.name : '新建文件夹'),
          inputErrorMessage: '不能为空'
        }).then(({ value }) => {

          let fileAllPathName = this.folderCurrent + '/' + value;

          if (type == 'addFolder') {
            $http.post(`file_manager/directory`, {name: fileAllPathName}).then((res) => {
              layer.msg(res.message)
              this.$refs.tree.append({name: value, path: fileAllPathName, leaf: true}, node);
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
            $http.post(`file_manager/rename`, {origin_name: fileAllPathNamet, new_name: value}).then((res) => {
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
    // 在实例初始化之后，组件属性计算之前，如data属性等
    beforeCreate () {
    },
    // 在实例创建完成后被立即同步调用
    created () {
      const defaultkeyarr = sessionStorage.getItem('defaultkeyarr');

      if (defaultkeyarr) {
        // this.defaultkeyarr = defaultkeyarr.split(',');
      }
    },
    // 在挂载开始之前被调用:相关的 render 函数首次被调用
    beforeMount () {
    },
    // 实例被挂载后调用
    mounted () {
    },
  })

  $(document).ready(function() {
    $(document).on('click', function (e) {
      if ($(e.target).closest('.content-center .image-list, .content-head, .content-footer').length === 0) {
        app.editingImageIndex = null;
        app.images.map(e => e.selected = false)
      }
    })
  });
  </script>
</body>
</html>

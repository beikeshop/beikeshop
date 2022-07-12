<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
  <script src="<?php echo e(asset('vendor/vue/2.6.12/vue.js')); ?>"></script>
  <script src="<?php echo e(asset('vendor/element-ui/2.6.2/js.js')); ?>"></script>
  <script src="<?php echo e(asset('vendor/jquery/jquery-3.6.0.min.js')); ?>"></script>
  <script src="<?php echo e(asset('vendor/layer/3.5.1/layer.js')); ?>"></script>
  <link href="<?php echo e(mix('/build/beike/admin/css/bootstrap.css')); ?>" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo e(asset('vendor/element-ui/2.6.2/css.css')); ?>">
  <link href="<?php echo e(mix('build/beike/admin/css/filemanager.css')); ?>" rel="stylesheet">
  <script src="<?php echo e(mix('build/beike/admin/js/app.js')); ?>"></script>
  <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
  <title>beike filemanager</title>
</head>
<body class="page-filemanager">
  <div class="filemanager-wrap" id="filemanager-wrap-app" v-cloak ref="splitPane">
    <div class="filemanager-navbar" :style="'width:' + paneLengthValue">
      <el-tree
        :data="tree"
        :props="defaultProps"
        @node-click="handleNodeClick"
        class="tree-wrap">
        <div class="custom-tree-node" slot-scope="{ node, data }">
          <div>{{ node.label }}</div>
          <div class="right">
            <el-tooltip class="item" effect="dark" content="创建文件夹" placement="top">
              <span><i class="el-icon-circle-plus-outline"></i></span>
            </el-tooltip>

            <el-tooltip class="item" effect="dark" content="重命名" placement="top">
              <span @click.stop="() => {openInputBox('folder', data)}"><i class="el-icon-edit"></i></span>
            </el-tooltip>

            <el-tooltip class="item" effect="dark" content="删除" placement="top">
              <span><i class="el-icon-delete"></i></span>
            </el-tooltip>

          </div>
        </div>
      </el-tree>
    </div>
    <div class="filemanager-divider" @mousedown="handleMouseDown"></div>
    <div class="filemanager-content" v-loading="loading" element-loading-background="rgba(255, 255, 255, 0.5)">
      <div class="content-head">
        <div class="left">
          <el-link :underline="false" :disabled="editingFileIndex === null" icon="el-icon-download">下载</el-link>
          <el-link :underline="false" :disabled="editingFileIndex === null" @click="deleteFile" icon="el-icon-delete">删除</el-link>
          <el-link :underline="false" :disabled="editingFileIndex === null" @click="openInputBox('image')" icon="el-icon-edit">重命名</el-link>
        </div>
        <div class="right"><el-button size="mini" type="primary">上传文件</el-button></div>
      </div>
      <div class="content-center">
        <div :class="['image-list', file.selected ? 'active' : '']" v-for="file, index in files" :key="index" @click="checkedImage(index)">
          <img :src="file.src">
          <div class="text">
            <span :title="file.name">{{ file.name }}</span>
            <i v-if="file.selected" class="el-icon-check"></i>
          </div>
        </div>
      </div>
      <div class="content-footer">
        <div class="right"></div>
        <div class="pagination-wrap">
          <el-pagination
            layout="prev, pager, next"
            :total="50">
          </el-pagination>
        </div>
        <div class="right"><el-button size="mini" type="primary" @click="fileChecked" :disabled="editingFileIndex === null">选择</el-button></div>
      </div>
    </div>
  </div>

  <script>
  var app = new Vue({
    el: '#filemanager-wrap-app',
    components: {},
    data: {
      min: 10,
      max: 40,
      paneLengthPercent: 20,
      triggerLength: 10,

      loading: false,

      editingFileIndex: null,

      tree: [
        {
          label: '一级 1',
          id: '2222',
          children: [
            {
              label: '二级 1-1',
              id: '2222',
              children: [
                {
                  label: '三级 1-1-1',
                  id: '2222',
                }
              ]
            }
          ]
        },
        {
          label: '一级 2',
          id: '423423',
        },
        {
          label: '一级 2',
          id: '423423',
        },
        {
          label: '一级 2',
          id: '423423',
        },
        {
          label: '一级 2',
          id: '423423',
        },
      ],

      defaultProps: {
        children: 'children',
        label: 'label'
      },

      triggerLeftOffset: 0,

      files: [
        {type: 'image', src: 'https://via.placeholder.com/140x140.png/eeeeee', name: '文件名称', selected: false},
        {type: 'image', src: 'https://via.placeholder.com/140x140.png/eeeeee', name: '文件名称', selected: false},
        {type: 'image', src: 'https://via.placeholder.com/140x140.png/eeeeee', name: '文件名称', selected: false},
        {type: 'image', src: 'https://via.placeholder.com/140x140.png/eeeeee', name: '文件名称', selected: false},
        {type: 'image', src: 'https://via.placeholder.com/140x140.png/eeeeee', name: '文件名称', selected: false},
        {type: 'image', src: 'https://via.placeholder.com/140x140.png/eeeeee', name: '文件名称', selected: false},
        {type: 'image', src: 'https://via.placeholder.com/140x140.png/eeeeee', name: '文件名称', selected: false},
        {type: 'image', src: 'https://via.placeholder.com/140x140.png/eeeeee', name: '文件名称', selected: false},
        {type: 'image', src: 'https://via.placeholder.com/140x140.png/eeeeee', name: '文件名称', selected: false},
        {type: 'image', src: 'https://via.placeholder.com/140x140.png/eeeeee', name: '文件名称', selected: false},
        {type: 'image', src: 'https://via.placeholder.com/140x140.png/eeeeee', name: '文件名称', selected: false},
        {type: 'image', src: 'https://via.placeholder.com/140x140.png/eeeeee', name: '文件名称', selected: false},
        {type: 'image', src: 'https://via.placeholder.com/140x140.png/eeeeee', name: '文件名称', selected: false},
        {type: 'image', src: 'https://via.placeholder.com/140x140.png/eeeeee', name: '文件名称', selected: false},
        {type: 'image', src: 'https://via.placeholder.com/140x140.png/eeeeee', name: '文件名称', selected: false},
        {type: 'image', src: 'https://via.placeholder.com/140x140.png/eeeeee', name: '文件名称', selected: false},
        {type: 'image', src: 'https://via.placeholder.com/140x140.png/eeeeee', name: '文件名称', selected: false},
        {type: 'image', src: 'https://via.placeholder.com/140x140.png/eeeeee', name: '文件名称', selected: false},
        {type: 'image', src: 'https://via.placeholder.com/140x140.png/eeeeee', name: '文件名称', selected: false},
        {type: 'image', src: 'https://via.placeholder.com/140x140.png/eeeeee', name: '文件名称', selected: false},
        {type: 'image', src: 'https://via.placeholder.com/140x140.png/eeeeee', name: '文件名称', selected: false},
        {type: 'image', src: 'https://via.placeholder.com/140x140.png/eeeeee', name: '文件名称', selected: false},
        {type: 'image', src: 'https://via.placeholder.com/140x140.png/eeeeee', name: '文件名称', selected: false},
        {type: 'image', src: 'https://via.placeholder.com/140x140.png/eeeeee', name: '文件名称', selected: false},
        {type: 'image', src: 'https://via.placeholder.com/140x140.png/eeeeee', name: '文件名称', selected: false},
        {type: 'image', src: 'https://via.placeholder.com/140x140.png/eeeeee', name: '文件名称', selected: false},
        {type: 'image', src: 'https://via.placeholder.com/140x140.png/eeeeee', name: '文件名称', selected: false},
        {type: 'image', src: 'https://via.placeholder.com/140x140.png/eeeeee', name: '文件名称', selected: false},
      ]
    },
    // 计算属性
    computed: {
      // isFileSelected() {
      //   return this.files.some(file => file.selected);
      // },

      paneLengthValue() {
        return `calc(${this.paneLengthPercent}% - ${this.triggerLength / 2 + 'px'})`
      },
    },
    // 侦听器
    watch: {},
    // 组件方法
    methods: {
      handleNodeClick(e) {
        console.log(e)
        this.loading = true

        setTimeout(() => {
          this.loading = false
        },1000)
      },

      loadNode(node, resolve) {
        console.log(node, resolve)
        resolve(this.tree)
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
        this.editingFileIndex = index;
        this.files.map(e => !e.index ? e.selected = false : '')
        this.files[index].selected = !this.files[index].selected
      },

      fileChecked() {
        console.log(this.editingFileIndex)
      },

      deleteFile() {
        this.$confirm('是否要删除选中文件', '提示', {
          type: 'warning'
        }).then(() => {
          this.files.splice(this.editingFileIndex, 1);
          this.$message({type: 'success',message: '删除成功!'});
        }).catch(_=>{});
      },

      openInputBox(type, data) {
        console.log(data)
        this.$prompt('', '重命名', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          inputPattern: /^.+$/,
          inputErrorMessage: '不能为空'
        }).then(({ value }) => {
          this.$message({
            type: 'success',
            message: '你的邮箱是: ' + value
          });
        }).catch(() => {
          this.$message({
            type: 'info',
            message: '取消输入'
          });
        });
      }
    },
    // 在实例初始化之后，组件属性计算之前，如data属性等
    beforeCreate () {
    },
    // 在实例创建完成后被立即同步调用
    created () {
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
        app.editingFileIndex = null;
        app.files.map(e => e.selected = false)
      }
    })
  });
  </script>
</body>
</html>
<?php /**PATH /Users/pushuo/www/product/beikeshop/resources//beike/admin/views/pages/filemanager/index.blade.php ENDPATH**/ ?>
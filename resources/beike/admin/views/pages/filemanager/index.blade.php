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
  <div class="filemanager-wrap" id="filemanager-wrap-app" v-cloak>
    <div class="filemanager-navbar" ref='letfDom'>
      <el-tree :data="tree" :props="defaultProps" @node-click="handleNodeClick"></el-tree>
    </div>
    <div class="filemanager-divider" draggable="true" @dragstart="myFunction(event)" ref='moveDom'></div>
    <div class="filemanager-content">
      <div class="content-head">
        <div class="left">
          <el-link :underline="false" icon="el-icon-edit">下载</el-link>
          <el-link :underline="false" icon="el-icon-edit">删除</el-link>
          <el-link :underline="false" icon="el-icon-edit">重命名</el-link>
          {{-- <el-link :underline="false" icon="el-icon-edit">无下划线</el-link> --}}
          {{-- <el-link :underline="false" icon="el-icon-edit">无下划线</el-link> --}}
        </div>
        <div class="right"><el-button size="mini" type="primary">上传文件</el-button></div>
      </div>
      <div class="content-center">
        <div :class="['image-list', file.selected ? 'active' : '']" v-for="file, index in files" :key="index" @click="checkedImage(index)">
          <img :src="file.src">
          <div class="text">
            <span :title="file.name">@{{ file.name }}</span>
            <i v-if="file.selected" class="el-icon-check"></i>
          </div>
        </div>
      </div>
      <div class="content-footer">
        <div class="pagination-wrap">
          <el-pagination
            layout="prev, pager, next"
            :total="50">
          </el-pagination>
        </div>
      </div>
    </div>
  </div>

  <script>
  var app = new Vue({
    el: '#filemanager-wrap-app',
    components: {},
    data: {
      letfDom: null,
      clientStartX: 0,

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
    computed: {},
    // 侦听器
    watch: {},
    // 组件方法
    methods: {
      handleNodeClick(e) {
        console.log(e)
      },

      moveHandle(nowClientX, letfDom) {
        let computedX = nowClientX - this.clientStartX;
        let leftBoxWidth = parseInt(letfDom.style.width);
        let changeWidth = leftBoxWidth + computedX;

        if (changeWidth < 280) {
          changeWidth = 280;
        }

        if (changeWidth > 400) {
          changeWidth = 400;
        }

        letfDom.style.width = changeWidth + "px";

        this.clientStartX = nowClientX;
      },

      checkedImage(index) {
        this.files.map(e => !e.index ? e.selected = false : '')
        this.files[index].selected = !this.files[index].selected
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
      this.letfDom = this.$refs.letfDom;
      let moveDom = this.$refs.moveDom;

      moveDom.onmousedown = e => {
        this.clientStartX = e.clientX;
        e.preventDefault();

        document.onmousemove = e => {
          this.moveHandle(e.clientX, this.letfDom);
        };

        document.onmouseup = e => {
          document.onmouseup = null;
          document.onmousemove = null;
        };
      };
    },
  })
document.ondragover=function(e){
  e.preventDefault();
}
  </script>
</body>
</html>

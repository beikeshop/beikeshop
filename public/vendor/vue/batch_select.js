// 自 https://blog.wy310.cn/2020/12/17/vue-drag-batch-select/ 修改
Vue.directive('batch-select', {
  // 当被绑定的元素插入到 DOM 中时……
  // inserted: (el, binding) => {}
  componentUpdated: (el, binding) => {
    if (!app.isMultiple) {
      return;
    }
    // 设置被绑定元素el（即上述的box）的position为relative，目的是让蓝色半透明遮罩area相对其定位
    el.style.position = 'relative';
    // 记录el在视窗中的位置elPos
    const { x, y } = el.getBoundingClientRect()
    const elPos = { x, y }
    // 获取该指令调用者传递过来的参数：className / selectArr 是选中的 index 要放的数组
    // v-batch-select="{ className: '.el-checkbox', selectArr: [] }"
    // 表示要使用鼠标框选类名为'.el-checkbox'的元素
    const optionClassName = binding.value.className
    const options = [].slice.call(el.querySelectorAll(optionClassName))
    // 获取被框选对象们的x、y、width、height
    const optionsXYWH = []
    options.forEach(v => {
      const obj = v.getBoundingClientRect()
      optionsXYWH.push({
        x: obj.x - elPos.x,
        y: obj.y - elPos.y,
        w: obj.width,
        h: obj.height
      })
    })
    // 创建一个div作为area区域，注意定位是absolute，visibility初始值是hidden
    const area = document.createElement('div')
    area.style = 'position: absolute; border: 1px solid #409eff; background-color: rgba(64, 158, 255, 0.1); z-index: 10; visibility: hidden;'
    area.className = 'area'
    area.innerHTML = ''
    el.appendChild(area)
    el.onmousedown = (e) => {
      if (e.button !== 0) return
      let isContentCenter = false
      // 判断 鼠标按下时是否在元素上（div.content-center)
      if (e.target.className === 'content-center') {
        // 清空选中的图片
        binding.value.selectImageIndex.length = 0
        isContentCenter = true
      }

      // 获取 父元素 .image-list 在列表中的 index
      const $image = $(e.target).parents('.image-list');

      // 获取鼠标按下时相对box的坐标
      const startX = e.clientX - elPos.x
      const startY = e.clientY - elPos.y
      // 判断鼠标按下后是否发生移动的标识
      let hasMove = false

      document.onmousemove = (e) => {
        if ($image.index() != -1 && !$image.hasClass('active')) {
          binding.value.selectImageIndex.length = 0
          app.checkedImage($image.index())
          binding.value.selectImageIndex.push($image.index())
        }

        hasMove = true
        binding.value.setSelectStatus(true)

        if (isContentCenter) {
          // 显示area
          area.style.visibility = 'visible'
          // 获取鼠标移动过程中指针的实时位置
          const endX = e.clientX - elPos.x
          const endY = e.clientY - elPos.y
          // 这里使用绝对值是为了兼容鼠标从各个方向框选的情况
          const width = Math.abs(endX - startX)
          const height = Math.abs(endY - startY)
          // 根据初始位置和实时位置计算出area的left、top、width、height
          const left = Math.min(startX, endX)
          const top = Math.min(startY, endY)
          // 实时绘制
          area.style.left = `${left}px`
          area.style.top = `${top}px`
          area.style.width = `${width}px`
          area.style.height = `${height}px`
          const areaTop = parseInt(top)
          const areaRight = parseInt(left) + parseInt(width)
          const areaBottom = parseInt(top) + parseInt(height)
          const areaLeft = parseInt(left)
          binding.value.selectImageIndex.length = 0
          optionsXYWH.forEach((v, i) => {
            const optionTop = v.y
            const optionRight = v.x + v.w
            const optionBottom = v.y + v.h
            const optionLeft = v.x
            if (!(areaTop > optionBottom || areaRight < optionLeft || areaBottom < optionTop || areaLeft > optionRight)) {
              // 该指令的调用者可以监听到selectIdxs的变化
              binding.value.selectImageIndex.push(i)
            }
          })
        } else {
          $('.select-tip').css({
            left: `${e.clientX + 10}px`,
            top: `${e.clientY + 10}px`,
            display: 'flex'
          })
        }
      }

      document.onmouseup = (e) => {
        document.onmousemove = document.onmouseup = null
        if (hasMove) {
          // 鼠标抬起时，如果之前发生过移动，则执行碰撞检测
          const { left, top, width, height } = area.style
          setTimeout(() => {
            binding.value.setSelectStatus(false)
          }, 100);
        }

        const [path, name] = [$(e.target).attr('path'), $(e.target).attr('name')]

        if (path) {
          binding.value.imgMove(path, name, binding.value.selectImageIndex)
        }

        $('.select-tip').hide().html('')
        // 恢复以下数据
        hasMove = false
        isContentCenter = false
        area.style.visibility = 'hidden'
        area.style.left = 0
        area.style.top = 0
        area.style.width = 0
        area.style.height = 0
        return false
      }

      setTimeout(() => {
        const selectImages = binding.value.selectImageIndex.map(v => app.images[v])
        selectImages.forEach((v, i) => {
          $('.select-tip').append(`<div class="s-img">${v.mime == 'video/mp4' ? '<i class="el-icon-video-play""></i>' : '<img src=' + v.url + '></img>'}</div>`)
        })

        if (selectImages.length) {
          $('.select-tip').append(`<div class="quantity">${selectImages.length}</div>`)
        }
      }, 100);
    }
  },
  // update: (el, binding) => {}
})
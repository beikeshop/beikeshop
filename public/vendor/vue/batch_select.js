Vue.directive('batch-select', {
  componentUpdated: (el, binding) => {
    if (!app.isMultiple) return;

    el.style.position = 'relative';

    // 获取所有需要框选的元素
    const optionClassName = binding.value.className;
    const options = [].slice.call(el.querySelectorAll(optionClassName));

    // 获取框选元素的坐标（考虑滚动）
    const getOptionsXYWH = () => {
      const rect = el.getBoundingClientRect();
      return options.map(v => {
        const box = v.getBoundingClientRect();
        return {
          x: box.left - rect.left + el.scrollLeft,
          y: box.top - rect.top + el.scrollTop,
          w: box.width,
          h: box.height
        };
      });
    };
    let optionsXYWH = getOptionsXYWH();

    // 创建遮罩层（area），避免重复添加
    let area = el.querySelector('.area');
    if (!area) {
      area = document.createElement('div');
      area.style = 'position: absolute; border: 1px solid #409eff; background-color: rgba(64, 158, 255, 0.1); z-index: 10; visibility: hidden;';
      area.className = 'area';
      el.appendChild(area);
    }

    // 计算鼠标相对 el 的坐标（考虑滚动）
    const getRelativeMousePos = (e) => {
      const rect = el.getBoundingClientRect();
      return {
        x: e.clientX - rect.left + el.scrollLeft,
        y: e.clientY - rect.top + el.scrollTop
      };
    };

    el.onmousedown = (e) => {
      if (e.button !== 0) return;

      let isContentCenter = false;
      if (e.target.className === 'content-center') {
        binding.value.selectImageIndex.length = 0;
        isContentCenter = true;
      }

      const $image = $(e.target).parents('.image-list');
      const { x: startX, y: startY } = getRelativeMousePos(e);
      let hasMove = false;

      document.onmousemove = (e) => {
        if ($image.index() !== -1 && !$image.hasClass('active')) {
          binding.value.selectImageIndex.length = 0;
          app.checkedImage($image.index());
          binding.value.selectImageIndex.push($image.index());
        }

        hasMove = true;
        binding.value.setSelectStatus(true);

        if (isContentCenter) {
          area.style.visibility = 'visible';

          const { x: endX, y: endY } = getRelativeMousePos(e);
          const width = Math.abs(endX - startX);
          const height = Math.abs(endY - startY);
          const left = Math.min(startX, endX);
          const top = Math.min(startY, endY);

          area.style.left = `${left}px`;
          area.style.top = `${top}px`;
          area.style.width = `${width}px`;
          area.style.height = `${height}px`;

          const areaTop = top;
          const areaRight = left + width;
          const areaBottom = top + height;
          const areaLeft = left;

          binding.value.selectImageIndex.length = 0;
          optionsXYWH = getOptionsXYWH(); // 每次更新最新位置
          optionsXYWH.forEach((v, i) => {
            const optionTop = v.y;
            const optionRight = v.x + v.w;
            const optionBottom = v.y + v.h;
            const optionLeft = v.x;
            if (!(areaTop > optionBottom || areaRight < optionLeft || areaBottom < optionTop || areaLeft > optionRight)) {
              binding.value.selectImageIndex.push(i);
            }
          });
        } else {
          $('.select-tip').css({
            left: `${e.clientX + 10}px`,
            top: `${e.clientY + 10}px`,
            display: 'flex'
          });
        }
      };

      document.onmouseup = (e) => {
        document.onmousemove = document.onmouseup = null;
        if (hasMove) {
          setTimeout(() => {
            binding.value.setSelectStatus(false);
          }, 100);
        }

        const [path, name] = [$(e.target).attr('path'), $(e.target).attr('name')];
        if (path) {
          binding.value.imgMove(path, name, binding.value.selectImageIndex);
        }

        $('.select-tip').hide().html('');

        // 恢复
        hasMove = false;
        isContentCenter = false;
        area.style.visibility = 'hidden';
        area.style.left = 0;
        area.style.top = 0;
        area.style.width = 0;
        area.style.height = 0;
        return false;
      };

      setTimeout(() => {
        const selectImages = binding.value.selectImageIndex.map(v => app.images[v]);
        selectImages.forEach((v) => {
          $('.select-tip').append(`<div class="s-img">${v.mime === 'video/mp4' ? '<i class="el-icon-video-play"></i>' : '<img src=' + v.url + '></img>'}</div>`);
        });

        if (selectImages.length) {
          $('.select-tip').append(`<div class="quantity">${selectImages.length}</div>`);
        }
      }, 100);
    };
  }
});

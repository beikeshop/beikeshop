/*
 * @copyright     2022 beikeshop.com - All Rights Reserved.
 * @link          https://beikeshop.com
 * @Author        pu shuo <pushuo@guangda.work>
 * @Date          2022-08-22 18:32:26
 * @LastEditTime  2023-02-03 17:50:57
 */

export default {
  // 打开文件管理器
  fileManagerIframe(callback) {
    const base = document.querySelector('base').href;

    layer.open({
      type: 2,
      title: lang.file_manager,
      shadeClose: false,
      skin: 'file-manager-box',
      scrollbar: false,
      shade: 0.4,
      area: ['1060px', '680px'],
      content: `${base}/file_manager`,
      success: function(layerInstance, index) {
        var iframeWindow = window[layerInstance.find("iframe")[0]["name"]];
        iframeWindow.callback = function(images) {
          callback(images);
        }
      }
    });
  },

  // 防抖
  debounce(fn, delay) {
    var timeout = null; // 创建一个标记用来存放定时器的返回值

    return function (e) {
      // 每当用户输入的时候把前一个 setTimeout clear 掉
      clearTimeout(timeout);
      // 然后又创建一个新的 setTimeout, 这样就能保证interval 间隔内如果时间持续触发，就不会执行 fn 函数
      timeout = setTimeout(() => {
          fn.apply(this, arguments);
      }, delay);
    }
  },

  // 生成随机字符串
  randomString(length) {
    let str = '';
    for (; str.length < length; str += Math.random().toString(36).substr(2));
    return str.substr(0, length);
  },

  // 获取url参数
  getQueryString(name, defaultValue) {
    const reg = new RegExp('(^|&)' + name + '=([^&]*)(&|$)');
    const r = window.location.search.substr(1).match(reg);
    if (r != null) {
      return decodeURIComponent(r[2]);
    }

    return typeof(defaultValue) != 'undefined' ? defaultValue : '';
  },

  // 控制字符串长度 超出显示...
  stringLengthInte(text, length = 50) {
    if (text.length) {
      return text.slice(0, length) + (text.length > length ? '...' : '');
    }

    return '';
  },

  // 给列表页筛选开发插件使用，场景：开发者需要添加筛选条件，不需要到filter添加筛选key
  addFilterCondition(app) {
    if (location.search) {
      const params = location.search.substr(1).split('&');
      params.forEach(param => {
        const [key, value] = param.split('=');
        app.$set(app.filter, key, value);
      });
    }
  },

  // 将对象内不为空的转换为url参数 并 添加到url后面
  objectToUrlParams(obj, url) {
    const params = [];
    for (const key in obj) {
      if (obj[key] !== '') {
        params.push(`${key}=${obj[key]}`);
      }
    }

    return `${url}${params.length ? '?' : ''}${params.join('&')}`;
  },

  // 清空对象内的值
  clearObjectValue(obj) {
    for (const key in obj) {
      obj[key] = '';
    }

    return obj;
  },

  // 设置版本更新提示
  setVersionUpdateTips() {
    const version = JSON.parse(localStorage.getItem('beike_version'));
    if (version && version.has_new_version) {
      localStorage.setItem('version', process.env.VUE_APP_VERSION);
      $('.new-version').text(version.latest);
      $('.update-date').text(version.release_date);
      $('.update-btn').show();
    } else {
      $('.update-btn').hide();
    }
  }
}
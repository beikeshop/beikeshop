import http from "../../../js/http";
window.$http = http;
const base = document.querySelector('base').href;
const asset = document.querySelector('meta[name="asset"]').content;

$(document).on('click', '.open-file-manager', function(event) {
  const $this = $(this);

  layer.open({
    type: 2,
    title: '图片管理器',
    shadeClose: false,
    skin: 'file-manager-box',
    scrollbar: false,
    shade: 0.4,
    area: ['1060px', '680px'],
    content: `${base}/file_manager`,
    success: function(layerInstance, index) {
      var iframeWindow = window[layerInstance.find("iframe")[0]["name"]];
      iframeWindow.callback = function(images) {
        $this.find('img').prop('src', images[0].url);
        $this.next('input').val(images[0].path)
        $this.next('input')[0].dispatchEvent(new Event('input'));
      }
    }
  });
});

if (typeof Vue != 'undefined') {
  Vue.prototype.thumbnail = function thumbnail(image, width, height) {
    // 判断 image 是否以 http 开头
    if (image.indexOf('http') === 0) {
      return image;
    }

    return asset + image;
  };
}

$(document).ready(function ($) {
  $.ajaxSetup({
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    beforeSend: function() { layer.load(2, {shade: [0.3,'#fff'] }); },
    complete: function() { layer.closeAll('loading'); },
    error: function(xhr, ajaxOptions, thrownError) {
      if (xhr.responseJSON.message) {
        layer.msg(xhr.responseJSON.message,() => {})
      }
    },
  });
});

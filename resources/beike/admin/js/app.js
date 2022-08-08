import http from "../../../js/http";
window.$http = http;
const base = document.querySelector('base').href;
const asset = document.querySelector('meta[name="asset"]').content;
const editor_language = document.querySelector('meta[name="editor_language"]')?.content || 'zh_cn';

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

  tinymceInit()
});

function tinymceInit() {
  if (typeof tinymce == 'undefined') {
    return;
  }

  tinymce.init({
    selector: '.tinymce',
    language: editor_language,
    branding: false,
    height: 400,
    plugins: "link lists fullscreen table hr wordcount image imagetools code",
    menubar: "",
    toolbar: "undo redo | toolbarImageButton | bold italic underline strikethrough | forecolor backcolor | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent | numlist bullist | formatpainter removeformat | charmap emoticons | preview | template link anchor table toolbarImageUrlButton | fullscreen code",
    // contextmenu: "link image imagetools table",
    toolbar_items_size: 'small',
    image_caption: true,
    // imagetools_toolbar: 'imageoptions',
    toolbar_mode: 'wrap',
    font_formats:
      "微软雅黑='Microsoft YaHei';黑体=黑体;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Georgia=georgia,palatino;Helvetica=helvetica;Times New Roman=times new roman,times;Verdana=verdana,geneva",
    fontsize_formats: "10px 12px 14px 18px 24px 36px",
    relative_urls : true,
    setup:function(ed) {
      ed.ui.registry.addButton('toolbarImageButton',{
        // text: '',
        icon: 'image',
        onAction:function() {
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
                if (images.length) {
                  images.forEach(e => {
                    ed.insertContent(`<img src='/${e.path}' class="img-fluid" />`);
                  });
                }
              }
            }
          });
        }
      });
      // ed.on('change', function(e) {
      //   if (e.target.targetElm.dataset.key) {
      //     app.form[e.target.targetElm.dataset.key] = ed.getContent()
      //   }
      // });
    }
  });
}
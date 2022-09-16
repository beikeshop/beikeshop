/*
 * @copyright     2022 beikeshop.com - All Rights Reserved.
 * @link          https://beikeshop.com
 * @Author        pu shuo <pushuo@guangda.work>
 * @Date          2022-08-26 18:18:22
 * @LastEditTime  2022-09-16 20:58:01
 */

import http from "../../../js/http";
window.$http = http;
import common from "./common";
window.bk = common;
import "./autocomplete";
import "./bootstrap-validation";

const base = document.querySelector('base').href;
const asset = document.querySelector('meta[name="asset"]').content;
const editor_language = document.querySelector('meta[name="editor_language"]')?.content || 'zh_cn';

$(document).on('click', '.open-file-manager', function(event) {
  bk.fileManagerIframe(images => {
    if (!$(this).find('img').length) {
      $(this).append('<img src="' + images[0].url + '" class="img-fluid">');
      $(this).find('i').remove()
    } else {
      $(this).find('img').prop('src', images[0].url);
    }
    $(this).next('input').val(images[0].path)
    $(this).next('input')[0].dispatchEvent(new Event('input'));
  });
});

if (typeof Vue != 'undefined') {
  Vue.prototype.thumbnail = function thumbnail(image) {
    if (!image) {
      return 'image/placeholder.png';
    }

    // 判断 image 是否以 http 开头
    if (image.indexOf('http') === 0) {
      return image;
    }

    return asset + image;
  };

  Vue.prototype.stringLengthInte = function stringLengthInte(text, length) {
    return bk.stringLengthInte(text, length)
  };
}

$(document).ready(function ($) {
  $.ajaxSetup({
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    // beforeSend: function() { layer.load(2, {shade: [0.3,'#fff'] }); },
    // complete: function() { layer.closeAll('loading'); },
    error: function(xhr, ajaxOptions, thrownError) {
      if (xhr.responseJSON.message) {
        layer.msg(xhr.responseJSON.message,() => {})
      }
    },
  });

  tinymceInit()
});

const tinymceInit = () => {
  if (typeof tinymce == 'undefined') {
    return;
  }

  tinymce.init({
    selector: '.tinymce',
    language: editor_language,
    branding: false,
    height: 400,
    convert_urls: false,
    // document_base_url: 'ssssss',
    inline: false,
    relative_urls: false,
    plugins: "link lists fullscreen table hr wordcount image imagetools code",
    menubar: "",
    toolbar: "undo redo | toolbarImageButton | lineheight | bold italic underline strikethrough | forecolor backcolor | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent | numlist bullist | formatpainter removeformat | charmap emoticons | preview | template link anchor table toolbarImageUrlButton | fullscreen code",
    // contextmenu: "link image imagetools table",
    toolbar_items_size: 'small',
    image_caption: true,
    // imagetools_toolbar: 'imageoptions',
    toolbar_mode: 'wrap',
    font_formats:
      "微软雅黑='Microsoft YaHei';黑体=黑体;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Georgia=georgia,palatino;Helvetica=helvetica;Times New Roman=times new roman,times;Verdana=verdana,geneva",
    fontsize_formats: "10px 12px 14px 18px 24px 36px",
    setup: function(ed) {
      const height = ed.getElement().dataset.tinymceHeight;
      // console.log(ed);
      // 修改 tinymce 的高度
      // if (height) {
        // ed.theme.resizeTo(null, height);
      // }

      ed.ui.registry.addButton('toolbarImageButton',{
        // text: '',
        icon: 'image',
        onAction:function() {
          bk.fileManagerIframe(images => {
            if (images.length) {
              images.forEach(e => {
                ed.insertContent(`<img src='/${e.path}' class="img-fluid" />`);
              });
            }
          })
        }
      });
    }
  });
}


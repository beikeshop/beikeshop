/*
 * @copyright     2022 beikeshop.com - All Rights Reserved.
 * @link          https://beikeshop.com
 * @Author        pu shuo <pushuo@guangda.work>
 * @Date          2022-08-26 18:18:22
 * @LastEditTime  2023-07-14 18:15:09
 */

import http from "../../../js/http";
window.$http = http;
import common from "./common";
window.bk = common;
import "./autocomplete";
import "./header";
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
  bk.versionUpdateTips();

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

  autoActiveTab()
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
    fontsize_formats: "10px 12px 14px 18px 24px 36px 48px 56px 72px 96px",
    lineheight_formats: "1 1.1 1.2 1.3 1.4 1.5 1.7 2.4 3 4",
    setup: function(ed) {
      ed.ui.registry.addButton('toolbarImageButton',{
        icon: 'image',
        onAction:function() {
          bk.fileManagerIframe(images => {
            if (images.length) {
              images.forEach(e => {
                if (e.mime == 'video/mp4') {
                  ed.insertContent(`<video src='/${e.path}' controls loop muted class="img-fluid" />`);
                } else {
                  ed.insertContent(`<img src='/${e.path}' class="img-fluid" />`);
                }
              });
            }
          })
        }
      });
    }
  });
}

const autoActiveTab = () => {
  const tab = bk.getQueryString('tab');

  if (tab) {
    if ($(`a[href="#${tab}"]`).length) {
      $(`a[href="#${tab}"]`)[0].click()
      return;
    }

    if ($(`button[data-bs-target="#${tab}"]`).length) {
      $(`button[data-bs-target="#${tab}"]`)[0].click()
    }
  }
}

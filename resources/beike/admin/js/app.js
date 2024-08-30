/*
 * @copyright     2022 beikeshop.com - All Rights Reserved.
 * @link          https://beikeshop.com
 * @Author        pu shuo <pushuo@guangda.work>
 * @Date          2022-08-26 18:18:22
 * @LastEditTime  2024-08-29 23:33:18
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

    if (image.indexOf('.mp4') !== -1) {
      return asset + 'image/video-icon.png';
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
  inputLocaleTranslate()
  checkRemoveCopyRight()
  pageBottomBtns()
});

const inputLocaleTranslate = () => {
  $('.translate-btn').click(function () {
    var from = $(this).siblings('.from-locale-code').val();
    var to = $(this).siblings('.to-locale-code').val();
    let $parents = $(this).parents('.input-locale-wrap').length ? $(this).parents('.input-locale-wrap') : $(this).parents('.col-auto');
    var text = $parents.find('.input-' + from).val();
    if (!text) {
      return layer.msg(lang.translate_form, () => {});
    }

    // 发请求之前删除所有错样式
    $http.post('translation', {from, to, text}).then((res) => {
      $('.translation-error-text').remove()

      res.data.forEach((e) => {
        $parents.find('.input-' + e.locale).removeClass('translation-error');

        if (e.error) {
          $parents.find('.input-' + e.locale).addClass('translation-error');
          $parents.find('.input-' + e.locale).parents('.input-for-group').after('<div class="invalid-feedback translation-error-text mb-1 d-block" style="margin-left: 86px">' + e.error + '</div>');
        } else {
          $parents.find('.input-' + e.locale).val(e.result);
        }
      });
    })
  });
}

const tinymceInit = () => {
  if (typeof tinymce == 'undefined') {
    return;
  }

  tinymce.init({
    selector: '.tinymce',
    language: editor_language,
    branding: false,
    height: 500,
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
    imagetools_toolbar: '',
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
                  ed.insertContent(`<video src='${e.path}' controls loop muted class="img-fluid" />`);
                } else {
                  ed.insertContent(`<img src='${e.path}' class="img-fluid" />`);
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

const pageBottomBtns = () => {
  if ($('.page-bottom-btns').length && $('.page-bottom-btns').html().trim()) {
    const contentInfoTop = $('.content-info').offset().top + $('.content-info').height();

    $('#content').css({'padding-bottom': '6rem'})
    $('.page-bottom-btns').css({'left': $('#content').offset().left})
    $('.page-bottom-btns').fadeIn(150)
  }
}

// 检查是否非法移除版权
const checkRemoveCopyRight = () => {
  let isRemove = false;

  // 被注释或删除
  if (!$('#copyright-text').length) {
    isRemove = true;
  }

  // 被隐藏
  if ($('#copyright-text').css('display') === 'none') {
    isRemove = true;
  }

  // 被去除版权中 BeikeShop 文字
  if ($('#copyright-text').text().indexOf('BeikeShop') === -1) {
    isRemove = true;
  }

  if (!config.has_license && isRemove) {
    $('.warning-copyright').removeClass('d-none')
    if (!$('.warning-copyright').length) {
      $('.header-content .header-right .navbar-right').prepend('<div class="alert alert-warning mb-0 warning-copyright"><i class="bi bi-exclamation-triangle-fill"></i> 请保留网站底部版权，或前往 <a href="https://beikeshop.com/vip/subscription?type=tab-license" target="_blank">购买授权</a></div>')
    }
  }
}
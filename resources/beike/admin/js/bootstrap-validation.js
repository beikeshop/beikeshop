/*
 * @copyright     2022 beikeshop.com - All Rights Reserved.
 * @link          https://beikeshop.com
 * @Author        pu shuo <pushuo@guangda.work>
 * @Date          2022-08-17 15:42:46
 * @LastEditTime  2023-10-18 17:46:11
 */

// Example starter JavaScript for disabling form submissions if there are invalid fields
$(function () {
  var forms = document.querySelectorAll(".needs-validation");

  // 触发表单提交
  $(document).on('click', '.submit-form', function(event) {
    const form = $(this).attr('form');

    if ($(`form#${form}`).find('button[type="submit"]').length > 0) {
      $(`form#${form}`).find('button[type="submit"]')[0].click();
    } else {
      $(`form#${form}`).submit();
    }
  });

  // 表单保存统一添加加载动画
  $(document).on('submit', 'form', function(event) {
    if (!$(this).hasClass('no-load')) {
      layer.load(2, { shade: [0.2, '#fff'] });
    }
  });

  // Loop over them and prevent submission
  Array.prototype.slice.call(forms).forEach(function (form) {
    form.addEventListener(
      "submit",
      function (event) {
        if (!form.checkValidity()) {
          event.preventDefault();
          event.stopPropagation();
        }

        form.classList.add("was-validated");
        $('.nav-link, .nav-item').removeClass('error-invalid');
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').removeClass('d-block');

        // 如果错误输入框在 tab 页面，则高亮显示对应的选项卡
        $('.invalid-feedback').each(function(index, el) {
          if ($(el).css('display') == 'block') {
            layer.msg(lang.error_form, () => {});

            // 兼容使用 element ui input、autocomplete 组件，在传统提交报错ui显示
            if ($(el).siblings('div[class^="el-"]')) {
              $(el).siblings('div[class^="el-"]').find('.el-input__inner').addClass('error-invalid-input')
            }

            if ($(el).parents('.tab-pane')) {
              //高亮显示对应的选项卡
              $(el).parents('.tab-pane').each(function(index, el) {
                const id = $(el).prop('id');
                $(`a[href="#${id}"], button[data-bs-target="#${id}"]`).addClass('error-invalid')[0].click();
              })
            }

            // 页面滚动到错误输入框位置 只滚动一次
            if ($('.main-content > #content').data('scroll') != 1) {
              $('.main-content > #content').data('scroll', 1);
              setTimeout(() => {
                $('.main-content > #content').animate({
                  scrollTop: $(el).offset().top - 100
                }, 500, () => {
                  $('.main-content > #content').data('scroll', 0);
                });
              }, 200);
            }
          }
        });
      },
      false
    );
  });
});

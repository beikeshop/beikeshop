/*
 * @copyright     2022 beikeshop.com - All Rights Reserved.
 * @link          https://beikeshop.com
 * @Author        pu shuo <pushuo@guangda.work>
 * @Date          2022-08-19 18:21:32
 * @LastEditTime  2023-11-30 11:12:53
 */

$(document).on('click', '.quantity-wrap .right i, .quantity-wrap-line .right i', function(event) {
  event.stopPropagation();
  event.preventDefault();

  let input = $(this).parent().siblings('input')

  if ($(this).hasClass('bi-chevron-up')) {
    input.val(input.val() * 1 + 1)
    input.get(0).dispatchEvent(new Event('input'));
    return;
  }

  if (input.val() * 1 <= input.attr('minimum') * 1) {
    return;
  }

  if (input.val () * 1 <= 1) {
    return;
  }

  input.val(input.val() * 1 - 1)
  input.get(0).dispatchEvent(new Event('input'));
});

$(document).on('click','.login-before-show-price',function () {
  bk.openLogin()
})
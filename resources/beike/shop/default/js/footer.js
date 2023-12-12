/*
 * @copyright     2023 beikeshop.com - All Rights Reserved.
 * @link          https://beikeshop.com
 * @Author        pu shuo <pushuo@guangda.work>
 * @Date          2023-11-22 09:51:04
 * @LastEditTime  2023-11-22 11:45:21
 */

$(document).on('click', '.footer-link-wrap > h6', function () {
  $(this).parent('.footer-link-wrap').toggleClass('active');
})
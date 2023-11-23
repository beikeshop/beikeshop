/*
 * @copyright     2022 beikeshop.com - All Rights Reserved.
 * @link          https://beikeshop.com
 * @Author        pu shuo <pushuo@guangda.work>
 * @Date          2022-08-29 17:32:51
 * @LastEditTime  2023-11-30 10:22:18
 */

import http from "../../../../js/http";
import common from "./common";
window.bk = common;
window.$http = http;

const token = document.querySelector('meta[name="csrf-token"]').content;
const base = document.querySelector('base').href;

import './product';
import './header'
import './footer'
import './bootstrap-validation'

$(document).ready(function ($) {
  if ($(window).width() > 992 && $('.x-fixed-top').length) {
    $('.x-fixed-top').scrollToFixed({
      zIndex: 99,
      marginTop: $('.header-content').outerHeight(true) - 18 || 0,
      limit: function () {
        var limit = $('footer').offset().top - 84 - $('.x-fixed-top').outerHeight(true);
        return limit
      }
    });
  }

  const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
  const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))

  bk.productImageResize11()
  window.addEventListener('resize', function () {
    bk.productImageResize11()
  })
});

bk.getCarts(); // 页面初始加载购物车数据
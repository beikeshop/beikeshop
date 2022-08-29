import http from "../../../../js/http";
import common from "./common";
window.bk = common;
window.$http = http;

const token = document.querySelector('meta[name="csrf-token"]').content;
const base = document.querySelector('base').href;

import './product';
import './header'
import './bootstrap-validation'

$(document).ready(function ($) {
  $(document).on('click', '.offcanvas-products-delete', function () {
    const id = $(this).data('id');

    $http.delete(`carts/${id}`).then((res) => {
      $(this).parents('.product-list').remove();
      console.log(res.data.quantity);
      if (!res.data.quantity) {
        $('.cart-badge-quantity').hide();
      } else {
        $('.cart-badge-quantity').show().html(res.data.quantity > 99 ? '99+' : res.data.quantity);
      }

      $('.offcanvas-right-cart-count').text(res.data.quantity);
      $('.offcanvas-right-cart-amount').text(res.data.amount_format);
    })
  })
});

bk.getCarts(); // 页面初始加载购物车数据
bk.slidingFixed();
import http from "../../../../js/http";
import common from "./common";
window.bk = common;
window.$http = http;

const token = document.querySelector('meta[name="csrf-token"]').content;
const base = document.querySelector('base').href;

import './product';
import './header'

$(document).ready(function ($) {
  $.ajaxSetup({
    headers: {'X-CSRF-TOKEN': token},
    // beforeSend: function() { layer.load(2, {shade: [0.3,'#fff'] }); },
    // complete: function() { layer.closeAll('loading'); },
    error: function(xhr, ajaxOptions, thrownError) {
      if (xhr.responseJSON.message) {
        layer.msg(xhr.responseJSON.message,() => {})
      }
    },
  });
});

bk.getCarts(); // 页面初始加载购物车数据
bk.slidingFixed();
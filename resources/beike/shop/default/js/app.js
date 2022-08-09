import http from "../../../../js/http";
import common from "./common";
window.bk = common;
window.$http = http;

import './product';
import './header'

bk.getCarts(); // 页面初始加载购物车数据
bk.slidingFixed();
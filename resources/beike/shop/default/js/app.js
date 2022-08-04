import http from "../../../../js/http";
window.$http = http;

import './global';
import './product';

bk.getCarts(); // 页面初始加载购物车数据
bk.slidingFixed();
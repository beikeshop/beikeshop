/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/beike/shop/default/js/http.js":
/*!*************************************************!*\
  !*** ./resources/beike/shop/default/js/http.js ***!
  \*************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* provided dependency */ var process = __webpack_require__(/*! process/browser.js */ "./node_modules/process/browser.js");
function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

var instance = axios.create({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  } // baseURL: 'https://api.example.com'

}); // import axios from "axios";
// import {Message} from 'element-ui';
// import QS from 'qs';

axios.defaults.timeout = 5000; // 请求超时
// axios.defaults.baseURL = process.env.NODE_ENV == 'production' ? process.env.VUE_APP_BASE_URL + '/' : '/';

axios.defaults.baseURL = process.env.VUE_APP_BASE_URL; // axios.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded';
// axios.defaults.headers.post['Content-Type'] = 'application/json';

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  /**
   * get 请求
   * @param url 接口路由
   * @returns {AxiosPromise<any>}
   */
  get: function get(url, params) {
    var _ref = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {},
        hmsg = _ref.hmsg,
        hload = _ref.hload;

    return this.request('get', url, params = params, {
      hmsg: hmsg,
      hload: hload
    });
  },

  /**
   * post 请求
   *
   * @param url 接口路由
   * @param params 接口参数
   * @returns {AxiosPromise<any>}
   */
  post: function post(url, params) {
    var _ref2 = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {},
        hmsg = _ref2.hmsg,
        hload = _ref2.hload;

    return this.request('post', url, params, {
      hmsg: hmsg,
      hload: hload
    });
  },

  /**
  * delete 方法封装
  * @param url
  * @param params
  * @returns {Promise}
  */
  "delete": function _delete(url, params) {
    var _ref3 = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {},
        hmsg = _ref3.hmsg,
        hload = _ref3.hload;

    return this.request('delete', url, params, {
      hmsg: hmsg,
      hload: hload
    });
  },

  /**
  * put 方法封装
  * @param url
  * @param params
  * @returns {Promise}
  */
  put: function put(url, params) {
    var _ref4 = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {},
        hmsg = _ref4.hmsg,
        hload = _ref4.hload;

    return this.request('put', url, params, {
      hmsg: hmsg,
      hload: hload
    });
  },

  /**
   * 网络请求
   * @param method 方法
   * @param url 接口地址
   * @param params 参数
   * @param showError 是否展示错误信息
   * @returns {Promise<any>}
   */
  // 错误和失败信息都在这里进行处理，界面中调用的时候只处理正确数据即可
  request: function request(method, url) {
    var params = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};

    var _ref5 = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : {},
        hmsg = _ref5.hmsg,
        hload = _ref5.hload;

    if (!hload) {
      layer.load(2, {
        shade: [0.3, '#fff']
      });
    }

    return new Promise(function (resolve, reject) {
      axios(_defineProperty({
        method: method,
        url: url
      }, method == 'get' ? 'params' : 'data', params)).then(function (res) {
        console.log(res);

        if (res) {
          resolve(res.data);
        } else {
          // 其他情况返回错误信息，根据需要处理
          reject(res.data);
          if (!hmsg) return layer.msg(res.data.message, function () {});
        }
      })["catch"](function (res) {
        reject(res);

        if (!hmsg) {
          layer.msg(res.response.data.message || res.message, function () {});
        }
      })["finally"](function () {
        layer.closeAll('loading');
      });
    });
  }
});

/***/ }),

/***/ "./node_modules/process/browser.js":
/*!*****************************************!*\
  !*** ./node_modules/process/browser.js ***!
  \*****************************************/
/***/ ((module) => {

// shim for using process in browser
var process = module.exports = {};

// cached from whatever global is present so that test runners that stub it
// don't break things.  But we need to wrap it in a try catch in case it is
// wrapped in strict mode code which doesn't define any globals.  It's inside a
// function because try/catches deoptimize in certain engines.

var cachedSetTimeout;
var cachedClearTimeout;

function defaultSetTimout() {
    throw new Error('setTimeout has not been defined');
}
function defaultClearTimeout () {
    throw new Error('clearTimeout has not been defined');
}
(function () {
    try {
        if (typeof setTimeout === 'function') {
            cachedSetTimeout = setTimeout;
        } else {
            cachedSetTimeout = defaultSetTimout;
        }
    } catch (e) {
        cachedSetTimeout = defaultSetTimout;
    }
    try {
        if (typeof clearTimeout === 'function') {
            cachedClearTimeout = clearTimeout;
        } else {
            cachedClearTimeout = defaultClearTimeout;
        }
    } catch (e) {
        cachedClearTimeout = defaultClearTimeout;
    }
} ())
function runTimeout(fun) {
    if (cachedSetTimeout === setTimeout) {
        //normal enviroments in sane situations
        return setTimeout(fun, 0);
    }
    // if setTimeout wasn't available but was latter defined
    if ((cachedSetTimeout === defaultSetTimout || !cachedSetTimeout) && setTimeout) {
        cachedSetTimeout = setTimeout;
        return setTimeout(fun, 0);
    }
    try {
        // when when somebody has screwed with setTimeout but no I.E. maddness
        return cachedSetTimeout(fun, 0);
    } catch(e){
        try {
            // When we are in I.E. but the script has been evaled so I.E. doesn't trust the global object when called normally
            return cachedSetTimeout.call(null, fun, 0);
        } catch(e){
            // same as above but when it's a version of I.E. that must have the global object for 'this', hopfully our context correct otherwise it will throw a global error
            return cachedSetTimeout.call(this, fun, 0);
        }
    }


}
function runClearTimeout(marker) {
    if (cachedClearTimeout === clearTimeout) {
        //normal enviroments in sane situations
        return clearTimeout(marker);
    }
    // if clearTimeout wasn't available but was latter defined
    if ((cachedClearTimeout === defaultClearTimeout || !cachedClearTimeout) && clearTimeout) {
        cachedClearTimeout = clearTimeout;
        return clearTimeout(marker);
    }
    try {
        // when when somebody has screwed with setTimeout but no I.E. maddness
        return cachedClearTimeout(marker);
    } catch (e){
        try {
            // When we are in I.E. but the script has been evaled so I.E. doesn't  trust the global object when called normally
            return cachedClearTimeout.call(null, marker);
        } catch (e){
            // same as above but when it's a version of I.E. that must have the global object for 'this', hopfully our context correct otherwise it will throw a global error.
            // Some versions of I.E. have different rules for clearTimeout vs setTimeout
            return cachedClearTimeout.call(this, marker);
        }
    }



}
var queue = [];
var draining = false;
var currentQueue;
var queueIndex = -1;

function cleanUpNextTick() {
    if (!draining || !currentQueue) {
        return;
    }
    draining = false;
    if (currentQueue.length) {
        queue = currentQueue.concat(queue);
    } else {
        queueIndex = -1;
    }
    if (queue.length) {
        drainQueue();
    }
}

function drainQueue() {
    if (draining) {
        return;
    }
    var timeout = runTimeout(cleanUpNextTick);
    draining = true;

    var len = queue.length;
    while(len) {
        currentQueue = queue;
        queue = [];
        while (++queueIndex < len) {
            if (currentQueue) {
                currentQueue[queueIndex].run();
            }
        }
        queueIndex = -1;
        len = queue.length;
    }
    currentQueue = null;
    draining = false;
    runClearTimeout(timeout);
}

process.nextTick = function (fun) {
    var args = new Array(arguments.length - 1);
    if (arguments.length > 1) {
        for (var i = 1; i < arguments.length; i++) {
            args[i - 1] = arguments[i];
        }
    }
    queue.push(new Item(fun, args));
    if (queue.length === 1 && !draining) {
        runTimeout(drainQueue);
    }
};

// v8 likes predictible objects
function Item(fun, array) {
    this.fun = fun;
    this.array = array;
}
Item.prototype.run = function () {
    this.fun.apply(null, this.array);
};
process.title = 'browser';
process.browser = true;
process.env = {};
process.argv = [];
process.version = ''; // empty string to avoid regexp issues
process.versions = {};

function noop() {}

process.on = noop;
process.addListener = noop;
process.once = noop;
process.off = noop;
process.removeListener = noop;
process.removeAllListeners = noop;
process.emit = noop;
process.prependListener = noop;
process.prependOnceListener = noop;

process.listeners = function (name) { return [] }

process.binding = function (name) {
    throw new Error('process.binding is not supported');
};

process.cwd = function () { return '/' };
process.chdir = function (dir) {
    throw new Error('process.chdir is not supported');
};
process.umask = function() { return 0; };


/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be in strict mode.
(() => {
"use strict";
/*!************************************************!*\
  !*** ./resources/beike/shop/default/js/app.js ***!
  \************************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _http__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./http */ "./resources/beike/shop/default/js/http.js");
// const instance = axios.create({
//   headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
//   // baseURL: 'https://api.example.com'
// });

window.$http = _http__WEBPACK_IMPORTED_MODULE_0__["default"];
$(document).ready(function ($) {
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    beforeSend: function beforeSend() {
      layer.load(2, {
        shade: [0.3, '#fff']
      });
    },
    complete: function complete() {
      layer.closeAll('loading');
    }
  });
  $('.quantity-wrap .right i').on('click', function (event) {
    event.stopPropagation();
    var input = $(this).parent().siblings('input');

    if ($(this).hasClass('bi-chevron-up')) {
      input.val(input.val() * 1 + 1);
      input.get(0).dispatchEvent(new Event('input'));
      return;
    }

    if (input.val() * 1 <= input.attr('minimum') * 1) {
      return;
    }

    input.val(input.val() * 1 - 1);
    input.get(0).dispatchEvent(new Event('input'));
  }); // 滑动固定顶部

  (function ($) {
    if (!$('.fixed-top-line').length) return;
    if ($(window).width() < 768) return;
    var totalWrapTop = $('.fixed-top-line').offset().top;
    var totalWrapWidth = $('.fixed-top-line').outerWidth();
    var totalWrapHeight = $('.fixed-top-line').outerHeight();
    var totalWrapLeft = $('.fixed-top-line').offset().left;
    var footerTop = $('footer').offset().top;
    var footerMarginTop = Math.abs(parseInt($('footer').css("marginTop")));
    $(window).scroll(function () {
      if ($(this).scrollTop() > totalWrapTop) {
        $('.fixed-top-line').css({
          position: 'fixed',
          top: 0,
          bottom: 'auto',
          'width': totalWrapWidth
        });

        if (!$('.total-old').length) {
          $('.fixed-top-line').before('<div class="total-old" style="height:' + totalWrapHeight + 'px; width:100%;"></div>');
        }

        if ($(this).scrollTop() + totalWrapHeight > footerTop - footerMarginTop) {
          $('.fixed-top-line').css({
            position: 'absolute',
            top: 'auto',
            bottom: '0',
            'width': totalWrapWidth
          });
        }
      } else {
        $('.total-old').remove();
        $('.fixed-top-line').removeAttr('style');
      } // if ($(this).scrollTop() > totalWrapTop) {
      //   $('.fixed-top-line').addClass('fixed-top-line-fixed').css({'left': totalWrapLeft, 'width': totalWrapWidth})
      //   if ($('.total-old').length > 0) return;
      //   $('.fixed-top-line').before('<div class="total-old" style="height:' + totalWrapHeight + 'px; width:100%;"></div>');
      // } else {
      //   $('.total-old').remove();
      //   $('.fixed-top-line').removeClass('fixed-top-line-fixed').css({'left': 0, 'width': 'auto'})
      // }

    });
  })(window.jQuery);
});
})();

/******/ })()
;
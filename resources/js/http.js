/*
 * @copyright     2022 beikeshop.com - All Rights Reserved.
 * @link          https://beikeshop.com
 * @Author        guangda <service@guangda.work>
 * @Date          2022-08-02 19:19:52
 * @LastEditTime  2024-12-05 00:07:57
 */

window.axios = require('axios');
const { resolveHttpErrorMessage, shouldShowNginxAlert, shouldPromptLoginRedirect } = require('./http-error-helper');

const axiosApi = axios.create({
  baseURL: document.querySelector('base').href, // 自动设置 base
  timeout: 0,
});

function resolveUiText() {
  const isZh = String(document.documentElement.lang || '').toLowerCase().startsWith('zh');
  return isZh
    ? {title: '提示', login: '去登录', cancel: '稍后再说'}
    : {title: 'Notice', login: 'Sign In', cancel: 'Later'};
}

function resolveLoginUrl() {
  const baseHref = document.querySelector('base')?.href || window.location.origin + '/';
  return new URL('login', baseHref).toString();
}

function isOnLoginPage() {
  return /\/login\/?$/.test(window.location.pathname);
}

function showAuthRedirectDialog(message) {
  const ui = resolveUiText();
  layer.confirm(message, {
    icon: 0,
    title: ui.title,
    btn: [ui.login, ui.cancel]
  }, function(index) {
    layer.close(index);
    window.location.href = resolveLoginUrl();
  }, function(index) {
    layer.close(index);
  });
}

export default {
  /**
   * get 请求
   * @param url 接口路由
   * @returns {AxiosPromise<any>}
   */
  axiosApi,

  get (url, params, {hmsg, hload, base}={}) {
    return this.request('get', url, params = params, {hmsg, hload, base});
  },

  /**
   * post 请求
   *
   * @param url 接口路由
   * @param params 接口参数
   * @returns {AxiosPromise<any>}
   */

  post (url, params, {hmsg, hload, base}={}) {
    return this.request('post', url, params, {hmsg, hload, base});
  },

  /**
  * delete 方法封装
  * @param url
  * @param params
  * @returns {Promise}
  */

  delete (url, params, {hmsg, hload, base}={}) {
    return this.request('delete', url, params, {hmsg, hload, base});
  },

  /**
  * put 方法封装
  * @param url
  * @param params
  * @returns {Promise}
  */

  put (url, params, {hmsg, hload, base}={}) {
    return this.request('put', url, params, {hmsg, hload, base});
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
  request(method, url, params = {}, {hmsg, hload, base} = {}) {
    if (!hload) {
      layer.load(2, {shade: [0.3,'#fff'] })
    }

    if (base) {
      axiosApi.defaults.baseURL = base;
    }

    return new Promise((resolve, reject) => {
      axiosApi({method: method, url: url, [method === 'get' ? 'params' : 'data']: params}).then((res) => {
        if (res) {
          resolve(res.data);
        } else { // 其他情况返回错误信息，根据需要处理
          const fallbackError = {response: {data: res && res.data ? res.data : null}};
          reject(fallbackError);
          if (!hmsg) {
            return layer.msg(
              resolveHttpErrorMessage(fallbackError, {locale: document.documentElement.lang}),
              () => {}
            );
          }
        }
      }).catch((error) => {
        reject(error);
        if (!hmsg) {
          if (shouldShowNginxAlert(error)) {
            layer.open({
              type: 1,
              title: false,
              area: '350px',
              content: `<div class="p-3">${$('.nginx-alert').html()}</div>`
            });
            return;
          }

          const message = resolveHttpErrorMessage(error, {locale: document.documentElement.lang});
          if (shouldPromptLoginRedirect(error) && !isOnLoginPage()) {
            showAuthRedirectDialog(message);
            return;
          }

          layer.msg(message, {time: 3000}, ()=>{});
        }
      }).finally(() => {
        layer.closeAll('loading')
      });
    });
  }
}

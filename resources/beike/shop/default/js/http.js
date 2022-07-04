const instance = axios.create({
  headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
  // baseURL: 'https://api.example.com'
});
// import axios from "axios";
// import {Message} from 'element-ui';
// import QS from 'qs';
axios.defaults.timeout = 5000; // 请求超时
// axios.defaults.baseURL = process.env.NODE_ENV == 'production' ? process.env.VUE_APP_BASE_URL + '/' : '/';
axios.defaults.baseURL = process.env.VUE_APP_BASE_URL;
// axios.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded';
// axios.defaults.headers.post['Content-Type'] = 'application/json';
export default {
  /**
   * get 请求
   * @param url 接口路由
   * @returns {AxiosPromise<any>}
   */
  get (url, params, {hmsg, hload}={}) {
    return this.request('get', url, params = params, {hmsg, hload});
  },

  /**
   * post 请求
   *
   * @param url 接口路由
   * @param params 接口参数
   * @returns {AxiosPromise<any>}
   */

  post (url, params, {hmsg, hload}={}) {
    return this.request('post', url, params, {hmsg, hload});
  },

  /**
  * delete 方法封装
  * @param url
  * @param params
  * @returns {Promise}
  */

  delete (url, params, {hmsg, hload}={}) {
    return this.request('delete', url, params, {hmsg, hload});
  },

  /**
  * put 方法封装
  * @param url
  * @param params
  * @returns {Promise}
  */

  put (url, params, {hmsg, hload}={}) {
    return this.request('put', url, params, {hmsg, hload});
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
  request(method, url, params = {}, {hmsg, hload} = {}) {
    if (!hload) {
      layer.load(2, {shade: [0.3,'#fff'] })
    }

    return new Promise((resolve, reject) => {
      axios({method: method, url: url, [method == 'get' ? 'params' : 'data']: params}).then((res) => {
        if (res) {
          resolve(res.data);
        } else { // 其他情况返回错误信息，根据需要处理
          reject(res.data);
          if (!hmsg) return layer.msg(res.data.message, ()=>{});
        }
      }).catch((res) => {
        reject(res);
        if (!hmsg && res.message) {
          layer.msg(res.response.data.message, ()=>{});
        }
      }).finally(function(){
        layer.closeAll('loading')
      });
    });
  }
}
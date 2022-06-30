@extends('layout.master')

@section('body-class', 'page-checkout')

@push('header')
  <script src="{{ asset('vendor/vue/2.6.14/vue.js') }}"></script>
  <script src="{{ asset('vendor/element-ui/2.15.6/js.js') }}"></script>
  <link rel="stylesheet" href="{{ asset('vendor/element-ui/2.15.6/css.css') }}">
@endpush

@section('content')
  <div class="container" id="checkout-app" v-cloak>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Library</li>
      </ol>
    </nav>

    <div class="row justify-content-center">
      <div class="col-12 col-md-9">@include('shared.steps', ['steps' => 2])</div>
    </div>

    <div class="row mt-5">
      <div class="col-12 col-md-8">
        <form action="">
          <div class="checkout-address">
            <h4 class="title">地址</h4>
{{--             <div class="row mb-3">
              <div class="col-12 col-md-6 mb-3">
                <div class="form-floating">
                  <input type="text" name="email" class="form-control" value="" placeholder="姓名">
                  <label class="form-label" for="email_1">姓名</label>
                </div>
              </div>
              <div class="col-12 col-md-6 mb-3">
                <div class="form-floating">
                  <input type="text" name="email" class="form-control" value="" placeholder="电话">
                  <label class="form-label" for="email_1">电话</label>
                </div>
              </div>
              <div class="col-12 col-md-4 mb-3">
                <div class="form-floating">
                  <select class="form-select" aria-label="Default select example" name="county">
                    <option value="1">One</option>
                    <option value="2">Two</option>
                    <option value="3">Three</option>
                  </select>
                  <label class="form-label" for="email_1">county</label>
                </div>
              </div>
              <div class="col-12 col-md-4 mb-3">
                <div class="form-floating">
                  <select class="form-select" aria-label="Default select example" name="zone">
                    <option value="1">One</option>
                    <option value="2">Two</option>
                    <option value="3">Three</option>
                  </select>
                  <label class="form-label" for="email_1">zone</label>
                </div>
              </div>
              <div class="col-12 col-md-4 mb-3">
                <div class="form-floating">
                  <input type="text" name="email" class="form-control" value="" placeholder="city">
                  <label class="form-label" for="email_1">city</label>
                </div>
              </div>
              <div class="col-12 mb-3">
                <div class="form-floating">
                  <input type="text" name="email" class="form-control" value="" placeholder="city">
                  <label class="form-label" for="email_1">邮编</label>
                </div>
              </div>
              <div class="col-12 mb-3">
                <div class="form-floating">
                  <input type="text" name="email" class="form-control" value="" placeholder="city">
                  <label class="form-label" for="email_1">address 1</label>
                </div>
              </div>
              <div class="col-12 mb-3">
                <div class="form-floating">
                  <input type="text" name="email" class="form-control" value="" placeholder="city">
                  <label class="form-label" for="email_1">address 2</label>
                </div>
              </div>
            </div> --}}

            <table class="table">
              <thead>
                <tr>
                  <th>#</th>
                  <th>名称</th>
                  <th>电话</th>
                  <th>注册来源</th>
                  <th>状态</th>
                  <th>操作</th>
                </tr>
              </thead>
              <tbody v-if="source.address.length">
                <tr v-for="address, index in source.address" :key="index">
                  <td>@{{ index }}</td>
                  <td>@{{ address.name }}</td>
                  <td>@{{ address.phone }}</td>
                  <td>222</td>
                  <td>222</td>
                  <td>
                    <button class="btn btn-outline-secondary btn-sm" type="button" @click="editAddress(index)">编辑</button>
                    <button class="btn btn-outline-danger btn-sm ml-1" type="button">删除</button>
                  </td>{{--
                </tr> --}}
              </tbody>
              <tbody v-else>
                <tr>
                  <td colspan="6" class="text-center">
                    <span class="me-2">当前账号无地址</span> <el-link type="primary" @click="editAddress">新增地址</el-link>
                  </td>{{--
                </tr> --}}
              </tbody>
            </table>

            <h4 class="title">支付方式</h4>
            <div class="row mb-3">
              <div class="mb-4">
                <input type="radio" class="btn-check" name="options-outlined" id="success-outlined" autocomplete="off" checked>
                <label class="btn btn-outline-primary" for="success-outlined">支付方式 - 1</label>

                <input type="radio" class="btn-check" name="options-outlined" id="danger-outlined" autocomplete="off">
                <label class="btn btn-outline-primary" for="danger-outlined">支付方式 - 2</label>
              </div>
            </div>

            <h4 class="title">配送方式</h4>
            <div class="row mb-3">
              <div class="mb-4">
                <input type="radio" class="btn-check" name="peisong_name" id="peisong-1" autocomplete="off" checked>
                <label class="btn btn-outline-primary" for="peisong-1">配送方式 - 1</label>

                <input type="radio" class="btn-check" name="peisong_name" id="peisong-2" autocomplete="off">
                <label class="btn btn-outline-primary" for="peisong-2">配送方式 - 2</label>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="col-12 col-md-4">
        <div class="card total-wrap">
          <div class="card-header"><h5 class="mb-0">CART TOTALS</h5></div>
          <div class="card-body">
            <div class="products-wrap">
              @for ($i = 0; $i < 4; $i++)
              <div class="item">
                <div class="image">
                  <img src="http://fpoimg.com/100x100?bg_color=f3f3f3" class="img-fluid">
                  <div class="name">
                    <span>Camera Canon EOS M50 Kit</span>
                    <span class="quantity">x2</span>
                  </div>
                </div>
                <div class="price">$1156.00</div>
              </div>
              @endfor
            </div>
            <ul class="totals">
              <li><span>总数</span><span>1120</span></li>
              <li><span>运费</span><span>20</span></li>
              <li><span>总价</span><span>2220</span></li>
            </ul>
            <div class="d-grid gap-2 mt-3">
              <button class="btn btn-primary">提交订单</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@push('add-scripts')
  <script>
    new Vue({
      el: '#checkout-app',

      data: {
        form: {
        },

        source: {
          address: []
        },

        dialogAddress: {
          show: false,
          index: null,
          form: {
            name: '',
            phone: '',
            country_id: @json(setting('country_id')) * 1,
            zipcode: '',
            zone_id: '',
            city_id: '',
            address_1: '',
            address_2: '',
          }
        },
      },

      beforeMount () {
      },

      methods: {
        editAddress(index) {
          if (typeof index == 'number') {
            this.dialogAddress.index = index;

            this.$nextTick(() => {
              this.dialogAddress.form = JSON.parse(JSON.stringify(this.form.address[index]))
            })
          }

          this.dialogAddress.show = true
        },
      }
    })


    $(function() {
      const totalWrapTop = $('.total-wrap').offset().top;
      const totalWrapWidth = $('.total-wrap').outerWidth();
      const totalWrapLeft = $('.total-wrap').offset().left;

      $(window).scroll(function () {
        if ($(this).scrollTop() > totalWrapTop) {
          $('.total-wrap').addClass('total-wrap-fixed').css({'left': totalWrapLeft, 'width': totalWrapWidth})
        } else {
          $('.total-wrap').removeClass('total-wrap-fixed').css({'left': 0, 'width': 'auto'})
        }
      })
    });
  </script>
@endpush
@extends('layout.master')

@section('body-class', 'page-cart')

@push('header')
  <script src="{{ asset('vendor/vue/2.6.14/vue.js') }}"></script>
  <script src="{{ asset('vendor/element-ui/2.15.6/js.js') }}"></script>
  <link rel="stylesheet" href="{{ asset('vendor/element-ui/2.15.6/css.css') }}">
@endpush

@section('content')
  <div class="container" id="app-cart" v-cloak>
    <div class="row mt-5 justify-content-center">
      <div class="col-12 col-md-9">@include('shared.steps', ['steps' => 1])</div>
    </div>

    <div class="row mt-5" v-if="products.length">
      <div class="col-12 col-md-9">
        <div class="cart-products-wrap">
          <table class="table">
            <thead>
              <tr>
                <th width="130">
                  <input class="form-check-input" type="checkbox" value="" id="check-all" v-model="allSelected">
                  <label class="form-check-label ms-1" for="check-all">
                    全选
                  </label>
                </th>
                <th>商品</th>
                <th width="170">数量</th>
                <th width="170">小计</th>
                <th class="text-end">操作</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="product, index in products" :key="index" @click="checkedCartTr(index)"
                :class="product.selected ? 'active' : ''">
                <td>
                  <div class="d-flex align-items-center p-image">
                    <input class="form-check-input" type="checkbox" v-model="product.selected">
                    <img :src="product.image" class="img-fluid">
                  </div>
                </td>
                <td>
                  <div class="name mb-1 fw-bold" v-text="product.name"></div>
                  <div class="price text-muted">@{{ product.price_format }}</div>
                </td>
                <td>
                  <div class="quantity-wrap">
                    <input type="text" class="form-control" @input="quantityChange(product.quantity, product.cart_id)" onkeyup="this.value=this.value.replace(/\D/g,'')" v-model.number="product.quantity" name="quantity" minimum="1">
                    <div class="right">
                      <i class="bi bi-chevron-up"></i>
                      <i class="bi bi-chevron-down"></i>
                    </div>
                  </div>
                </td>
                <td>@{{ product.subtotal_format }}</td>
                <td class="text-end">
                  <button type="button" class="btn text-danger btn-sm px-0" @click.stop="checkedBtnDelete(product.cart_id)">
                    <i class="bi bi-x-lg"></i> 删除
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="col-12 col-md-3">
        <div class="card total-wrap fixed-top-line">
          <div class="card-header"><h5 class="mb-0">商品总计</h5></div>
          <div class="card-body">
            <ul class="list-group list-group-flush">
              <li class="list-group-item"><span>全部</span><span>@{{ products.length }}</span></li>
              <li class="list-group-item"><span>已选</span><span>@{{ total_quantity }}</span></li>
              <li class="list-group-item border-bottom-0"><span>总价</span><span class="total-price">@{{ amount_format }}</span></li>
              <li class="list-group-item d-grid gap-2 mt-3 border-bottom-0">
                {{-- <a href="{{ shop_route('checkout.index', 'checkout') }}" class="btn btn-primary">去结账</a> --}}
                <button type="button" class="btn btn-primary" @click="checkedBtnToCheckout">去结账</button>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div v-else class="d-flex justify-content-center align-items-center flex-column">
      <div class="empty-cart-wrap text-center mt-5">
        <div class="empty-cart-icon mb-3">
          <i class="bi bi-cart fs-1"></i>
        </div>
        <div class="empty-cart-text mb-3">
          <h5>您的购物车是空的</h5>
          <p class="text-muted">您可以去看看有哪些想买的</p>
        </div>
        <div class="empty-cart-action">
          <a href="{{ shop_route('home.index') }}" class="btn btn-primary">去逛逛</a>
        </div>
      </div>
    </div>
  </div>

@endsection

@push('add-scripts')
  <script>
    var app = new Vue({
      el: "#app-cart",
      data: {
        products: @json($data['carts']),
        total_quantity: @json($data['quantity']),
        amount: @json($data['amount']),
        amount_format: @json($data['amount_format']),
      },
      // components: {},
      // 计算属性
      computed: {
        allSelected: {
          get() {
            return !this.products.length ? false : this.products.every(s => s.selected)
          },
          set(val) {
            // return
            this.products.map(e => e.selected = val)
            this.selectedBtnSelected()
          }
        },
      },
      // 侦听器
      watch: {},
      // 组件方法
      methods: {
        checkedBtnToCheckout() {
          if (!this.products.some(e => e.selected)) {
            layer.msg('请选择至少一个商品', ()=>{})
            return
          }

          location = '{{ shop_route("checkout.index") }}'
        },

        quantityChange(quantity, cart_id) {
          const self = this;
          $http.put(`/carts/${cart_id}`, {quantity: quantity}).then((res) => {
            this.setUpdateData(res);
          })
        },

        checkedBtnDelete(cart_id) {
          const self = this;

          $http.delete(`/carts/${cart_id}`).then((res) => {
            this.setUpdateData(res);
          })
        },

        checkedCartTr(index) {
          this.products[index].selected = !this.products[index].selected;
          this.selectedBtnSelected();
        },

        selectedBtnSelected() {
          const self = this;
          const cart_ids = this.products.filter(e => e.selected).map(x => x.cart_id)

          $http.post(`/carts/select`, {cart_ids: cart_ids}).then((res) => {
            this.setUpdateData(res);
          })
        },

        setUpdateData(res) {
          this.products = res.data.carts
          this.amount_format = res.data.amount_format
          this.total_quantity = res.data.quantity
        }
      },
      // 实例被挂载后调用
      mounted () {
      },
    })
  </script>
@endpush

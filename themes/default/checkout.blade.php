@extends('layout.master')

@section('body-class', 'page-checkout')

@push('header')
  <script src="{{ asset('vendor/vue/2.6.14/vue.js') }}"></script>
  <script src="{{ asset('vendor/element-ui/2.15.6/js.js') }}"></script>
  <link rel="stylesheet" href="{{ asset('vendor/element-ui/2.15.6/css.css') }}">
@endpush

@section('content')
  <div class="container" id="checkout-app" v-cloak>

    <x-shop-breadcrumb type="static" value="checkout.index" />

    <div class="row mt-1 justify-content-center">
      <div class="col-12 col-md-9">@include('shared.steps', ['steps' => 2])</div>
    </div>

    <div class="row mt-5">
      <div class="col-12 col-md-8 left-column">
        <div class="checkout-black">
          <div class="checkout-title">
            <div class="d-flex">
              <h5 class="mb-0 me-4">{{ __('shop/checkout.address') }}</h5>
              <el-checkbox v-model="same_as_shipping_address">{{ __('shop/checkout.same_as_shipping_address') }}
              </el-checkbox>
            </div>
            <button class="btn btn-sm icon" v-if="isAllAddress" @click="isAllAddress = false"><i
                class="bi bi-x-lg"></i></button>
          </div>
          <div class="addresses-wrap">
            <div class="row">
              <div class="col-6" v-for="address, index in source.addresses" :key="index"
                v-if="source.addresses.length &&( address.id == form.shipping_address_id || isAllAddress)">
                <div :class="['item', address.id == form.shipping_address_id ? 'active' : '']"
                  @click="updateCheckout(address.id, 'shipping_address_id')">
                  <div class="name-wrap">
                    <span class="name">@{{ address.name }}</span>
                    <span class="phone">@{{ address.phone }}</span>
                  </div>
                  <div class="zipcode">@{{ address.zipcode }}</div>
                  <div class="address-info">@{{ address.country }} @{{ address.zone }} @{{ address.city }}
                    @{{ address.address_1 }}</div>
                  <div class="address-bottom">
                    <div>
                      <span class="badge bg-success"
                        v-if="form.shipping_address_id == address.id">{{ __('shop/checkout.chosen') }}</span>
                    </div>
                    <a class=""
                      @click.stop="editAddress(index, 'shipping_address_id')">{{ __('shop/checkout.edit') }}</a>
                  </div>
                </div>
              </div>
              <div class="col-6" v-if="!isAllAddress">
                <div class="item address-right">
                  <button class="btn btn-outline-dark w-100 mb-3" v-if="source.addresses.length > 1"
                    @click="isAllAddress = true">{{ __('shop/checkout.choose_another_address') }}</button>
                  <button class="btn btn-outline-dark w-100" @click="editAddress(null, 'shipping_address_id')"><i
                      class="bi bi-plus-square-dotted"></i> {{ __('shop/checkout.add_new_address') }}</button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="checkout-black" v-if='!same_as_shipping_address'>
          <div class="checkout-title">
            <div class="d-flex">
              <h5 class="mb-0 me-4">{{ __('shop/checkout.payment_address') }}</h5>
            </div>
            <button class="btn btn-sm icon" v-if="isAllAddressPayment" @click="isAllAddressPayment = false"><i
                class="bi bi-x-lg"></i></button>
          </div>
          <div class="addresses-wrap">
            <div class="row">
              <div class="col-6" v-for="address, index in source.addresses" :key="index"
                v-if="source.addresses.length && (form.payment_address_id == '' || address.id == form.payment_address_id || isAllAddressPayment)">
                <div :class="['item', address.id == form.payment_address_id ? 'active' : '']"
                  @click="updateCheckout(address.id, 'payment_address_id')">
                  <div class="name-wrap">
                    <span class="name">@{{ address.name }}</span>
                    <span class="phone">@{{ address.phone }}</span>
                  </div>
                  <div class="zipcode">@{{ address.zipcode }}</div>
                  <div class="address-info">@{{ address.country }} @{{ address.zone }} @{{ address.city }}
                    @{{ address.address_1 }}</div>
                  <div class="address-bottom">
                    <div>
                      <span class="badge bg-success"
                        v-if="form.payment_address_id == address.id">{{ __('shop/checkout.chosen') }}</span>
                    </div>
                    <a class=""
                      @click.stop="editAddress(index, 'payment_address_id')">{{ __('shop/checkout.edit') }}</a>
                  </div>
                </div>
              </div>
              <div class="col-6" v-if="!isAllAddressPayment">
                <div class="item address-right">
                  <button class="btn btn-outline-dark w-100 mb-3" v-if="source.addresses.length > 1"
                    @click="isAllAddressPayment = true">{{ __('shop/checkout.choose_another_address') }}</button>
                  <button class="btn btn-outline-dark w-100" @click="editAddress(null, 'payment_address_id')"><i
                      class="bi bi-plus-square-dotted"></i> {{ __('shop/checkout.add_new_address') }}</button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="checkout-black">
          <h5 class="checkout-title">{{ __('shop/checkout.payment_method') }}</h5>
          <div class="radio-line-wrap">
            <div :class="['radio-line-item', payment.code == form.payment_method_code ? 'active' : '']"
              v-for="payment, index in source.payment_methods" :key="index"
              @click="updateCheckout(payment.code, 'payment_method_code')">
              <div class="left">
                <input name="payment" type="radio" v-model="form.payment_method_code" :value="payment.code"
                  :id="'payment-method-' + index" class="form-check-input">
                <img :src="payment.icon" class="img-fluid">
              </div>
              <div class="right ms-3">
                <div class="title">@{{ payment.name }}</div>
                <div class="sub-title" v-html="payment.description"></div>
              </div>
            </div>
          </div>
        </div>

        <div class="checkout-black">
          <h5 class="checkout-title">{{ __('shop/checkout.delivery_method') }}</h5>
          <div class="radio-line-wrap">
            <div :class="['radio-line-item', shipping.code == form.shipping_method_code ? 'active' : '']"
              v-for="shipping, index in source.shipping_methods" :key="index"
              @click="updateCheckout(shipping.code, 'shipping_method_code')">
              <div class="left">
                <input name="shipping" type="radio" v-model="form.shipping_method_code" :value="shipping.code"
                  :id="'shipping-method-' + index" class="form-check-input">
                <img :src="shipping.icon" class="img-fluid" v-if="shipping.icon">
              </div>
              <div class="right ms-3">
                <div class="title">@{{ shipping.name }}</div>
                <div class="sub-title" v-html="shipping.description"></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12 col-md-4 right-column">
        <div class="card total-wrap fixed-top-line">
          <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0">{{ __('shop/checkout.cart_totals') }}</h5>
            <span class="rounded-circle bg-primary">@{{ source.carts.quantity }}</span>
          </div>
          <div class="card-body">
            <div class="products-wrap">
              <div class="item" v-for="cart, index in source.carts.carts" :key="index">
                <div class="image">
                  <img :src="cart.image" class="img-fluid">
                  <div class="name">
                    <span v-text="cart.name"></span>
                  </div>
                </div>
                <div class="price text-end">
                  <div>@{{ cart.price_format }}</div>
                  <div class="quantity">x @{{ cart.quantity }}</div>
                </div>
              </div>
            </div>
            <ul class="totals">
              @foreach ($totals as $total)
                <li><span>{{ $total['title'] }}</span><span>{{ $total['amount_format'] }}</span></li>
              @endforeach
            </ul>
            <div class="d-grid gap-2 mt-3">
              <button class="btn btn-primary" type="button" :disabled="!isSubmit"
                @click="checkedBtnCheckoutConfirm">{{ __('shop/checkout.submit_order') }}</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    @include('shared.address-form')
  </div>
@endsection
@push('add-scripts')
  <script>
    new Vue({
      el: '#checkout-app',

      data: {
        form: {
          shipping_address_id: @json($current['shipping_address_id']),
          payment_address_id: @json($current['payment_address_id']),
          payment_method_code: @json($current['payment_method_code']),
          shipping_method_code: @json($current['shipping_method_code']),
        },

        // same_as_shipping_address: @json($current['same_as_shipping_address'] ?? true),

        isAllAddress: false,
        isAllAddressPayment: false,

        source: {
          addresses: @json($addresses ?? []),
          countries: @json($countries ?? []),
          shipping_methods: @json($shipping_methods ?? []),
          payment_methods: @json($payment_methods ?? []),
          carts: @json($carts ?? null),
          zones: []
        },

        dialogAddress: {
          show: false,
          index: null,
          type: 'shipping_address_id',
          form: {
            name: '',
            phone: '',
            country_id: @json($country_id),
            zipcode: '',
            zone_id: @json((int) system_setting('base.zone_id')),
            city: '',
            address_1: '',
            address_2: '',
            default: false,
          }
        },

        addressRules: {
          name: [{
            required: true,
            message: '{{ __('shop/checkout.enter_name') }}',
            trigger: 'blur'
          }, ],
          phone: [{
            required: true,
            message: '{{ __('shop/checkout.enter_phone') }}',
            trigger: 'blur'
          }, ],
          address_1: [{
            required: true,
            message: '{{ __('shop/checkout.enter_address') }}',
            trigger: 'blur'
          }, ],
          zone_id: [{
            required: true,
            message: '{{ __('shop/checkout.select_province') }}',
            trigger: 'blur'
          }, ],
          city: [{
            required: true,
            message: '{{ __('shop/checkout.enter_city') }}',
            trigger: 'blur'
          }, ],
        }
      },

      // 计算属性
      computed: {
        same_as_shipping_address: {
          get() {
            return this.form.shipping_address_id == this.form.payment_address_id
          },

          set(e) {
            if (e) {
              this.form.payment_address_id = this.form.shipping_address_id
              this.updateCheckout(this.form.payment_address_id, 'same_as_shipping_address')
            } else {
              this.form.payment_address_id = '';
            }
          }
        },

        isSubmit() {
          // source.addresses.length > 0 && source.payment_methods.length > 0 && source.shipping_methods.length > 0
          return this.source.addresses.length > 0 && this.source.payment_methods.length > 0 && this.source
            .shipping_methods.length > 0;
        },
        // isAddress: {
        //   this.form.shipping_address_id ==
        // }
      },

      beforeMount() {
        this.countryChange(this.dialogAddress.form.country_id);
      },

      methods: {
        editAddress(index, type) {
          if (typeof index == 'number') {
            this.dialogAddress.index = index;

            this.$nextTick(() => {
              this.dialogAddress.form = JSON.parse(JSON.stringify(this.source.addresses[index]))
              this.countryChange(this.dialogAddress.form.country_id);
            })
          }

          this.dialogAddress.type = type
          this.dialogAddress.show = true
        },

        // shippingPaymentAddressChange() {

        // },

        addressFormSubmit(form) {
          const self = this;

          this.$refs[form].validate((valid) => {
            if (!valid) {
              this.$message.error('{{ __('shop/checkout.check_form') }}');
              return;
            }

            const type = this.dialogAddress.form.id ? 'put' : 'post';
            const url = `/account/addresses${type == 'put' ? '/' + this.dialogAddress.form.id : ''}`;

            $http[type](url, this.dialogAddress.form).then((res) => {
              if (type == 'post') {
                this.source.addresses.push(res.data)
                this.updateCheckout(res.data.id, this.dialogAddress.type)
                this.form[this.dialogAddress.type] = res.data.id

              } else {
                this.source.addresses[this.dialogAddress.index] = res.data
              }
              this.$message.success(res.message);
              this.$refs[form].resetFields();
              this.dialogAddress.show = false
              this.dialogAddress.index = null;
            })
          });
        },

        closeAddressDialog(form) {
          this.$refs[form].resetFields();
          this.dialogAddress.show = false
          this.dialogAddress.index = null;

          Object.keys(this.dialogAddress.form).forEach(key => this.dialogAddress.form[key] = '')
          this.dialogAddress.form.country_id = @json($country_id)
        },

        countryChange(e) {
          const self = this;

          $http.get(`/countries/${e}/zones`).then((res) => {
            this.source.zones = res.data.zones;

            if (!res.data.zones.some(e => e.id == this.dialogAddress.form.zone_id)) {
              this.dialogAddress.form.zone_id = '';
            }
          })
        },

        updateCheckout(id, key) {
          if (this.form[key] === id && key != 'same_as_shipping_address') {
            return
          }

          if (key == 'shipping_address_id' && this.same_as_shipping_address) {
            this.form.payment_address_id = id
          }

          this.form[key] = id

          $http.put('/checkout', this.form).then((res) => {
            this.form = res.current
            this.isAllAddress = false
            this.isAllAddressPayment = false
          })
        },

        checkedBtnCheckoutConfirm() {
          if (!this.form.payment_address_id) {
            layer.msg('{{ __('shop/checkout.error_payment_address') }}', ()=>{})
            return;
          }

          $http.post('/checkout/confirm', this.form).then((res) => {
            location = 'orders/' + res.number + '/success?type=create'
          })
        }
      }
    })
  </script>
@endpush

@extends('layout.master')

@section('body-class', 'page-checkout')

@push('header')
  <script src="{{ asset('vendor/vue/2.6.14/vue' . (!config('app.debug') ? '.min' : '') . '.js') }}"></script>
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
        <div id="checkout-app" v-cloak>
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
                <template v-if="source.isLogin">
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
                        <a href="javascript:void(0)" class=""
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
                </template>
                <template v-else>
                  <div class="col-6" v-if="source.guest_shipping_address">
                    <div class="item active">
                      <div class="name-wrap">
                        <span class="name">@{{ source.guest_shipping_address.name }}</span>
                        <span class="phone">@{{ source.guest_shipping_address.phone }}</span>
                      </div>
                      <div class="zipcode">
                        <span>@{{ source.guest_shipping_address.zipcode }}</span>
                        <span class="ms-1">@{{ source.guest_shipping_address.email }}</span>
                      </div>
                      <div class="address-info">@{{ source.guest_shipping_address.country }} @{{ source.guest_shipping_address.zone }} @{{ source.guest_shipping_address.city }}
                        @{{ source.guest_shipping_address.address_1 }}</div>
                      <div class="address-bottom">
                        <div>
                          <span class="badge bg-success">{{ __('shop/checkout.chosen') }}</span>
                        </div>
                        <a class="javascript:void(0)"
                          @click.stop="editAddress(null, 'guest_shipping_address')">{{ __('shop/checkout.edit') }}</a>
                      </div>
                    </div>
                  </div>
                  <div class="col-6" v-if="!source.guest_shipping_address">
                    <div class="item address-right">
                      <button class="btn btn-outline-dark w-100" @click="editAddress(null, 'guest_shipping_address')"><i
                          class="bi bi-plus-square-dotted"></i> {{ __('shop/checkout.add_new_address') }}</button>
                    </div>
                  </div>
                </template>
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
                <template v-if="source.isLogin">
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
                        <a class="javascript:void(0)"
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
                </template>
                <template v-else>
                  <div class="col-6" v-if="source.guest_payment_address">
                    <div class="item active">
                      <div class="name-wrap">
                        <span class="name">@{{ source.guest_payment_address.name }}</span>
                        <span class="phone">@{{ source.guest_payment_address.phone }}</span>
                      </div>
                      <div class="zipcode">@{{ source.guest_payment_address.zipcode }}</div>
                      <div class="address-info">@{{ source.guest_payment_address.country }} @{{ source.guest_payment_address.zone }} @{{ source.guest_payment_address.city }}
                        @{{ source.guest_payment_address.address_1 }}</div>
                      <div class="address-bottom">
                        <div>
                          <span class="badge bg-success">{{ __('shop/checkout.chosen') }}</span>
                        </div>
                        <a class="javascript:void(0)"
                          @click.stop="editAddress(null, 'guest_payment_address')">{{ __('shop/checkout.edit') }}</a>
                      </div>
                    </div>
                  </div>
                  <div class="col-6" v-if="!source.guest_payment_address">
                    <div class="item address-right">
                      <button class="btn btn-outline-dark w-100" @click="editAddress(null, 'guest_payment_address')"><i
                          class="bi bi-plus-square-dotted"></i> {{ __('shop/checkout.add_new_address') }}</button>
                    </div>
                  </div>
                </template>
              </div>
            </div>
          </div>
        </div>

        <div class="checkout-black">
          <h5 class="checkout-title">{{ __('shop/checkout.payment_method') }}</h5>
          <div class="radio-line-wrap">
            @foreach ($payment_methods as $payment)
              <div class="radio-line-item {{ $payment['code'] == $current['payment_method_code'] ? 'active' : '' }}" data-key="payment_method_code" data-value="{{ $payment['code'] }}">
                <div class="left">
                  <span class="radio"></span>
                  {{-- <input name="payment" type="radio" {{ $payment['code'] == $current['payment_method_code'] ? 'checked' : ''  }} value="{{ $payment['code'] }}" class="form-check-input"> --}}
                  <img src="{{ $payment['icon'] }}" class="img-fluid">
                </div>
                <div class="right ms-3">
                  <div class="title">{{ $payment['name'] }}</div>
                  <div class="sub-title">{!! $payment['description'] !!}</div>
                </div>
              </div>
            @endforeach
          </div>
        </div>

        <div class="checkout-black">
          <h5 class="checkout-title">{{ __('shop/checkout.delivery_method') }}</h5>
          <div class="radio-line-wrap">
            @foreach ($shipping_methods as $methods)
              @foreach ($methods['quotes'] as $shipping)
              <div class="radio-line-item {{ $shipping['code'] == $current['shipping_method_code'] ? 'active':'' }}" data-key="shipping_method_code" data-value="{{ $shipping['code'] }}">
                <div class="left">
                  {{-- <input name="shipping" {{ $shipping['code'] == $current['shipping_method_code'] ? 'checked' : ''  }} type="radio" value="{{ $shipping['code'] }}" class="form-check-input"> --}}
                  <span class="radio"></span>
                  <img src="{{ $shipping['icon'] }}" class="img-fluid">
                </div>
                <div class="right ms-3">
                  <div class="title">{{ $shipping['name'] }}</div>
                  <div class="sub-title">{!! $shipping['description'] !!}</div>
                  <div class="mt-2">{!! $shipping['html'] ?? '' !!}</div>
                </div>
              </div>
              @endforeach
            @endforeach
          </div>
        </div>
      </div>

      <div class="col-12 col-md-4 right-column">
        <div class="card total-wrap fixed-top-line">
          <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0">{{ __('shop/checkout.cart_totals') }}</h5>
            <span class="rounded-circle bg-primary">{{ $carts['quantity'] }}</span>
          </div>
          <div class="card-body">
            <div class="products-wrap">
              @foreach ($carts['carts'] as $cart)
                <div class="item">
                  <div class="image">
                    <img src="{{ $cart['image'] }}" class="img-fluid">
                    <div class="name">
                      <div title="{{ $cart['name'] }}" class="text-truncate-2">{{ $cart['name'] }}</div>
                      @if ($cart['variant_labels'])
                        <div class="text-muted mt-1">{{ $cart['variant_labels'] }}</div>
                      @endif
                    </div>
                  </div>
                  <div class="price text-end">
                    <div>{{ $cart['price_format'] }}</div>
                    <div class="quantity">x {{ $cart['quantity'] }}</div>
                  </div>
                </div>
              @endforeach
            </div>
            <ul class="totals">
              @foreach ($totals as $total)
                <li><span>{{ $total['title'] }}</span><span>{{ $total['amount_format'] }}</span></li>
              @endforeach
            </ul>
            <div class="d-grid gap-2 mt-3">
              <button class="btn btn-primary" type="button" id="submit-checkout">{{ __('shop/checkout.submit_order') }}</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <address-dialog ref="address-dialog" @change="onAddressDialogChange"></address-dialog>
  </div>
  @endsection

@push('add-scripts')
@include('shared.address-form')
<script>
  $(document).ready(function() {
    $('.radio-line-item').click(function(event) {
      const key = $(this).data('key');
      const value = $(this).data('value');

      $http.put('/checkout', {[key]: value}).then((res) => {
        $(this).addClass('active').siblings().removeClass('active')
      })
    });

    $('#submit-checkout').click(function(event) {
      app.checkedBtnCheckoutConfirm();
    });
  });

  var app = new Vue({
    el: '#checkout-app',

    data: {
      form: {
        shipping_address_id: @json($current['shipping_address_id']),
        payment_address_id: @json($current['payment_address_id']),
      },

      isAllAddress: false,
      isAllAddressPayment: false,

      source: {
        addresses: @json($addresses ?? []),
        guest_shipping_address: @json($current['guest_shipping_address'] ?? null),
        guest_payment_address: @json($current['guest_payment_address'] ?? null),
        isLogin: @json(current_customer(), null),
      },

      dialogAddress: {
        index: null,
        type: 'shipping_address_id',
      },
    },

    computed: {
      same_as_shipping_address: {
        get() {
          if (!this.source.isLogin) {
            return JSON.stringify(this.source.guest_shipping_address) === JSON.stringify(this.source.guest_payment_address);
          }

          return this.form.shipping_address_id === this.form.payment_address_id
        },

        set(e) {
          if (e) {
            if (!this.source.isLogin) {
              $http.put('/checkout', {guest_payment_address: this.source.guest_shipping_address}).then((res) => {
                this.source.guest_payment_address = res.current.guest_payment_address;
              })
            } else {
              this.form.payment_address_id = this.form.shipping_address_id
              this.updateCheckout(this.form.payment_address_id, 'same_as_shipping_address')
            }
          } else {
            this.form.payment_address_id = '';
            this.source.guest_payment_address = null
          }
        },
      },
    },

    methods: {
      editAddress(index, type) {
        let addresses = null

        if (typeof index == 'number') {
          this.dialogAddress.index = index;
          addresses = JSON.parse(JSON.stringify(this.source.addresses[index]))
        }

        // 游客结账
        if ((type == 'guest_shipping_address' || type == 'guest_payment_address') && this.source[type]) {
          addresses = JSON.parse(JSON.stringify(this.source[type]))
        }

        this.dialogAddress.type = type
        this.$refs['address-dialog'].editAddress(addresses, this.dialogAddress.type)
      },

      onAddressDialogChange(form) {
        const type = form.id ? 'put' : 'post';
        const url = `/account/addresses${type == 'put' ? '/' + form.id : ''}`;

        if (!isLogin) {
          let data = {[this.dialogAddress.type]: form}

          if (this.source.guest_payment_address === null && this.source.guest_shipping_address === null) {
            data = {
              guest_shipping_address: form,
              guest_payment_address: form
            }
          }

          $http.put('/checkout', data).then((res) => {
            if (this.source.guest_payment_address === null && this.source.guest_shipping_address === null) {
              this.source.guest_shipping_address = res.current.guest_shipping_address;
              this.source.guest_payment_address = res.current.guest_payment_address;
            } else {
              this.source[this.dialogAddress.type] = res.current[this.dialogAddress.type];
            }
            this.$message.success('{{ __('common.edit_success') }}');
            this.$refs['address-dialog'].closeAddressDialog()
          })
        } else {
          $http[type](url, form).then((res) => {
            this.$message.success(res.message);
            if (this.source.addresses.find(e => e.id == res.data.id)) {
              this.source.addresses[this.dialogAddress.index] = res.data
            } else {
              this.source.addresses.push(res.data)
              this.updateCheckout(res.data.id, this.dialogAddress.type)
              this.form[this.dialogAddress.type] = res.data.id
            }

            this.dialogAddress.index = null;
            this.$forceUpdate()
            this.$refs['address-dialog'].closeAddressDialog()
          })
        }
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
          this.source.totals = res.totals

          this.isAllAddress = false
          this.isAllAddressPayment = false
        })
      },

      checkedBtnCheckoutConfirm() {
        if (!this.source.isLogin) {
          if (this.source.guest_shipping_address === null) {
            layer.msg('{{ __('shop/checkout.error_payment_address') }}', ()=>{})
            return;
          }
        } else {
          if (!this.form.payment_address_id) {
            layer.msg('{{ __('shop/checkout.error_payment_address') }}', ()=>{})
            return;
          }
        }

        $http.post('/checkout/confirm').then((res) => {
          location = 'orders/' + res.number + '/success?type=create'
        })
      }
    }
  })
</script>
@endpush

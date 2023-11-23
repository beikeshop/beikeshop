<div id="checkout-address-app" v-cloak>
  <div class="checkout-black" v-if="shippingRequired">
    <div class="checkout-title">
      <div class="d-flex">
        <h5 class="mb-0 me-4">{{ __('shop/checkout.address') }}</h5>
        <el-checkbox v-model="same_as_shipping_address" v-if="source.addresses.length || source.guest_shipping_address">{{ __('shop/checkout.same_as_shipping_address') }}
        </el-checkbox>
      </div>
      <button class="btn btn-sm icon" v-if="isAllAddress" @click="isAllAddress = false"><i
          class="bi bi-x-lg"></i></button>
    </div>
    <div class="addresses-wrap">
      <div class="row">
        <template v-if="source.isLogin">
          <div class="col-lg-6 col-12" v-for="address, index in source.addresses" :key="index"
            v-if="source.addresses.length &&( address.id == form.shipping_address_id || isAllAddress)">
            <div :class="['item', address.id == form.shipping_address_id ? 'active' : '']"
              @click="updateCheckout(address.id, 'shipping_address_id')">
              <div class="name-wrap">
                <span class="name">@{{ address.name }}</span>
                <span class="phone">@{{ address.phone }}</span>
              </div>
              <div class="zipcode">@{{ address.zipcode }}</div>
              <div class="address-info">@{{ address.address_1 }} @{{ address.address_2 }} @{{ address.city }} @{{ address.zone }} @{{ address.country }}</div>
              <div class="address-bottom">
                <div>
                  <span class="badge bg-success"
                    v-if="form.shipping_address_id == address.id">{{ __('shop/checkout.chosen') }}</span>
                </div>
                <button type="button" class="btn btn-outline-secondary btn-sm"
                  @click.stop="editAddress(index, 'shipping_address_id')">{{ __('shop/checkout.edit') }}</button>
              </div>
            </div>
          </div>
          <div class="col-lg-6 col-12" v-if="!isAllAddress">
            <div class="item address-right">
              <button class="btn btn-outline-dark w-100 mb-md-3" v-if="source.addresses.length > 1"
                @click="isAllAddress = true">{{ __('shop/checkout.choose_another_address') }}</button>
              <button class="btn btn-outline-dark w-100" @click="editAddress(null, 'shipping_address_id')"><i
                  class="bi bi-plus-square-dotted"></i> {{ __('shop/checkout.add_new_address') }}</button>
            </div>
          </div>
        </template>
        <template v-else>
          <div class="col-lg-6 col-12" v-if="source.guest_shipping_address">
            <div class="item active">
              <div class="name-wrap">
                <span class="name">@{{ source.guest_shipping_address.name }}</span>
                <span class="phone">@{{ source.guest_shipping_address.phone }}</span>
              </div>
              <div class="zipcode">
                <span>@{{ source.guest_shipping_address.zipcode }}</span>
                <span class="ms-1">@{{ source.guest_shipping_address.email }}</span>
              </div>
              <div class="address-info">@{{ source.guest_shipping_address.address_1 }} @{{ source.guest_shipping_address.address_2 }} @{{ source.guest_shipping_address.city }} @{{ source.guest_shipping_address.zone }} @{{ source.guest_shipping_address.country }}</div>
              <div class="address-bottom">
                <div>
                  <span class="badge bg-success">{{ __('shop/checkout.chosen') }}</span>
                </div>
                <button type="button" class="btn btn-outline-secondary btn-sm"
                  @click.stop="editAddress(null, 'guest_shipping_address')">{{ __('shop/checkout.edit') }}</button>
              </div>
            </div>
          </div>
          <div class="col-lg-6 col-12" v-if="!source.guest_shipping_address">
            <div class="item address-right">
              <button class="btn btn-outline-dark w-100" @click="editAddress(null, 'guest_shipping_address')"><i
                  class="bi bi-plus-square-dotted"></i> {{ __('shop/checkout.add_new_address') }}</button>
            </div>
          </div>
        </template>
      </div>
    </div>
  </div>

  <div class="checkout-black" v-if='!this.shippingRequired || !same_as_shipping_address'>
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
          <div class="col-lg-6 col-12" v-for="address, index in source.addresses" :key="index"
            v-if="source.addresses.length && (form.payment_address_id == '' || address.id == form.payment_address_id || isAllAddressPayment)">
            <div :class="['item', address.id == form.payment_address_id ? 'active' : '']"
              @click="updateCheckout(address.id, 'payment_address_id')">
              <div class="name-wrap">
                <span class="name">@{{ address.name }}</span>
                <span class="phone">@{{ address.phone }}</span>
              </div>
              <div class="zipcode">@{{ address.zipcode }}</div>
              <div class="address-info">@{{ address.address_1 }} @{{ address.address_2 }} @{{ address.city }} @{{ address.zone }} @{{ address.country }}</div>
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
          <div class="col-lg-6 col-12" v-if="!isAllAddressPayment">
            <div class="item address-right">
              <button class="btn btn-outline-dark w-100 mb-md-3" v-if="source.addresses.length > 1"
                @click="isAllAddressPayment = true">{{ __('shop/checkout.choose_another_address') }}</button>
              <button class="btn btn-outline-dark w-100" @click="editAddress(null, 'payment_address_id')"><i
                  class="bi bi-plus-square-dotted"></i> {{ __('shop/checkout.add_new_address') }}</button>
            </div>
          </div>
        </template>
        <template v-else>
          <div class="col-lg-6 col-12" v-if="source.guest_payment_address">
            <div class="item active">
              <div class="name-wrap">
                <span class="name">@{{ source.guest_payment_address.name }}</span>
                <span class="phone">@{{ source.guest_payment_address.phone }}</span>
              </div>
              <div class="zipcode">@{{ source.guest_payment_address.zipcode }}</div>
              <div class="address-info">@{{ source.guest_payment_address.address_1 }} @{{ source.guest_payment_address.address_2 }} @{{ source.guest_payment_address.city }} @{{ source.guest_payment_address.zone }} @{{ source.guest_payment_address.country }}</div>
              <div class="address-bottom">
                <div>
                  <span class="badge bg-success">{{ __('shop/checkout.chosen') }}</span>
                </div>
                <a class="javascript:void(0)"
                  @click.stop="editAddress(null, 'guest_payment_address')">{{ __('shop/checkout.edit') }}</a>
              </div>
            </div>
          </div>
          <div class="col-lg-6 col-12" v-if="!source.guest_payment_address">
            <div class="item address-right">
              <button class="btn btn-outline-dark w-100" @click="editAddress(null, 'guest_payment_address')"><i
                  class="bi bi-plus-square-dotted"></i> {{ __('shop/checkout.add_new_address') }}</button>
            </div>
          </div>
        </template>
      </div>
    </div>
  </div>

  <address-dialog ref="address-dialog" @change="onAddressDialogChange"></address-dialog>
</div>

@push('add-scripts')
@include('shared.address-form')
<script>
  var checkoutAddressApp = new Vue({
    el: '#checkout-address-app',

    data: {
      form: {
        shipping_address_id: @json($current['shipping_address_id']),
        payment_address_id: @json($current['payment_address_id']),
      },

      isAllAddress: false,
      isAllAddressPayment: false,
      shippingRequired: @json($shipping_require ?? true),

      source: {
        addresses: @json($addresses ?? []),
        guest_shipping_address: @json($current['guest_shipping_address'] ?? null),
        guest_payment_address: @json($current['guest_payment_address'] ?? null),
        isLogin: config.isLogin,
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

        if (!this.source.isLogin) {
          let data = {[this.dialogAddress.type]: form}

          if (this.same_as_shipping_address) {
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
              if (this.same_as_shipping_address) {
                this.source.guest_payment_address = res.current.guest_shipping_address;
              }
              this.source[this.dialogAddress.type] = res.current[this.dialogAddress.type];
            }
            updateTotal(res.totals)
            updateShippingMethods(res.shipping_methods, res.current.shipping_method_code)
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
              this.form[this.dialogAddress.type] = res.data.id
            }

            this.updateCheckout(res.data.id, this.dialogAddress.type)
            this.dialogAddress.index = null;
            this.$forceUpdate()
            this.$refs['address-dialog'].closeAddressDialog()
          })
        }
      },

      updateCheckout(id, key) {
        // if (this.form[key] === id && key != 'same_as_shipping_address') {
        //   return
        // }

        if (key == 'shipping_address_id' && this.same_as_shipping_address) {
          this.form.payment_address_id = id
        }

        this.form[key] = id

        $http.put('/checkout', this.form).then((res) => {
          this.form = res.current
          this.source.totals = res.totals

          updateTotal(res.totals)
          updateShippingMethods(res.shipping_methods, res.current.shipping_method_code)

          this.isAllAddress = false
          this.isAllAddressPayment = false
        })
      },
    }
  })
</script>
@endpush

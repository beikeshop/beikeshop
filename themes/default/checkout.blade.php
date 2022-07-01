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
            <h5 class="checkout-title">地址</h5>
            <div class="addresses-wrap">
              <div class="row">
                <div class="col-4" v-for="address, index in source.addresses" :key="index" v-if="source.addresses.length">
                  <div :class="['item', address.id == form.shipping_address_id ? 'active' : '']">
                    <div class="name-wrap">
                      <span class="name">@{{ address.name }}</span>
                      <span class="phone">@{{ address.phone }}</span>
                    </div>
                    <div class="zipcode">@{{ address.zipcode }}</div>
                    <div class="address-info">@{{ address.country_id }} @{{ address.zone_id }}</div>
                    <div class="address-bottom">
                      <span class="badge bg-success">已选择</span>
                      <a class="" @click="editAddress(index)">编辑</a>
                    </div>
                  </div>
                </div>
                <div class="col-4">
                  <div class="item add-addres" @click="editAddress"><i class="bi bi-plus-square-dotted"></i> 添加新地址</div>
                </div>
              </div>
            </div>

            <h5 class="checkout-title">支付方式</h5>
            <div class="row mb-3">
              <div class="mb-4">
                <input type="radio" class="btn-check" name="options-outlined" id="success-outlined" autocomplete="off" checked>
                <label class="btn btn-outline-primary" for="success-outlined">支付方式 - 1</label>

                <input type="radio" class="btn-check" name="options-outlined" id="danger-outlined" autocomplete="off">
                <label class="btn btn-outline-primary" for="danger-outlined">支付方式 - 2</label>
              </div>
            </div>

            <h5 class="checkout-title">配送方式</h5>
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
              <div class="item" v-for="cart, index in source.carts.carts" :key="index">
                <div class="image">
                  <img :src="cart.image" class="img-fluid">
                  <div class="name">
                    <span v-text="cart.name"></span>
                    <span class="quantity">x @{{ cart.quantity }}</span>
                  </div>
                </div>
                <div class="price" v-text="cart.price_format"></div>
              </div>
            </div>
            <ul class="totals">
              <li><span>总数</span><span v-text="source.carts.quantity"></span></li>
              <li><span>运费</span><span v-text="source.carts.quantity"></span></li>
              <li><span>总价</span><span v-text="source.carts.amount_format"></span></li>
            </ul>
            <div class="d-grid gap-2 mt-3">
              <button class="btn btn-primary" type="button" @click="checkedBtnCheckoutConfirm">提交订单</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <el-dialog title="编辑地址" :visible.sync="dialogAddress.show" width="600px" @close="closeAddressDialog('addressForm')">
      <el-form ref="addressForm" :rules="addressRules" :model="dialogAddress.form" label-width="100px">
        <el-form-item label="姓名" prop="name">
          <el-input v-model="dialogAddress.form.name"></el-input>
        </el-form-item>
        <el-form-item label="联系电话" prop="phone">
          <el-input maxlength="11" v-model="dialogAddress.form.phone"></el-input>
        </el-form-item>
        <el-form-item label="地址" required>
          <div class="row">
            <div class="col-4">
              <el-form-item>
                <el-select v-model="dialogAddress.form.country_id" filterable placeholder="选择国家" @change="countryChange">
                  <el-option v-for="item in source.countries" :key="item.id" :label="item.name"
                    :value="item.id">
                  </el-option>
                </el-select>
              </el-form-item>
            </div>
            <div class="col-4">
              <el-form-item prop="zone_id">
                <el-select v-model="dialogAddress.form.zone_id" filterable placeholder="选择省份">
                  <el-option v-for="item in source.zones" :key="item.id" :label="item.name"
                    :value="item.id">
                  </el-option>
                </el-select>
              </el-form-item>
            </div>
            <div class="col-4">
              <el-form-item prop="city_id">
                <el-input v-model="dialogAddress.form.city_id" placeholder="输入 city"></el-input>
              </el-form-item>
            </div>
          </div>
        </el-form-item>
        <el-form-item label="邮编" prop="zipcode">
          <el-input v-model="dialogAddress.form.zipcode"></el-input>
        </el-form-item>
        <el-form-item label="详细地址 1" prop="address_1">
          <el-input v-model="dialogAddress.form.address_1"></el-input>
        </el-form-item>
        <el-form-item label="详细地址 2">
          <el-input v-model="dialogAddress.form.address_2"></el-input>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="addressFormSubmit('addressForm')">保存</el-button>
          <el-button @click="closeAddressDialog('addressForm')">取消</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
@endsection
@push('add-scripts')
  <script>
    new Vue({
      el: '#checkout-app',

      data: {
        form: {
          shipping_address_id: 7,
        },

        source: {
          addresses: @json($addresses ?? []),
          countries: @json($countries ?? []),
          carts: @json($carts ?? null),
          zones: []
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

        addressRules: {
          name: [{required: true, message: '请输入姓名', trigger: 'blur'}, ],
          phone: [{required: true, message: '请输入联系电话', trigger: 'blur'}, ],
          address_1: [{required: true, message: '请输入详细地址 1', trigger: 'blur'}, ],
          zone_id: [{required: true, message: '请选择省份', trigger: 'blur'}, ],
          city_id: [{required: true, message: '请填写 city', trigger: 'blur'}, ],
        }
      },

      beforeMount () {
      },

      methods: {
        editAddress(index) {
          if (typeof index == 'number') {
            this.dialogAddress.index = index;

            this.$nextTick(() => {
              this.dialogAddress.form = JSON.parse(JSON.stringify(this.source.addresses[index]))
            })
          }

          this.countryChange(this.dialogAddress.form.country_id);
          this.dialogAddress.show = true
        },

        addressFormSubmit(form) {
          const self = this;

          this.$refs[form].validate((valid) => {
            if (!valid) {
              this.$message.error('请检查表单是否填写正确');
              return;
            }

            const type = this.dialogAddress.form.id ? 'put' : 'post';

            $.ajax({
              url: `/admin/customers/{{ $customer_id }}/addresses${type == 'put' ? '/' + this.dialogAddress.form.id : ''}`,
              data: self.dialogAddress.form,
              type: type,
              success: function(res) {
                if (type == 'post') {
                  self.source.addresses.push(res.data)
                } else {
                  self.source.addresses[self.dialogAddress.index] = res.data
                }
                self.$message.success(res.message);
                self.$refs[form].resetFields();
                self.dialogAddress.show = false
                self.dialogAddress.index = null;
              }
            })
          });
        },

        closeAddressDialog(form) {
          this.$refs[form].resetFields();
          this.dialogAddress.show = false
          this.dialogAddress.index = null;
        },

        countryChange(e) {
          const self = this;

          $.ajax({
            url: `/admin/countries/${e}/zones`,
            type: 'get',
            success: function(res) {
              self.source.zones = res.data.zones;
            }
          })
        },

        checkedBtnCheckoutConfirm() {
          console.log(1)
        }
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
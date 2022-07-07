@extends('layout.master')

@section('body-class', 'page-account-address')

@push('header')
  <script src="{{ asset('vendor/vue/2.6.14/vue.js') }}"></script>
  <script src="{{ asset('vendor/element-ui/2.15.6/js.js') }}"></script>
  <link rel="stylesheet" href="{{ asset('vendor/element-ui/2.15.6/css.css') }}">
@endpush

@section('content')
  <div class="container" id="address-app">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Library</li>
      </ol>
    </nav>

    <div class="row">
      <x-shop-sidebar/>

      <div class="col-12 col-md-9">
        <div class="card account-card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title">我的地址</h5>
          </div>
          <div class="card-body h-600">
            <button class="btn btn-dark mb-3" @click="editAddress"><i class="bi bi-plus-square-dotted me-1"></i> 添加新地址</button>
            <div class="addresses-wrap" v-cloak>
              <div class="row">
                <div class="col-6" v-for="address, index in addresses" :key="index" v-if="addresses.length">
                  <div :class="['item', address.default ? 'active' : '']" >
                    <div class="name-wrap">
                      <span class="name">@{{ address.name }}</span>
                      <span class="phone">@{{ address.phone }}</span>
                    </div>
                    <div class="zipcode">@{{ address.zipcode }}</div>
                    <div class="address-info">@{{ address.country }} @{{ address.zone }} @{{ address.city }} @{{ address.address_1 }}</div>
                    <div class="address-bottom">
                      <div><span class="badge bg-success" v-if="address.default">默认地址</span></div>
                      <div>
                        <a class="me-2" @click.stop="deleteAddress(index)">删除</a>
                        <a @click.stop="editAddress(index)">编辑</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    @include('shared.address-form', [
      'address_form_key' => 'form',
      'address_form_show' => 'editShow',
      'address_form_rules' => 'rules'
      ])
  </div>
@endsection

@push('add-scripts')
  <script>
    new Vue({
      el: '#address-app',

      data: {
        editIndex: null,

        editShow: false,

        form: {
          name: '',
          phone: '',
          country_id: @json((int)setting('system.country_id')),
          zipcode: '',
          zone_id: '',
          city: '',
          address_1: '',
          address_2: '',
          default: false,
        },

        addresses: @json($addresses ?? []),

        source: {
          countries: @json($countries ?? []),
          zones: []
        },

        rules: {
          name: [{required: true, message: '请输入姓名', trigger: 'blur'}, ],
          phone: [{required: true, message: '请输入联系电话', trigger: 'blur'}, ],
          address_1: [{required: true, message: '请输入详细地址 1', trigger: 'blur'}, ],
          zone_id: [{required: true, message: '请选择省份', trigger: 'blur'}, ],
          city: [{required: true, message: '请填写 city', trigger: 'blur'}, ],
        }
      },

      // 实例被挂载后调用
      mounted () {
      },

      beforeMount () {
        this.countryChange(this.form.country_id);
      },

      methods: {
        editAddress(index) {
          if (typeof index == 'number') {
            this.editIndex = index;
            this.form = JSON.parse(JSON.stringify(this.addresses[index]))
          }

          this.editShow = true
        },

        deleteAddress(index) {
          this.$confirm('确定要删除用户码？', '提示', {
            confirmButtonText: '确定',
            cancelButtonText: '取消',
            type: 'warning'
          }).then(() => {
            $http.delete('/account/addresses/' + this.addresses[index].id).then((res) => {
              this.$message.success(res.message);
              this.addresses.splice(index, 1)
            })
          }).catch(()=>{})
        },

        addressFormSubmit(form) {
          const self = this;

          this.$refs[form].validate((valid) => {
            if (!valid) {
              this.$message.error('请检查表单是否填写正确');
              return;
            }

            const type = this.form.id ? 'put' : 'post';
            const url = `/account/addresses${type == 'put' ? '/' + this.form.id : ''}`;

            $http[type](url, this.form).then((res) => {
              if (type == 'post') {
                this.addresses.push(res.data)
              } else {
                this.addresses[this.editIndex] = res.data
              }
              this.$message.success(res.message);
              this.$refs[form].resetFields();
              this.editShow = false
              this.editIndex = null;
            })
          });
        },

        closeAddressDialog(form) {
          this.$refs[form].resetFields();
          this.editShow = false
          this.editIndex = null;

          Object.keys(this.form).forEach(key => this.form[key] = '')
          this.form.country_id =  @json((int)setting('system.country_id'))
        },

        countryChange(e) {
          const self = this;

          $http.get(`/countries/${e}/zones`, null, {hload:true}).then((res) => {
            this.source.zones = res.data.zones;
          })
        },
      }
    })
  </script>
@endpush
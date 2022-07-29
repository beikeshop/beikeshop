@extends('layout.master')

@section('body-class', 'page-product')

@push('header')
  <script src="{{ asset('vendor/vue/2.6.14/vue.js') }}"></script>
  {{-- <script src="{{ asset('vendor/element-ui/2.15.6/js.js') }}"></script> --}}
  {{-- <link rel="stylesheet" href="{{ asset('vendor/element-ui/2.15.6/css.css') }}"> --}}
@endpush

@section('content')

  <div class="container" id="product-app" v-cloak>

    {{ Diglactic\Breadcrumbs\Breadcrumbs::render('product', $product) }}

    <div class="row mb-5" id="product-top">
      <div class="col-12 col-md-6">
        <div class="product-image d-flex align-items-start">
          <div class="left" v-if="images.length">
            <div :class="!index ? 'active' : ''" :data-image="image.image" v-for="image, index in images"><img :src="image.thumb" class="img-fluid"></div>
          </div>
          <div class="right">
            <img :src="images[0]?.image || '{{ asset('image/placeholder.png') }}'" class="img-fluid">
          </div>
        </div>
      </div>

      <div class="ps-lg-5 col-xl-5 col-lg-6 order-lg-2">
        <div class="peoduct-info">
          <h1>{{ $product['name'] }}</h1>
          <div class="rating-wrap d-flex">
            <div class="rating">
              @for ($i = 0; $i < 5; $i++)
              <i class="iconfont">&#xe628;</i>
              @endfor
            </div>
            <span class="text-muted">132 reviews</span>
          </div>
          <div class="price-wrap d-flex align-items-end">
            <div class="new-price">@{{ product.price_format }}</div>
            <div class="old-price text-muted text-decoration-line-through">@{{ product.origin_price_format }}</div>
          </div>

          <div class="variables-wrap" v-if="source.variables.length">
            <div class="variable-group mb-2" v-for="variable, variable_index in source.variables" :key="variable_index">
              <p class="mb-2"><strong>@{{ variable.name }}</strong></p>
              <div class="variable-info">
                <div
                  v-for="value, value_index in variable.values"
                  @click="checkedVariableValue(variable_index, value_index, value)"
                  :key="value_index"
                  :class="[value.selected ? 'selected' : '', value.disabled ? 'disabled' : '']">
                  @{{ value.name }}
                </div>
              </div>
            </div>
          </div>

          <div class="attribute-wrap">
            <table class="table table-striped table-borderless">
              <tbody>
                <tr>
                  <td>型号</td>
                  <td>@{{ product.model }}</td>
                </tr>
                <tr>
                  <td>Sku</td>
                  <td>@{{ product.sku }}</td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="quantity-btns d-flex">
            <div class="quantity-wrap">
              <input type="text" class="form-control" :disabled="!product.quantity" onkeyup="this.value=this.value.replace(/\D/g,'')" v-model="quantity" name="quantity">
              <div class="right">
                <i class="bi bi-chevron-up"></i>
                <i class="bi bi-chevron-down"></i>
              </div>
            </div>
            <button
              class="btn btn-outline-secondary ms-3 add-cart"
              :disabled="!product.quantity"
              @click="addCart(false)"
              ><i class="bi bi-cart-fill me-1"></i>加入购物车
            </button>
            <button
              class="btn btn-dark ms-3"
              :disabled="!product.quantity"
              @click="addCart(true)"
              ><i class="bi bi-bag-fill me-1"></i>立即购买
            </button>
          </div>
          <div class="add-wishlist">
            <button class="btn btn-link ps-0 text-dark" @click="addWishlist"><i class="bi bi-suit-heart-fill me-1"></i>加入收藏夹</button>
          </div>
        </div>
      </div>
    </div>

    <div class="product-description">
      <div class="nav nav-tabs nav-overflow justify-content-start justify-content-md-center border-bottom">
        <a class="nav-link active" data-bs-toggle="tab" href="#product-description">
          Description
        </a>
        <a class="nav-link" data-bs-toggle="tab" href="#description-1">
          Size &amp; Fit
        </a>
        <a class="nav-link" data-bs-toggle="tab" href="#description-2">
          Shipping &amp; Return
        </a>
      </div>
      <div class="tab-content">
        <div class="tab-pane fade show active" id="product-description" role="tabpanel" aria-labelledby="pills-home-tab">111</div>
        <div class="tab-pane fade" id="description-1" role="tabpanel" aria-labelledby="pills-profile-tab">222</div>
        <div class="tab-pane fade" id="description-2" role="tabpanel" aria-labelledby="pills-contact-tab">333</div>
      </div>
    </div>
  </div>
@endsection

@push('add-scripts')
  <script>
    let app = new Vue({
      el: '#product-app',

      data: {
        selectedVariantsIndex: [], // 选中的变量索引
        images: [],
        product: {
          id: 0,
          images: "",
          model: "",
          origin_price: 0,
          origin_price_format: "",
          position: 0,
          price: 0,
          price_format: "",
          quantity: 0,
          sku: "",
        },
        quantity: 1,
        source: {
          skus: @json($product['skus']),
          variables: @json($product['variables'] ?? []),
        }
      },

      computed: {
      },

      beforeMount () {
        const skus = JSON.parse(JSON.stringify(this.source.skus));
        const skuDefault = skus.find(e => e.is_default)
        this.selectedVariantsIndex = skuDefault.variants

        // 为 variables 里面每一个 values 的值添加 selected、disabled 字段
        if (this.source.variables.length) {
          this.source.variables.forEach(variable => {
            variable.values.forEach(value => {
              this.$set(value, 'selected', false)
              this.$set(value, 'disabled', false)
            })
          })

          this.checkedVariants()
          this.getSelectedSku();
          this.updateSelectedVariantsStatus()
        } else {
          // 如果没有默认的sku，则取第一个sku的第一个变量的第一个值
          this.product = skus[0];
          this.images = @json($product['images'] ?? []);
        }
      },

      methods: {
        checkedVariableValue(variable_idnex, value_index, value) {
          this.source.variables[variable_idnex].values.forEach((v, i) => {
            v.selected = i == value_index
          })

          this.updateSelectedVariantsIndex();
          this.getSelectedSku();
          this.updateSelectedVariantsStatus()
        },

        // 把对应 selectedVariantsIndex 下标选中 variables -> values 的 selected 字段为 true
        checkedVariants() {
          this.source.variables.forEach((variable, index) => {
            variable.values[this.selectedVariantsIndex[index]].selected = true
          })
        },

        getSelectedSku() {
          // 通过 selectedVariantsIndex 的值比对 skus 的 variables
          const sku = this.source.skus.find(sku => sku.variants.toString() == this.selectedVariantsIndex.toString())
          const spuImages = @json($product['images'] ?? []);
          this.images = [...sku.images, ...spuImages]
          this.product = sku;
        },

        addCart(isBuyNow = false) {
          $http.post('/carts', {sku_id: this.product.id, quantity: this.quantity}).then((res) => {
            bk.getCarts();
            layer.msg(res.message)
            if (isBuyNow) {
              location.href = '{{ shop_route("checkout.index") }}'
            }
          })
        },

        updateSelectedVariantsIndex() {
          // 获取选中的 variables 内 value的 下标 index 填充到 selectedVariantsIndex 中
          this.source.variables.forEach((variable, index) => {
            variable.values.forEach((value, value_index) => {
              if (value.selected) {
                this.selectedVariantsIndex[index] = value_index
              }
            })
          })
        },

        updateSelectedVariantsStatus() {
          // skus 里面 quantity 不为 0 的 sku.variants
          const skus = this.source.skus.filter(sku => sku.quantity > 0).map(sku => sku.variants);
          this.source.variables.forEach((variable, index) => {
            variable.values.forEach((value, value_index) => {
              const selectedVariantsIndex = this.selectedVariantsIndex.slice(0);

              selectedVariantsIndex[index] = value_index;
              const selectedSku = skus.find(sku => sku.toString() == selectedVariantsIndex.toString());
              if (selectedSku) {
                value.disabled = false;
              } else {
                value.disabled = true;
              }
            })
          });
        },

        addWishlist() {
          $http.post('account/wishlist', {product_id: this.product.id}).then((res) => {
            layer.msg(res.message)
          })
        },
      }
    });

    $("body").on("mouseover", ".product-image .left > div", function() {
      const image = $(this).data('image')
      $('.product-image .right').find('img').attr('src', image);
      $(this).addClass('active').siblings().removeClass('active');
    });
  </script>
@endpush

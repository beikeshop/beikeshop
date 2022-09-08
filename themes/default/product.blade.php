@extends('layout.master')

@section('body-class', 'page-product')

@push('header')
  <script src="{{ asset('vendor/vue/2.6.14/vue.js') }}"></script>
  <script src="{{ asset('vendor/swiper/swiper-bundle.min.js') }}"></script>
  <script src="{{ asset('vendor/zoom/jquery.zoom.min.js') }}"></script>
  <link rel="stylesheet" href="{{ asset('vendor/swiper/swiper-bundle.min.css') }}">
@endpush

@section('content')
  <div class="container" id="product-app" v-cloak>

    <x-shop-breadcrumb type="product" :value="$product['id']" />

    <div class="row mb-5" id="product-top">
      <div class="col-12 col-lg-6 mb-3">
        <div class="product-image d-flex align-items-start">

          <div class="left"  v-if="images.length">
            <div class="swiper" id="swiper">
              <div class="swiper-wrapper">
                <div class="swiper-slide" :class="!index ? 'active' : ''" v-for="image, index in images">
                  <a href="javascript:;" :data-image="image.preview" :data-zoom-image="image.popup">
                    <img :src="image.thumb" class="img-fluid">
                  </a>
                </div>
              </div>
              <div class="swiper-pager">
                  <div class="swiper-button-next new-feature-slideshow-next"></div>
                  <div class="swiper-button-prev new-feature-slideshow-prev"></div>
              </div>
            </div>
          </div>

          <div class="right" id="zoom">
            <img :src="images[0]?.preview || '{{ asset('image/placeholder.png') }}'" class="img-fluid">
          </div>
        </div>
      </div>

      <div class="col-12 col-lg-6">
        <div class="peoduct-info">
          <h1 class="mb-4">{{ $product['name'] }}</h1>
          <div class="price-wrap d-flex align-items-end">
            <div class="new-price fs-1 lh-1 fw-bold me-2">@{{ product.price_format }}</div>
            <div class="old-price text-muted text-decoration-line-through">@{{ product.origin_price_format }}</div>
          </div>
          <div class="stock-and-sku mb-4">
            <div class="d-flex">
              <span class="title text-muted">{{ __('product.quantity') }}:</span>
              <div :class="product.quantity > 0 ? 'text-success' : 'text-secondary'">
                <template v-if="product.quantity > 0">{{ __('shop/products.in_stock') }}</template>
                <template v-else>{{ __('shop/products.out_stock') }}</template>
              </div>
            </div>
            <div class="d-flex"><span class="title text-muted">SKU:</span>@{{ product.sku }}</div>
            <div class="d-flex"><span class="title text-muted">{{ __('shop/products.model') }}:</span> @{{ product.model }}</div>
          </div>
          @if (0)
          <div class="rating-wrap d-flex">
            <div class="rating">
              @for ($i = 0; $i < 5; $i++)
              <i class="iconfont">&#xe628;</i>
              @endfor
            </div>
            <span class="text-muted">132 reviews</span>
          </div>
          @endif


          <div class="variables-wrap mb-4" v-if="source.variables.length">
            <div class="variable-group mb-2" v-for="variable, variable_index in source.variables" :key="variable_index">
              <p class="mb-2">@{{ variable.name }}</p>
              <div class="variable-info">
                <div
                  v-for="value, value_index in variable.values"
                  @click="checkedVariableValue(variable_index, value_index, value)"
                  :key="value_index"
                  :class="[value.selected ? 'selected' : '', value.disabled ? 'disabled' : '']">
                  <span class="image" v-if="value.image"><img :src="value.image" class="img-fluid"></span>
                  @{{ value.name }}
                </div>
              </div>
            </div>
          </div>

          @if ($product['active'])
            <div class="quantity-btns">
              <div class="quantity-wrap">
                <input type="text" class="form-control" :disabled="!product.quantity" onkeyup="this.value=this.value.replace(/\D/g,'')" v-model="quantity" name="quantity">
                <div class="right">
                  <i class="bi bi-chevron-up"></i>
                  <i class="bi bi-chevron-down"></i>
                </div>
              </div>
              <button
                class="btn btn-outline-dark ms-md-3 add-cart"
                :disabled="!product.quantity"
                @click="addCart(false, this)"
                ><i class="bi bi-cart-fill me-1"></i>{{ __('shop/products.add_to_cart') }}
              </button>
              <button
                class="btn btn-dark ms-3"
                :disabled="!product.quantity"
                @click="addCart(true, this)"
                ><i class="bi bi-bag-fill me-1"></i>{{ __('shop/products.buy_now') }}
              </button>
            </div>
            <div class="add-wishlist">
              <button class="btn btn-link ps-0 text-dark" data-in-wishlist="{{ $product['in_wishlist'] }}" onclick="bk.addWishlist('{{ $product['id'] }}', this)">
                <i class="bi bi-heart{{ $product['in_wishlist'] ? '-fill' : '' }} me-1"></i> {{ __('shop/products.add_to_favorites') }}
              </button>
            </div>
          @else
            <div class="text-danger"><i class="bi bi-exclamation-circle-fill"></i> {{ __('product.has_been_inactive') }}</div>
          @endif
        </div>
      </div>
    </div>

    <div class="product-description">
      <div class="nav nav-tabs nav-overflow justify-content-start justify-content-md-center border-bottom mb-3">
        <h5 class="mb-0"><a class="nav-link fw-bold active" data-bs-toggle="tab" href="#product-description">
          {{ __('shop/products.product_details') }}
        </a></h5>
      </div>
      <div class="tab-content">
        <div class="tab-pane fade show active" id="product-description" role="tabpanel" aria-labelledby="pills-home-tab">
          {!! $product['description'] !!}
        </div>
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
          $('.product-image .swiper .swiper-slide').eq(0).addClass('active').siblings().removeClass('active');
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
          this.$nextTick(() => {
            $('#zoom img').attr('src', $('#swiper a').attr('data-image'));
            $('#zoom').trigger('zoom.destroy');
            $('#zoom').zoom({url: $('#swiper a').attr('data-zoom-image')});
          })
        },

        addCart(isBuyNow = false) {
          bk.addCart({sku_id: this.product.id, quantity: this.quantity, isBuyNow});
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
      }
    });

    $(document).on("mouseover", ".product-image .swiper .swiper-slide a", function() {
      $(this).parent().addClass('active').siblings().removeClass('active');
      $('#zoom').trigger('zoom.destroy');
      $('#zoom img').attr('src', $(this).attr('data-image'));
      $('#zoom').zoom({url: $(this).attr('data-zoom-image')});
    });

    var swiper = new Swiper("#swiper", {
      direction: "vertical",
      slidesPerView: 1,
      spaceBetween:3,
      breakpoints:{
        375:{
          slidesPerView: 3,
          spaceBetween:3,
        },
        480:{
          slidesPerView: 4,
          spaceBetween:27,
        },
        768:{
          slidesPerView: 6,
          spaceBetween:3,
        },

      },
      pagination: {
        el: ".swiper-pagination",
        clickable: true,
      },
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
      observer: true,
      observeParents: true
    });

    $(document).ready(function () {
      $('#zoom').trigger('zoom.destroy');
      $('#zoom').zoom({url: $('#swiper a').attr('data-zoom-image')});
    });
  </script>
@endpush

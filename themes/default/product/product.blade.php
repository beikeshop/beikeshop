@extends('layout.master')
@section('body-class', 'page-product')
@section('title', $product['meta_title'] ?: $product['name'])
@section('keywords', $product['meta_keywords'] ?: system_setting('base.meta_keyword'))
@section('description', $product['meta_description'] ?: system_setting('base.meta_description'))

@push('header')
  <script src="{{ asset('vendor/vue/2.7/vue' . (!config('app.debug') ? '.min' : '') . '.js') }}"></script>
  <script src="{{ asset('vendor/swiper/swiper-bundle.min.js') }}"></script>
  <script src="{{ asset('vendor/zoom/jquery.zoom.min.js') }}"></script>
  <link rel="stylesheet" href="{{ asset('vendor/swiper/swiper-bundle.min.css') }}">
  @if ($product['video'] && strpos($product['video'], '<iframe') === false)
  <script src="{{ asset('vendor/video/video.min.js') }}"></script>
  <link rel="stylesheet" href="{{ asset('vendor/video/video-js.min.css') }}">
  @endif
@endpush

@php
  $iframeClass = request('iframe') ? 'd-none' : '';
@endphp

@section('content')
  @if (!request('iframe'))
    <x-shop-breadcrumb type="product" :value="$product['id']" />
  @endif

  <div class="container {{ request('iframe') ? 'pt-4' : '' }}" id="product-app" v-cloak>
    <div class="row mb-md-5 mt-md-0" id="product-top">
      <div class="col-12 col-lg-6 mb-2">
        <div class="product-image d-flex align-items-start">
          @if(!is_mobile())
            <div class="left {{ $iframeClass }}"  v-if="images.length">
              <div class="swiper" id="swiper">
                <div class="swiper-wrapper">
                  <div class="swiper-slide" :class="!index ? 'active' : ''" v-for="image, index in images" :key="index">
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
              @include('product.product-video')
              <div class="product-img"><img :src="images.length ? images[0].preview : '{{ asset('image/placeholder.png') }}'" class="img-fluid"></div>
            </div>
          @else
            @include('product.product-video')
            <div class="swiper" id="swiper-mobile">
              <div class="swiper-wrapper">
                <div class="swiper-slide d-flex align-items-center justify-content-center" v-for="image, index in images" :key="index">
                  <img :src="image.preview" class="img-fluid">
                </div>
              </div>
              <div class="swiper-pagination mobile-pagination"></div>
            </div>
          @endif
        </div>
      </div>

      <div class="col-12 col-lg-6">
        <div class="peoduct-info product-mb-block">
          @hookwrapper('product.detail.name')
          <h1 class="mb-lg-4 mb-2 product-name">{{ $product['name'] }}</h1>
          @endhookwrapper
          @hookwrapper('product.detail.price')
          @if ((system_setting('base.show_price_after_login') and current_customer()) or !system_setting('base.show_price_after_login'))
          <div class="price-wrap d-flex align-items-end">
            <div class="new-price fs-1 lh-1 fw-bold me-2">@{{ product.price_format }}</div>
            <div class="old-price text-muted text-decoration-line-through" v-if="product.price != product.origin_price && product.origin_price !== 0">
              @{{ product.origin_price_format }}
            </div>
          </div>
          @else
          <div class="product-price">
            <div class="text-dark fs-6">{{ __('common.before') }} <a class="price-new fs-6 login-before-show-price" href="javascript:void(0);">{{ __('common.login') }}</a> {{ __('common.show_price') }}</div>
          </div>
          @endif

          @hook('product.detail.price.after')

          @endhookwrapper
          <div class="stock-and-sku mb-lg-4 mb-2">
            @hookwrapper('product.detail.quantity')
            <div class="d-lg-flex">
              <span class="title text-muted">{{ __('product.quantity') }}:</span>
              <span :class="product.quantity > 0 ? 'text-success' : 'text-secondary'">
                <template v-if="product.quantity > 0">{{ __('shop/products.in_stock') }}</template>
                <template v-else>{{ __('shop/products.out_stock') }}</template>
              </span>
            </div>
            @endhookwrapper

            @if ($product['brand_id'])
            @hookwrapper('product.detail.brand')
            <div class="d-lg-flex">
              <span class="title text-muted">{{ __('product.brand') }}:</span>
              <a href="{{ shop_route('brands.show', $product['brand_id']) }}">{{ $product['brand_name'] }}</a>
            </div>
            @endhookwrapper
            @endif

            @hookwrapper('product.detail.sku')
            <div class="d-lg-flex"><span class="title text-muted">SKU:</span>@{{ product.sku }}</div>
            @endhookwrapper

            @hookwrapper('product.detail.model')
            <div class="d-lg-flex" v-if="product.model"><span class="title text-muted">{{ __('shop/products.model') }}:</span> @{{ product.model }}</div>
            @endhookwrapper
          </div>
          @if (0)
          <div class="rating-wrap d-lg-flex">
            <div class="rating">
              @for ($i = 0; $i < 5; $i++)
              <i class="iconfont">&#xe628;</i>
              @endfor
            </div>
            <span class="text-muted">132 reviews</span>
          </div>
          @endif
          @hookwrapper('product.detail.variables')
          <div class="variables-wrap mb-md-4" v-if="source.variables.length">
            <div class="variable-group" v-for="variable, variable_index in source.variables" :key="variable_index">
              <p class="mb-2">@{{ variable.name }}</p>
              <div class="variable-info">
                <div
                  v-for="value, value_index in variable.values"
                  @click="checkedVariableValue(variable_index, value_index, value)"
                  :key="value_index"
                  data-bs-toggle="tooltip"
                  data-bs-placement="top"
                  :title="value.image ? value.name : ''"
                  :class="[value.selected ? 'selected' : '', value.disabled ? 'disabled' : '', value.image ? 'is-v-image' : '']">
                  <span class="image" v-if="value.image"><img :src="value.image" class="img-fluid"></span>
                  <span v-else>@{{ value.name }}</span>
                </div>
              </div>
            </div>
          </div>
          @endhookwrapper

          <div class="product-btns">
            @if ($product['active'])
              <div class="quantity-btns">
                @hook('product.detail.buy.before')
                @hookwrapper('product.detail.quantity.input')
                <div class="quantity-wrap">
                  <input type="text" class="form-control" :disabled="!product.quantity" onkeyup="this.value=this.value.replace(/\D/g,'')" v-model="quantity" name="quantity">
                  <div class="right">
                    <i class="bi bi-chevron-up"></i>
                    <i class="bi bi-chevron-down"></i>
                  </div>
                </div>
                @endhookwrapper
                @hookwrapper('product.detail.add_to_cart')
                <button
                  class="btn btn-outline-dark ms-md-3 add-cart fw-bold"
                  :product-id="product.id"
                  :product-price="product.price"
                  :disabled="!product.quantity"
                  @click="addCart(false, this)"
                  ><i class="bi bi-cart-fill me-1"></i>{{ __('shop/products.add_to_cart') }}
                </button>
                @endhookwrapper
                @hookwrapper('product.detail.buy_now')
                <button
                  class="btn btn-dark ms-3 btn-buy-now fw-bold"
                  :disabled="!product.quantity"
                  :product-id="product.id"
                  :product-price="product.price"
                  @click="addCart(true, this)"
                  ><i class="bi bi-bag-fill me-1"></i>{{ __('shop/products.buy_now') }}
                </button>
                @endhookwrapper
                @hook('product.detail.buy.after')
              </div>

              @if (current_customer() || !request('iframe'))
                @hookwrapper('product.detail.wishlist')
                <div class="add-wishlist">
                  <button class="btn btn-link ps-md-0 text-secondary" data-in-wishlist="{{ $product['in_wishlist'] }}" onclick="bk.addWishlist('{{ $product['id'] }}', this)">
                    <i class="bi bi-heart{{ $product['in_wishlist'] ? '-fill' : '' }} me-1"></i> <span>{{ __('shop/products.add_to_favorites') }}</span>
                  </button>
                </div>
                @endhookwrapper
              @endif
            @else
              <div class="text-danger"><i class="bi bi-exclamation-circle-fill"></i> {{ __('product.has_been_inactive') }}</div>
            @endif
          </div>

          @hook('product.detail.after')
        </div>
      </div>
    </div>

    <div class="product-description product-mb-block {{ $iframeClass }}">
      <div class="nav nav-tabs nav-overflow justify-content-start justify-content-md-center border-bottom mb-3">
        <a class="nav-link fw-bold active fs-5" data-bs-toggle="tab" href="#product-description">
          {{ __('shop/products.product_details') }}
        </a>
        @if ($product['attributes'])
        <a class="nav-link fw-bold fs-5" data-bs-toggle="tab" href="#product-attributes">
          {{ __('admin/attribute.index') }}
        </a>
        @endif
        @hook('product.tab.after.link')
      </div>
      <div class="tab-content">
        <div class="tab-pane fade show active" id="product-description" role="tabpanel">
          {!! $product['description'] !!}
        </div>
        <div class="tab-pane fade" id="product-attributes" role="tabpanel">
          <table class="table table-bordered attribute-table">
            @foreach ($product['attributes'] as $group)
              <thead class="table-light">
                <tr><td colspan="2"><strong>{{ $group['attribute_group_name'] }}</strong></td></tr>
              </thead>
              <tbody>
                @foreach ($group['attributes'] as $item)
                <tr>
                  <td>{{ $item['attribute'] }}</td>
                  <td>{{ $item['attribute_value'] }}</td>
                </tr>
                @endforeach
              </tbody>
            @endforeach
          </table>
        </div>
        @hook('product.tab.after.pane')
      </div>
    </div>
  </div>

  @if ($relations && !request('iframe'))
    <div class="relations-wrap mt-2 mt-md-5 product-mb-block">
      <div class="container position-relative">
        <div class="title text-center">{{ __('admin/product.product_relations') }}</div>
        <div class="product swiper-style-plus">
          <div class="swiper relations-swiper">
            <div class="swiper-wrapper">
              @foreach ($relations as $item)
              <div class="swiper-slide">
                @include('shared.product', ['product' => $item])
              </div>
              @endforeach
            </div>
          </div>
          <div class="swiper-pagination relations-pagination"></div>
          <div class="swiper-button-prev relations-swiper-prev"></div>
          <div class="swiper-button-next relations-swiper-next"></div>
        </div>
      </div>
    </div>
  @endif

  @hook('product.detail.footer')
@endsection

@push('add-scripts')
  <script>
    let swiperMobile = null;
    const isIframe = bk.getQueryString('iframe', false);

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
          this.getSelectedSku(false);
          this.updateSelectedVariantsStatus()
        } else {
          // 如果没有默认的sku，则取第一个sku的第一个变量的第一个值
          this.product = skus[0];
          this.images = @json($product['images'] ?? []);
        }
      },

      methods: {
        checkedVariableValue(variable_index, value_index, value) {
          $('.product-image .swiper .swiper-slide').eq(0).addClass('active').siblings().removeClass('active');
          this.source.variables[variable_index].values.forEach((v, i) => {
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

        getSelectedSku(reload = true) {
          // 通过 selectedVariantsIndex 的值比对 skus 的 variables
          const sku = this.source.skus.find(sku => sku.variants.toString() == this.selectedVariantsIndex.toString())
          this.images = @json($product['images'] ?? [])

          if (reload) {
            this.images.unshift(...sku.images)
          }

          this.product = sku;

          if (swiperMobile) {
            swiperMobile.slideTo(0, 0, false)
          }

          this.$nextTick(() => {
            $('#zoom img').attr('src', $('#swiper a').attr('data-image'));
            $('#zoom').trigger('zoom.destroy');
            $('#zoom').zoom({url: $('#swiper a').attr('data-zoom-image')});
          })

          closeVideo()
        },

        addCart(isBuyNow = false) {
          bk.addCart({sku_id: this.product.id, quantity: this.quantity, isBuyNow}, null, () => {
            if (isIframe) {
              let index = parent.layer.getFrameIndex(window.name); //当前iframe层的索引
              parent.bk.getCarts();

              setTimeout(() => {
                parent.layer.close(index);

                if (isBuyNow) {
                  parent.location.href = 'checkout'
                } else {
                  parent.$('.btn-right-cart')[0].click()
                }
              }, 400);
            } else {
              if (isBuyNow) {
                location.href = 'checkout'
              }
            }
          });
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

    $(document).on("mouseover", ".product-image #swiper .swiper-slide a", function() {
      $(this).parent().addClass('active').siblings().removeClass('active');
      $('#zoom').trigger('zoom.destroy');
      $('#zoom img').attr('src', $(this).attr('data-image'));
      $('#zoom').zoom({url: $(this).attr('data-zoom-image')});
      closeVideo()
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
      navigation: {
        nextEl: '.new-feature-slideshow-next',
        prevEl: '.new-feature-slideshow-prev',
      },
      observer: true,
      observeParents: true
    });

    var relationsSwiper = new Swiper ('.relations-swiper', {
      watchSlidesProgress: true,
      autoHeight: true,
      breakpoints:{
        320: {
          slidesPerView: 2,
          spaceBetween: 10,
        },
        768: {
          slidesPerView: 4,
          spaceBetween: 30,
        },
      },
      spaceBetween: 30,
      // 如果需要前进后退按钮
      navigation: {
        nextEl: '.relations-swiper-next',
        prevEl: '.relations-swiper-prev',
      },

      // 如果需要分页器
      pagination: {
        el: '.relations-pagination',
        clickable: true,
      },
    })

    @if (is_mobile())
      swiperMobile = new Swiper("#swiper-mobile", {
        slidesPerView: 1,
        pagination: {
          el: ".mobile-pagination",
        },
        observer: true,
        observeParents: true
      });
    @endif

    $(document).ready(function () {
      $('#zoom').trigger('zoom.destroy');
      $('#zoom').zoom({url: $('#swiper a').attr('data-zoom-image')});
    });

    const selectedVariantsIndex = app.selectedVariantsIndex;
    const variables = app.source.variables;

    const selectedVariants = variables.map((variable, index) => {
      return variable.values[selectedVariantsIndex[index]]
    });
  </script>
@endpush

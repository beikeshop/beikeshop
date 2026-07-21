@extends('layout.master')
@section('body-class', 'page-product')
@section('title', $product['meta_title'] ?: $product['name'])
@section('keywords', $product['meta_keywords'] ?: system_setting('base.meta_keyword'))
@section('description', $product['meta_description'] ?: system_setting('base.meta_description'))
@section('og_type', 'product')
@section('og_image', $product['images'][0]['popup'] ?? '')
@section('og_image_width', system_setting('base.product_image_origin_width'))
@section('og_image_height', system_setting('base.product_image_origin_height'))

@section('bk-page-loading', true)

@addScript(asset('vendor/vue/2.7/vue' . (!config('app.debug') ? '.min' : '') . '.js'))
@addStyle(asset('vendor/swiper/swiper-bundle.min.css'))
@addScript(asset('vendor/swiper/swiper-bundle.min.js'))
@addScript(asset('vendor/zoom/jquery.zoom.min.js'))

@push('header')
  @if ($has_video)
    <script src="{{ asset('vendor/video/video.min.js') }}" defer></script>
    <link rel="stylesheet" href="{{ asset('vendor/video/video-js.min.css') }}">
  @endif
  <style id="product-hide-elements">
    .product-description, .relations-wrap, footer {
      display: none;
    }
  </style>
@endpush

@php
  $iframeClass = request('iframe') ? 'd-none' : '';
@endphp

@section('content')
  @hook('product.detail.before')

  @if (!request('iframe'))
    <x-shop-breadcrumb type="product" :value="$product['id']"/>
  @endif

  <div class="container product-container {{ request('iframe') ? 'pt-4' : '' }}">
    @hook('product.detail.content.before')
    <div class="row mb-md-5 mt-md-0" id="product-top">
      <div class="col-12 col-lg-6 mb-2">
        @hookwrapper('product.detail.images')
        <div class="product-image">
          @if(!is_mobile())
            <div class="left {{ $iframeClass }}" v-if="images.length">
              <div class="swiper product-left-thumb-wrap" id="swiper">
                <div class="swiper-wrapper">
                  @foreach ($product['images'] as $item)
                  <div class="swiper-slide {{ $loop->first ? 'active' : '' }}">
                    <a href="javascript:;" data-image="{{ $item['preview'] }}" data-zoom-image="{{ $item['popup'] }}">
                      <img src="{{ $item['thumb'] }}" alt="{{ $product['name'] }}" class="img-fluid seo-img" width="120" height="120">
                    </a>
                  </div>
                  @endforeach
                </div>
                <div class="swiper-pager">
                  <div class="swiper-button-next new-feature-slideshow-next"></div>
                  <div class="swiper-button-prev new-feature-slideshow-prev"></div>
                </div>
              </div>
            </div>
            <div class="right" id="zoom">
              @include('product.product-video')
              <div class="product-img">
                <img alt="{{ $product['name'] }}"
                  src="{{ $product['images'][0]['preview'] ?? system_setting('base.placeholder') ?? asset('image/placeholder.png') }}"
                  class="img-fluid seo-img"
                  width="{{ system_setting('base.product_image_origin_width', 800) }}"
                  height="{{ system_setting('base.product_image_origin_height', 800) }}">
              </div>
            </div>
          @else
            @include('product.product-video')
            <div class="swiper" id="swiper-mobile">
              <div class="swiper-wrapper">
                @foreach ($product['images'] as $item)
                <div class="swiper-slide d-flex align-items-center justify-content-center">
                  <img src="{{ $item['preview'] }}" fetchpriority="high" width="{{ system_setting('base.product_image_origin_width', 800) }}" height="{{ system_setting('base.product_image_origin_height', 800) }}" class="img-fluid seo-img" alt="{{ $product['name'] }}">
                </div>
                @endforeach
              </div>
              <div class="swiper-pagination mobile-pagination"></div>
            </div>
          @endif
        </div>
        @endhookwrapper
      </div>

      <div class="col-12 col-lg-6">
        <div class="product-info product-mb-block" id="product-app" v-cloak>
          @hookwrapper('product.detail.name')
          <h1 class="mb-lg-4 mb-2 product-name">{{ $product['name'] }}</h1>
          @endhookwrapper
          @hookwrapper('product.detail.price')
          @if ((system_setting('base.show_price_after_login') and current_customer()) or !system_setting('base.show_price_after_login'))
            <div class="price-wrap d-flex align-items-end">
              <div class="new-price fs-1 lh-1 fw-bold me-2">@{{ product.price_format }}</div>
              <div class="old-price text-muted text-decoration-line-through"
                   v-if="product.price != product.origin_price && product.origin_price !== 0">
                @{{ product.origin_price_format }}
              </div>
              @hook('product.detail.origin_price.after')
            </div>
          @else
            <div class="product-price">
              <div class="text-dark fs-6">{{ __('common.before') }} <a class="price-new fs-6 login-before-show-price" href="javascript:void(0);">{{ __('common.login') }}</a> {{ __('common.show_price') }}
              </div>
            </div>
          @endif

          @hook('product.detail.price.after')

          @endhookwrapper
          <div class="stock-and-sku mb-lg-4 mb-2">
            @hook('shop.product.detail.quantity.before')

            @hookwrapper('product.detail.quantity')
            <div class="d-lg-flex">
              <span class="title text-muted">{{ __('product.stock') }}:</span>
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
            <div class="d-lg-flex" v-if="product.model"><span
                class="title text-muted">{{ __('shop/products.model') }}:</span> @{{ product.model }}
            </div>
            @endhookwrapper

            @hookwrapper('product.detail.weight')
            <div class="d-lg-flex" v-if="product.weight != 0"><span class="title text-muted">{{ __('admin/product.weight_text') }}:</span> @{{ product.weight }} {{ __('product.' . $product['weight_class']) }}</div>
            @endhookwrapper

            @hook('shop.product.detail.weight.after')
          </div>
          @hookwrapper('product.detail.variables')
          <div class="variables-wrap mb-md-3" v-if="source.variables.length">
            <div class="variable-group" v-for="variable, variable_index in source.variables" :key="variable_index">
              <p class="mb-2">
                @{{ variable.name }}
                <span class="text-secondary" v-if="selectedVariantsIndex[variable_index] !== undefined && selectedVariantsIndex[variable_index] !== null">
                  : @{{ variable.values[selectedVariantsIndex[variable_index]].name }}
                </span>
              </p>
              <div class="variable-info">
                <div
                  v-for="value, value_index in variable.values"
                  @click="checkedVariableValue(variable_index, value_index, value)"
                  :key="value_index"
                  data-bs-toggle="tooltip"
                  data-bs-placement="top"
                  :title="value.image ? value.name : ''"
                  :class="[value.selected ? 'selected' : '', value.disabled ? 'disabled' : '', value.image ? 'is-v-image' : '']">
                  <span class="image" v-if="value.image"><img :src="value.image" class="img-fluid" :alt="value.name"></span>
                  <span v-else>@{{ value.name }}</span>
                </div>
              </div>
            </div>
          </div>
          @endhookwrapper

          @hook('shop.product.detail.product-btns.before')

          @hookwrapper('product.detail.quantity.input')
          <div class="mb-md-3">
            <p class="mb-2">{{ __('rma.quantity') }}:</p>
            <div class="input-group quantity-wrap">
              <button class="btn quantity-reduce" type="button"><i class="bi bi-dash-lg"></i></button>
              <input type="text" class="form-control" :disabled="!product.quantity || product.active != 1" onkeyup="this.value=this.value.replace(/\D/g,'')" v-model="quantity" name="quantity" placeholder="" aria-label="Example text with button addon" aria-describedby="button-addon1">
              <button class="btn quantity-increase" type="button"><i class="bi bi-plus-lg"></i></button>
            </div>
          </div>
          @endhookwrapper

          <div class="product-btns">
            @hook('product.detail.buy.before')
            <div class="add-cart-btns">
              @hook('shop.product.detail.btns.before')

              @hookwrapper('product.detail.add_to_cart')
              <button
                class="btn btn-outline-dark add-cart fw-bold"
                :product-id="product.id"
                :product-price="product.price"
                :disabled="!product.quantity || product.active != 1"
                @click="addCart(false, this)"
              ><i class="bi bi-cart-fill me-1"></i>{{ __('shop/products.add_to_cart') }}
              </button>
              @endhookwrapper
              @hookwrapper('product.detail.buy_now')
              <button
                class="btn btn-dark ms-md-3 btn-buy-now fw-bold"
                :disabled="!product.quantity || product.active != 1"
                :product-id="product.id"
                :product-price="product.price"
                @click="addCart(true, this)"
              ><i class="bi bi-bag-fill me-1"></i>{{ __('shop/products.buy_now') }}
              </button>
              @endhookwrapper

              @hook('shop.product.detail.btns.after')
            </div>
            @hook('product.detail.buy.after')
            @if ($product['active'])
              @if (current_customer() || !request('iframe'))
                @hookwrapper('product.detail.wishlist')
                <div class="add-wishlist">
                  <button class="btn btn-link ps-md-0 text-secondary" data-in-wishlist="{{ $product['in_wishlist'] }}"
                          onclick="bk.addWishlist('{{ $product['id'] }}', this)">
                    <i class="bi bi-heart{{ $product['in_wishlist'] ? '-fill' : '' }} me-1"></i>
                    <span>{{ __('shop/products.add_to_favorites') }}</span>
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

    @hook('product.tab.iframe.before')
    @hookwrapper('shop.product.description')
    <div class="product-description product-mb-block {{ $iframeClass }}">
      <div class="nav nav-tabs nav-overflow justify-content-start justify-content-md-center border-bottom mb-3">
        @hook('shop.product.description.tabs.before')
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
        @hook('shop.product.description.tabs.content.before')
        <div class="tab-pane fade show active" id="product-description" role="tabpanel">
          <div class="rich-text-editor-content">{!! $product['description'] !!}</div>
        </div>
        <div class="tab-pane fade" id="product-attributes" role="tabpanel">
          <table class="table table-bordered attribute-table">
            @foreach ($product['attributes'] as $group)
              <thead class="table-light">
              <tr>
                <td colspan="2"><strong>{{ $group['attribute_group_name'] }}</strong></td>
              </tr>
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
    @endhookwrapper
    @hook('product.detail.content.after')
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
    @hook('product.detail.script.before')

    let swiperMobile = null;
    var swiper = null;
    const isIframe = bk.getQueryString('iframe', false);
    const productImageOriginWidth = @json(system_setting('base.product_image_origin_width', 800));
    const productImageOriginHeight = @json(system_setting('base.product_image_origin_height', 800));

    $(function () {
      descriptionImagesLazy()
      $('#zoom').trigger('zoom.destroy');
      $('#zoom').zoom({url: $('#swiper a').attr('data-zoom-image')});

      var relationsSwiper = new Swiper('.relations-swiper', {
        watchSlidesProgress: true,
        autoHeight: true,
        breakpoints: {
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
    });

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
          weight: 0,
          price_format: "",
          quantity: 0,
          sku: "",
        },
        quantity: 1,
        source: {
          skus: @json($product['skus']),
          weight: @json($product['weight'] ?? ''),
          variables: @json($product['variables'] ?? []),
        },
        request_variant: @json(request('variant')),
        extraCartParams: {},
        @hook('product.detail.vue.data')
      },

      beforeMount() {
        @hook('product.detail.vue.beforeMount')
      },

      mounted() {
        $('.bk-page-loading').fadeOut();
        $('#product-hide-elements').remove();
        const skus = JSON.parse(JSON.stringify(this.source.skus));

        this.product = skus[0];
        this.images = @json($product['images'] ?? []);

        if (this.source.variables.length) {
          // 为 variables 里面每一个 values 的值添加 selected、disabled 字段
          this.source.variables.forEach(variable => {
            variable.values.forEach(value => {
              this.$set(value, 'selected', false)
              this.$set(value, 'disabled', false)
            })
          })

          if (this.request_variant && this.source.skus.find(sku => sku.sku == this.request_variant)) {
            const sku = this.source.skus.find(sku => sku.sku == this.request_variant)
            this.selectedVariantsIndex = JSON.parse(JSON.stringify(sku.variants))
            this.checkedVariants()
            this.getSelectedSku(false);
            this.updateSelectedVariantsStatus()
          }
        } else {
          this.product.weight = this.source.weight;
        }

        this.initSwiper();

        @hook('product.detail.vue.mounted')
      },

      methods: {
        normalizeVariants(selected, length) {
          return Array.from({ length }, (_, i) => selected?.[i] ?? 0);
        },

        checkedVariableValue(variable_index, value_index, value) {
          $('.product-image .swiper .swiper-slide').eq(0).addClass('active').siblings().removeClass('active');
          this.source.variables[variable_index].values.forEach((v, i) => {
            v.selected = i == value_index
          })

          this.updateSelectedVariantsIndex();
          this.getSelectedSku();
          this.updateSelectedVariantsStatus()
          $('.variables-wrap').removeClass('error');
        },

        // 把对应 selectedVariantsIndex 下标选中 variables -> values 的 selected 字段为 true
        checkedVariants() {
          this.source.variables.forEach((variable, index) => {
            variable.values[this.selectedVariantsIndex[index]].selected = true
          })
        },

        getSelectedSku(reload = true) {
          const filledCount = this.selectedVariantsIndex.filter(v => v !== undefined && v !== null).length;

          // 通过 selectedVariantsIndex 的值比对 skus 的 variables
          let sku = this.source.skus.find(sku => sku.variants.toString() == this.selectedVariantsIndex.toString())

          if (filledCount < this.source.variables.length) {
            const selectedVariantsIndexLight = this.normalizeVariants(this.selectedVariantsIndex, this.source.variables.length);
            sku = this.source.skus.find(sku => sku.variants.toString() == selectedVariantsIndexLight.toString());
          }

          this.images = @json($product['images'] ?? []);
          this.images.unshift(...sku.images);
          this.product = sku;

          if (swiperMobile) {
            swiperMobile.slideTo(0, 0, false)
          }

          if (filledCount == this.source.variables.length) {
            window.history.replaceState(null, '', bk.updateQueryStringParameter(window.location.href, 'variant', sku.sku));
          }

          setTimeout(() => {
            this.updateProductImage()
            $('#zoom img').attr('src', $('#swiper a').attr('data-image'));
            $('#zoom').trigger('zoom.destroy');
            $('#zoom').zoom({url: $('#swiper a').attr('data-zoom-image')});
          }, 0);

          closeVideo()
        },

        addCart(isBuyNow = false) {
          //判断如果是多规格 并且没有选择组合
          const realLength = this.selectedVariantsIndex.filter(v => v !== undefined).length;

          if (this.source.variables.length && realLength < this.source.variables.length) {
            layer.msg('{{ __('shop/products.error_variables') }}');

            $('html, body').animate({scrollTop: 0}, 200);
            $('.variables-wrap').addClass('error');
            return;
          }

          let params = {
            sku_id: this.product.id,
            quantity: this.quantity,
            isBuyNow,
            ...this.extraCartParams // 插件扩展参数
          };

          // 插件扩展方法
          if (typeof this.beforeAddCartHooks === 'function') {
            const beforeAddCartHooks = this.beforeAddCartHooks(params);
            if (beforeAddCartHooks === false) {
              return;
            }
          }

          bk.addCart(params, null, () => {
            const lang = "{{ locale() === system_setting('base.locale') ? "null": session()->get('locale') }}";
            let path = '/' + '{{ session()->get('locale') }}' + '/checkout';
            if(lang === "null") {
              path = '/checkout';
            }

            if (isIframe) {
              let index = parent.layer.getFrameIndex(window.name); //当前iframe层的索引
              parent.bk.getCarts();
              setTimeout(() => {
                parent.layer.close(index);
                if (isBuyNow) {
                  parent.location.href = path;
                } else {
                  parent.$('.btn-right-cart')[0].click()
                }
              }, 400);
            } else {
              if (isBuyNow) {
                location.href = path;
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
          // 取出所有有库存且 active=1 的 SKU 的 variants
          const skus = this.source.skus.filter(sku => sku.active == 1 && sku.quantity > 0).map(sku => sku.variants);

          this.source.variables.forEach((variable, index) => {
            variable.values.forEach((value, value_index) => {
              // 拷贝当前已选择的规格索引
              const selectedVariantsIndex = this.selectedVariantsIndex.slice(0);

              selectedVariantsIndex[index] = value_index;

              const selectedSku = skus.find(sku => {
                return selectedVariantsIndex.every((v, i) => {
                  if (v === undefined || v === null) return true; // 这一维没选，不限制
                  return sku[i] == v; // 已经选择的维度必须匹配
                });
              });

              value.disabled = !selectedSku;
            });
          });
        },

        updateProductImage() {
          if (this.images.length) {
            if ($('.product-left-thumb-wrap').length) {
              if (swiper) {
                swiper.removeAllSlides()
                const slides = this.images.map((image, index) => `
                  <div class="swiper-slide ${index == 0 ? 'active' : ''}">
                    <a href="javascript:;" data-image="${image['preview']}" data-zoom-image="${image['popup']}">
                      <img src="${image['thumb']}" alt="${$('.product-name').text()}" class="img-fluid seo-img" width="120" height="120">
                    <\/a>
                  <\/div>
                `);
                swiper.appendSlide(slides);

                $('#zoom .product-img img').prop('src', this.images[0]['preview'])
              }
            } else {
              if (swiperMobile) {
                swiperMobile.removeAllSlides()
                const slides = this.images.map((image, index) => `
                  <div class="swiper-slide ${index == 0 ? 'active' : ''}">
                    <img src="${image['preview']}" alt="${$('.product-name').text()}" class="img-fluid seo-img" width="${productImageOriginWidth}" height="${productImageOriginHeight}">
                  <\/div>
                `);
                swiperMobile.appendSlide(slides);
              }
            }
          }
        },

        initSwiper() {
          swiper = new Swiper("#swiper", {
            direction: "vertical",
            slidesPerView: 1,
            spaceBetween: 3,
            autoHeight: true,
            mousewheel: true,
            breakpoints: {
              375: {
                slidesPerView: 3,
                spaceBetween: 3,
              },
              480: {
                slidesPerView: 4,
                spaceBetween: 27,
              },
              768: {
                slidesPerView: 6,
                spaceBetween: 3,
              },
            },
            navigation: {
              nextEl: '.new-feature-slideshow-next',
              prevEl: '.new-feature-slideshow-prev',
            },
            observeParents: true
          });

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
        },

        @hook('product.detail.vue.methods')
      },

      @hook('product.detail.vue.hooks')
    });

    $(document).on("mouseover", ".product-image #swiper .swiper-slide a", function () {
      $(this).parent().addClass('active').siblings().removeClass('active');
      $('#zoom').trigger('zoom.destroy');
      $('#zoom img').attr('src', $(this).attr('data-image'));
      $('#zoom').zoom({url: $(this).attr('data-zoom-image')});
      closeVideo()
    });

    const selectedVariantsIndex = app.selectedVariantsIndex;
    const variables = app.source.variables;

    const selectedVariants = variables.map((variable, index) => {
      return variable.values[selectedVariantsIndex[index]]
    });

    // 优化详情描述里面的图片加载 -> 懒加载
    const descriptionImagesLazy = () => {
      var $content = $('.rich-text-editor-content');
      if (!$content.length) return;

      var $imgs = $content.find('img');
      $imgs.each(function (index, img) {
        var $img = $(img);

        $img.addClass('lazyload');

        var src = $img.attr('src');
        if (src) {
          $img.attr('data-src', src);
          $img.attr('src', '');
        }
      });
    };

    @hook('product.detail.script.after')
  </script>
@endpush

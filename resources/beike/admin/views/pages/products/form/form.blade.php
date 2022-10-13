@extends('admin::layouts.master')

@section('title', __('admin/product.products_show'))

@section('body-class', 'page-product-form')

@push('header')
  <script src="{{ asset('vendor/vue/Sortable.min.js') }}"></script>
  <script src="{{ asset('vendor/vue/vuedraggable.js') }}"></script>
  <script src="{{ asset('vendor/tinymce/5.9.1/tinymce.min.js') }}"></script>
@endpush

@section('content')
    @if ($errors->has('error'))
      <x-admin-alert type="danger" msg="{{ $errors->first('error') }}" class="mt-4" />
    @endif


    @if ($errors->any())
      <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
          <div>{{ $error }}</div>
        @endforeach
      </div>
    @endif

  <ul class="nav nav-tabs nav-bordered mb-3" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-basic" type="button" >{{ __('admin/product.basic_information') }}</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-descriptions" type="button">{{ __('admin/product.product_details') }}</button>
    </li>
  </ul>

  <div class="card">
    {{-- <div class="card-header"><h6 class="card-title">基础信息</h6></div> --}}
    <div class="card-body">
      <form novalidate class="needs-validation" action="{{ $product->id ? admin_route('products.update', $product) : admin_route('products.store') }}"
        method="POST" id="app">
        @csrf
        @method($product->id ? 'PUT' : 'POST')
        <input type="hidden" name="_redirect" value="{{ $_redirect }}" />

        <div class="tab-content">
          <div class="tab-pane fade show active" id="tab-basic">
            <h6 class="border-bottom pb-3 mb-4">{{ __('common.data') }}</h6>
            <x-admin-form-input-locale :width="600" name="descriptions.*.name" title="{{ __('common.name') }}" :value="$descriptions" :required="true" />
            <x-admin::form.row title="{{ __('common.image') }}">
              <draggable
                element="div"
                ghost-class="dragabble-ghost"
                class="product-images d-flex flex-wrap"
                :list="form.images"
                :options="{animation: 200, filter: '.set-product-img'}"
                >
                <div v-for="image, index in form.images" :key="index" class="wh-80 product-item position-relative me-2">
                  <div class="position-absolute top-0 end-0">
                    <button class="btn btn-danger btn-sm wh-20 p-0" @click="removeImages(index)" type="button"><i class="bi bi-trash"></i></button>
                  </div>
                  <img :src="thumbnail(image)" class="img-fluid">
                  <input type="hidden" name="images[]" :value="image">
                </div>
                <div class="set-product-img wh-80" @click="addProductImages"><i class="bi bi-plus fs-1 text-muted"></i></div>
              </draggable>
              <div class="help-text mb-1 mt-2">{{ __('admin/product.image_help') }}</div>
            </x-admin::form.row>
            {{-- <x-admin-form-input name="video" title="视频" :value="old('video', $product->video ?? '')" /> --}}
            <x-admin-form-input name="position" :title="__('common.sort_order')" :value="old('position', $product->position ?? '0')" />

            <x-admin::form.row :title="__('admin/brand.index')">
              <input type="text" value="{{ $product->brand->name ?? '' }}" id="brand-autocomplete" class="form-control wp-400 " />
              <input type="hidden" name="brand_id" value="{{ old('brand_id', $product->brand_id ?? '') }}" />
            </x-admin::form.row>

            <x-admin-form-select :title="__('admin/tax_class.index')" name="tax_class_id" :value="old('tax_class_id', $product->tax_class_id ?? '')" :options="$tax_classes" key="id" label="title" />
            <x-admin-form-switch name="active" :title="__('common.status')" :value="old('active', $product->active ?? 1)" />

            <x-admin::form.row :title="__('admin/category.index')">
              <div class="wp-400 form-control" style="max-height: 240px;overflow-y: auto">
                @foreach ($source['categories'] as $_category)
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="categories[]" value="{{ $_category->id }}"
                  id="category-{{ $_category->id }}" {{ in_array($_category->id, $category_ids) ? 'checked' : '' }}>
                  <label class="form-check-label" for="category-{{ $_category->id }}">
                    {{ $_category->name }}
                  </label>
                </div>
                @endforeach
              </div>
            </x-admin::form.row>

            <div>
              <h5 class="border-bottom pb-3 mb-4">{{ __('admin/product.stocks') }}</h5>

              <x-admin::form.row :title="__('admin/product.enable_multi_spec')">
                <el-switch v-model="editing.isVariable" class="mt-2"></el-switch>
              </x-admin::form.row>

              <input type="hidden" name="variables" :value="JSON.stringify(form.variables)">

              <div class="row g-3 mb-3" v-if="editing.isVariable">
                <label for="" class="wp-200 col-form-label text-end"></label>
                <div class="col-auto wp-200-">
                  <div class="selectable-variants">
                    <div>
                      <draggable :list="source.variables" :options="{animation: 100}">
                        <div v-for="(variant, variantIndex) in source.variables" :id="'selectable-variant-' + variantIndex">
                          <div class="title">
                            <div>
                              <b>@{{ variant.name[current_language_code] }}</b>
                              <el-link type="primary" @click="modalVariantOpenButtonClicked(variantIndex, null)">{{ __('common.edit') }}</el-link>
                              <el-link type="danger" class="ms-2" @click="removeSourceVariant(variantIndex)">{{ __('common.delete') }}</el-link>
                            </div>
                            <div>
                              <el-checkbox v-model="variant.isImage" @change="(e) => {variantIsImage(e, variantIndex)}" border size="mini" class="me-2 bg-white">{{ __('admin/product.add_variable_image') }}</el-checkbox>
                              <el-button type="primary" plain size="mini" @click="modalVariantOpenButtonClicked(variantIndex, -1)">{{ __('admin/product.add_variable_value') }}</el-button>
                            </div>
                          </div>
                           <draggable
                             element="div"
                             @start="isMove = true"
                             v-if="variant.values.length"
                             class="variants-wrap"
                             @update="(e) => {swapSourceVariantValue(e, variantIndex)}"
                             @end="isMove = false"
                             ghost-class="dragabble-ghost"
                             :list="variant.values"
                             :options="{animation: 100}"
                             >
                             <div v-for="(value, value_index) in variant.values" :key="value_index" class="variants-item" @dblclick="modalVariantOpenButtonClicked(variantIndex, value_index)">
                               {{-- <div class="value-img" v-if="variant.isImage"> --}}
                                 {{-- <a href="" :id="'value-img-' + i + '-' + value_index" data-toggle="image" data-no-preview> --}}
                                   {{-- <img :src="thumbnail(value.image)" class="img-responsive" /> --}}
                                 {{-- </a> --}}
                               {{-- </div> --}}

                               <div class="open-file-manager variant-value-img" v-if="variant.isImage">
                                 <div>
                                   <img :src="thumbnail(value.image)" class="img-fluid">
                                 </div>
                               </div>
                               <input type="hidden" v-model="value.image">

                               <div class="btn-remove" @click="removeSourceVariantValue(variantIndex, value_index)"><i class="el-icon-error"></i></div>
                               <div class="name">
                                 @{{ value.name[current_language_code] }}
                               </div>
                             </div>
                          </draggable>
                          <div v-else>
                            <div class="p-2" @click="modalVariantOpenButtonClicked(variantIndex, -1)">{{ __('admin/product.add_variable_value') }}</div>
                          </div>
                        </div>
                      </draggable>

                      <el-button type="primary" plain size="small" @click="modalVariantOpenButtonClicked(-1, null)" class="btn btn-xs mr-1 mb-1">{{ __('admin/product.add_variable') }}</el-button>
                    </div>

                    <div v-if="form.skus.length && form.variables.length" class="mt-3">
                      <table class="table table-bordered table-hover">
                        <thead>
                          <th v-for="(variant, index) in form.variables" :key="'pv-header-' + index">
                            @{{ variant.name[current_language_code] || 'No name' }}
                          </th>
                          <th width="106px">{{ __('common.image') }}</th>
                          <th>{{ __('admin/product.model') }}</th>
                          <th>sku</th>
                          <th>{{ __('admin/product.price') }}</th>
                          <th>{{ __('admin/product.origin_price') }}</th>
                          <th>{{ __('admin/product.cost_price') }}</th>
                          <th>{{ __('admin/product.quantity') }}</th>
                        </thead>
                        <tbody>
                          <tr v-for="(sku, skuIndex) in form.skus" :key="skuIndex">
                            <template v-for="(variantValueIndex, j) in sku.variants">
                              <td v-if="skuIndex % variantValueRepetitions[j] == 0" :key="'pvv' + skuIndex + '-' + j"
                                :rowspan="variantValueRepetitions[j]">
                                <span>@{{ form.variables[j].values[variantValueIndex].name[current_language_code] || 'No name' }}</span>
                              </td>
                            </template>
                            <td>
                              <div class="product-images d-flex flex-wrap" style="margin-right: -8px">
                                <div v-for="image, index in sku.images" :key="index" class="product-item wh-40 border d-flex justify-content-center align-items-center me-2 mb-2 position-relative">
                                  <div class="position-absolute top-0 end-0">
                                    <button class="btn btn-danger btn-sm wh-20 p-0" @click="removeSkuImages(skuIndex, index)" type="button"><i class="bi bi-trash"></i></button>
                                  </div>
                                  <img :src="thumbnail(image)" class="img-fluid">
                                  <input type="hidden" class="form-control" v-model="sku.images[index]" :name="'skus[' + skuIndex + '][images][]'"
                                placeholder="image">
                                </div>
                                <div class="border d-flex justify-content-center align-items-center border-dashed bg-light wh-40" @click="addProductImages(skuIndex)"><i class="bi bi-plus fs-3 text-muted"></i></div>
                              </div>
                              <input type="hidden" class="form-control" :name="'skus[' + skuIndex + '][is_default]'" :value="skuIndex == 0 ? 1 : 0">
                              <input v-for="(variantValueIndex, j) in sku.variants" type="hidden"
                                :name="'skus[' + skuIndex + '][variants][' + j + ']'" :value="variantValueIndex">
                            </td>
                            <td><input type="text" class="form-control" v-model="sku.model" :name="'skus[' + skuIndex + '][model]'"
                                placeholder="{{ __('admin/product.model') }}"></td>
                            <td>
                              <input type="text" class="form-control" v-model="sku.sku" :name="'skus[' + skuIndex + '][sku]'" placeholder="sku" :style="sku.is_default ? 'margin-top: 19px;' : ''" required>
                              <span role="alert" class="invalid-feedback">{{ __('common.error_required', ['name' => 'sku']) }}</span>
                              <span v-if="sku.is_default" class="text-success">{{ __('admin/product.default_main_product') }}</span>
                            </td>
                            <td>
                              <input type="number" class="form-control" v-model="sku.price" :name="'skus[' + skuIndex + '][price]'" step="any"
                                placeholder="{{ __('admin/product.price') }}" required>
                              <span role="alert" class="invalid-feedback">{{ __('common.error_required', ['name' => __('admin/product.price')]) }}</span>
                            </td>
                            <td><input type="number" class="form-control" v-model="sku.origin_price" :name="'skus[' + skuIndex + '][origin_price]'" step="any"
                              placeholder="{{ __('admin/product.origin_price') }}" required>
                              <span role="alert" class="invalid-feedback">{{ __('common.error_required', ['name' => __('admin/product.origin_price')]) }}</span>
                            </td>
                            <td><input type="number" class="form-control" v-model="sku.cost_price" :name="'skus[' + skuIndex + '][cost_price]'"
                                placeholder="{{ __('admin/product.cost_price') }}">
                            </td>
                            <td><input type="number" class="form-control" v-model="sku.quantity" :name="'skus[' + skuIndex + '][quantity]'"
                                placeholder="{{ __('admin/product.quantity') }}"></td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <input class="form-control d-none" :value="form.skus[0]?.variants?.length || ''" required>
                  <div class="invalid-feedback" style="font-size: 16px"><i class="bi bi-exclamation-circle-fill"></i> {{ __('admin/product.add_variable') }}</div>
                </div>
              </div>

              <template v-if="!editing.isVariable">
                <input type="hidden" value="{{ old('skus.0.image', $product->skus[0]->image ?? '') }}" name="skus[0][image]">
                <x-admin-form-input name="skus[0][model]" :title="__('admin/product.model')" :value="old('skus.0.model', $product->skus[0]->model ?? '')" />
                <x-admin-form-input name="skus[0][sku]" title="sku" :value="old('skus.0.sku', $product->skus[0]->sku ?? '')" required />
                <x-admin-form-input name="skus[0][price]" type="number" :title="__('admin/product.price')" :value="old('skus.0.price', $product->skus[0]->price ?? '')" step="any" required />
                <x-admin-form-input name="skus[0][origin_price]" type="number" :title="__('admin/product.origin_price')" :value="old('skus.0.origin_price', $product->skus[0]->origin_price ?? '')" step="any" required />
                <x-admin-form-input name="skus[0][cost_price]" type="number" :title="__('admin/product.cost_price')" :value="old('skus.0.cost_price', $product->skus[0]->cost_price ?? '')" />
                <x-admin-form-input name="skus[0][quantity]" type="number" :title="__('admin/product.quantity')" :value="old('skus.0.quantity', $product->skus[0]->quantity ?? '')" />
                <input type="hidden" name="skus[0][variants]" placeholder="variants" value="">
                <input type="hidden" name="skus[0][position]" placeholder="position" value="0">
                <input type="hidden" name="skus[0][is_default]" placeholder="is_default" value="1">
              </template>
            </div>
          </div>
          <div class="tab-pane fade" id="tab-descriptions">
            <h6 class="border-bottom pb-3 mb-4">{{ __('admin/product.product_details') }}</h6>
            <x-admin::form.row :title="__('admin/product.product_details')">

              <ul class="nav nav-tabs mb-3" role="tablist">
                @foreach ($languages as $language)
                  <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $loop->first ? 'active' : '' }}" data-bs-toggle="tab" data-bs-target="#tab-descriptions-{{ $language->code }}" type="button" >{{ $language->name }}</button>
                  </li>
                @endforeach
              </ul>

              <div class="tab-content">
                @foreach ($languages as $language)
                  <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="tab-descriptions-{{ $language->code }}">
                    <textarea name="descriptions[{{ $language->code }}][content]" class="form-control tinymce">
                      {{ old('content', $product->descriptions->keyBy('locale')[$language->code]->content ?? '') }}
                    </textarea>
                  </div>
                @endforeach
              </div>
            </x-admin::form.row>
          </div>
        </div>


        <x-admin::form.row title="">
          <button type="submit" class="btn btn-primary mt-3 btn-lg">{{ __('common.save') }}</button>
        </x-admin::form.row>

        <el-dialog
          title="{{ __('common.edit') }}"
          :visible.sync="dialogVariables.show"
          width="400"
          @close="closedialogVariablesFormDialog('form')"
          :close-on-click-modal="false"
          >
          <el-form ref="form" :rules="rules" :model="dialogVariables.form" label-width="100px">
            <el-form-item label="{{ __('common.name') }}" required class="language-inputs">
              <el-form-item  :prop="'name.' + lang.code" :inline-message="true"  v-for="lang, lang_i in source.languages" :key="lang_i"
                :rules="[
                  { required: true, message: '{{ __('common.error_input_required') }}', trigger: 'blur' },
                ]"
              >
                <el-input size="mini" v-model="dialogVariables.form.name[lang.code]" placeholder="请填写名称"><template slot="prepend">@{{lang.name}}</template></el-input>
              </el-form-item>
            </el-form-item>

            <el-form-item>
              <el-button type="primary" @click="dialogVariablesFormSubmit('form')">{{ __('common.save') }}</el-button>
              <el-button @click="closedialogVariablesFormDialog('form')">{{ __('common.cancel') }}</el-button>
            </el-form-item>
          </el-form>
        </el-dialog>
      </form>
    </div>
  </div>
@endsection

@push('footer')
  <script>
    var app = new Vue({
      el: '#app',
      data: {
        current_language_code: '{{ locale() }}',
        isMove: false,
        form: {
          images: @json($product->images ?? []),
          model: @json($product->skus[0]['model'] ?? ''),
          price: @json($product->skus[0]['price'] ?? ''),
          quantity: @json($product->skus[0]['quantity'] ?? ''),
          sku: @json($product->skus[0]['sku'] ?? ''),
          status: @json($product->skus[0]['status'] ?? false),
          variables: @json($product->variables ?? []),
          skus: @json($product->skus ?? []),
        },
        source: {
          variables: @json($product->variables ?? []),
          languages: @json($languages ?? []),
        },

        editing: {
          isVariable: @json(($product->variables ?? null) != null),
        },

        dialogVariables: {
          show: false,
          variantIndex: null,
          variantValueIndex: null,
          form: {
            name: {}
          },
        },

        rules: {}
      },
      computed: {
        // variant value 重复次数
        variantValueRepetitions() {
          var repeats = [];
          var repeat = 1;
          for (var index = this.form.variables.length - 2; index >= 0; index--) {
            repeat *= this.form.variables[index + 1].values.length;
            repeats[index] = repeat;
          }
          // 最后一组只重复1次
          repeats.push(1);
          return repeats;
        },
      },
      watch: {
        'source.variables': {
          deep: true,
          handler: function(val) {
            // 原始规格数据变动，过滤有效规格并同步至 form.variables
            let variants = [];
            const sourceVariants = JSON.parse(JSON.stringify(this.source.variables));
            for (var i = 0; i < sourceVariants.length; i++) {
              const sourceVariant = sourceVariants[i];
              // 排除掉没有规格值的
              if (sourceVariant.values.length > 0) {
                variants.push(sourceVariant);
              }
            }

            this.form.variables = variants;

            if (this.isMove) return;
            this.remakeSkus();
          }
        }
      },
      methods: {
        variantIsImage(e, index) {
          if (!e) {
            this.source.variables[index].values.forEach(v => {
              v.image = '';
            })
          }
        },

        addProductImages(skuIndex) {
          bk.fileManagerIframe(images => {
            if (!isNaN(skuIndex)) {
              if (this.form.skus[skuIndex].images === null) {
                this.form.skus[skuIndex].images = images.map(e => e.path)
              } else {
                this.form.skus[skuIndex].images.push(...images.map(e => e.path))
              }
              return;
            }
            this.form.images.push(...images.map(e => e.path))
          })
        },

        removeImages(index) {
          this.form.images.splice(index, 1)
        },

        removeSkuImages(variantIndex, index) {
          this.form.skus[variantIndex].images.splice(index, 1)
        },

        dialogVariablesFormSubmit(form) {
          const name = JSON.parse(JSON.stringify(this.dialogVariables.form.name));
          const variantIndex = this.dialogVariables.variantIndex;
          const variantValueIndex = this.dialogVariables.variantValueIndex;

          this.$refs[form].validate((valid) => {
            if (!valid) {
              this.$message.error('请检查表单是否填写正确');
              return;
            }

            if (variantValueIndex !== null) {
              if (variantValueIndex == -1) { // 创建
                this.source.variables[variantIndex].values.push({name, image: ''});
              } else {
                this.source.variables[variantIndex].values[variantValueIndex].name = name;
              }
            } else {
              if (variantIndex == -1) { // 创建
                this.source.variables.push({name, values: [], isImage: false});
              } else {
                this.source.variables[variantIndex].name = name;
              }
            }

            this.dialogVariables.show = false;
          });
        },

        swapSourceVariantValue(e, variantIndex) {
          // 将 sku.variants[variantIndex] == e.oldIndex 的 sku[0] 与 sku.variants[variantIndex] == e.newIndex 的 sku[1] 交换顺序
          this.form.skus.forEach(function(sku) {
            const oldIndex = sku.variants[variantIndex];
            const newIndex = sku.variants[variantIndex] == e.oldIndex ? e.newIndex.toString() : e.oldIndex.toString()
            sku.variants[variantIndex] = newIndex;
          });

          this.remakeSkus()
        },

        closedialogVariablesFormDialog(form) {
          this.dialogVariables.show = false;
          this.dialogVariables.variantIndex = null;
          this.dialogVariables.variantValueIndex = null;
          this.dialogVariables.form.name = {};
          this.$refs[form].clearValidate();
        },

        removeSourceVariantValue(variantIndex, variantValueIndex) {
          this.source.variables[variantIndex].values.splice(variantValueIndex, 1);
          // this.form.variants = this.validSourceVariants;


        },

        modalVariantOpenButtonClicked(variantIndex, variantValueIndex) {
          this.dialogVariables.variantIndex = variantIndex;
          this.dialogVariables.variantValueIndex = variantValueIndex;

          let name = null;
          if (variantIndex === -1 || variantValueIndex === -1) {
            name = {};
          } else {
            if (variantValueIndex !== null) {
              // 编辑 variant value
              name = this.source.variables[variantIndex].values[variantValueIndex].name;
            } else {
              // 编辑 variant
              name = this.source.variables[variantIndex].name;
            }
          }

          this.dialogVariables.form.name = JSON.parse(JSON.stringify(name));
          this.dialogVariables.show = true;
        },

        removeSourceVariant(variantIndex) {
          this.source.variables.splice(variantIndex, 1);
          if (!this.source.variables.length) {
            setTimeout(() => { // 等 remakeSkus 完成 this.form.skus = [];
              this.form.skus = [{product_sku_id: 0,position: 1,variants: [],image: '',model: '',sku: '',price: null,quantity: null,is_default: 1}];
              this.editing.isVariable = false;
            }, 0);
          }
        },

        addVariantValue(variantIndex) {
          this.dialogVariables.show = true;
          this.dialogVariables.type = 'variant-value';
          this.dialogVariables.variantIndex = variantIndex;
        },

        remakeSkus() {
          const combos = makeVariableIndexes();

          if (combos.length < 1) {
            this.form.skus = [];
            return;
          }

          // 找出已存在的组合
          const productVariantCombos = this.form.skus.map(v => v.variants.join()); // ['0,0,0', '0,0,1']
          let skus = [];
          for (var i = 0; i < combos.length; i++) {
            const combo = combos[i]; // 0,0,0
            const index = productVariantCombos.indexOf(combo.join());
            if (index > -1) {
              skus.push(this.form.skus[index]);
            } else {
              skus.push({
                product_sku_id: 0,
                position: i,
                variants: combo,
                images: [],
                model: '',
                sku: '',
                price: null,
                quantity: null,
                is_default: i == 0,
              });
            }
          }

          // 第一个子商品用主商品的值
          if (!this.isMove) {
            skus[0].model = this.form.model;
            skus[0].sku = this.form.sku;
            skus[0].price = this.form.price;
            skus[0].quantity = this.form.quantity;
            skus[0].status = this.form.status;
          }

          this.form.skus = skus;
        },
      }
    });

    function makeVariableIndexes() {
      // 每组值重复次数
      var repeats = app.variantValueRepetitions;
      var results = [];

      if (app.form.variables.length < 1) {
        return results;
      }

      for (let i = 0; i < repeats[0] * app.form.variables[0].values.length; i++) {
        results.push([]);
      }
      for (let xIndex = 0; xIndex < repeats.length; xIndex++) { // 0 - 3
        let repeat = 0;
        let itemIndex = 0;
        for (let yIndex = 0; yIndex < results.length; yIndex++) { // 0 - 36
          results[yIndex].push(itemIndex);
          repeat++;
          if (repeat >= repeats[xIndex]) {
            repeat = 0;
            itemIndex++;
            if (itemIndex >= app.form.variables[xIndex].values.length) {
              itemIndex = 0;
            }
          }
        }
      }
      return results;
    }

    $(document).ready(function ($) {
      $('#brand-autocomplete').autocomplete({
        'source': function(request, response) {
          $http.get(`brands/autocomplete?name=${encodeURIComponent(request)}`, null, {hload: true}).then((res) => {
            response($.map(res.data, function(item) {
              return {label: item['name'], value: item['id']}
            }));
          })
        },
        'select': function(item) {
          $(this).val(item['label']);
          $('input[name="brand_id"]').val(item['value']);
        }
      });
    });
  </script>
@endpush

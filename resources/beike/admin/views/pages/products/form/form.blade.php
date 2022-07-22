@extends('admin::layouts.master')

@section('body-class', 'page-product-form')

@push('header')
  <script src="{{ asset('vendor/vue/Sortable.min.js') }}"></script>
  <script src="{{ asset('vendor/vue/vuedraggable.js') }}"></script>
@endpush

@section('content')
  <div class="card">
    {{-- <div class="card-header"><h6 class="card-title">基础信息</h6></div> --}}
    <div class="card-body">
      <h5 class="border-bottom pb-3 mb-4">基础信息</h5>
      <form action="{{ $product->id ? admin_route('products.update', $product) : admin_route('products.store') }}"
        method="POST" id="app">
        @csrf
        @method($product->id ? 'PUT' : 'POST')
        <input type="hidden" name="_redirect" value="{{ $_redirect }}" />

        @foreach (locales() as $index => $locale)
          {{-- <input type="hidden" name="descriptions[{{ $index }}][locale]" value="{{ $locale['code'] }}"> --}}
        @endforeach

        <x-admin-form-input-locale name="descriptions.*.name" title="名称" :value="$descriptions" required />
        <x-admin-form-input name="image" title="主图" :value="old('image', $product->image ?? '')" />
        <x-admin-form-input name="video" title="视频" :value="old('video', $product->video ?? '')" />
        <x-admin-form-input name="position" title="排序" :value="old('position', $product->position ?? '')" />
        <x-admin-form-switch name="active" title="状态" :value="old('active', $product->active ?? 1)" />

        <x-admin::form.row title="分类">
          @foreach ($source['categories'] as $_category)
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="categories[]" value="{{ $_category->id }}"
                id="category-{{ $_category->id }}" {{ in_array($_category->id, $category_ids) ? 'checked' : '' }}>
              <label class="form-check-label" for="category-{{ $_category->id }}">
                {{ $_category->name }}
              </label>
            </div>
          @endforeach
        </x-admin::form.row>

        <div>
          <h5 class="border-bottom pb-3 mb-4">商品库存</h5>

          <div class="form-group">
            <div class="row align-items-center">
              <label for="" class="col-sm-2 col-form-label">启用多规格</label>
              <div class="col-sm-10">
                <el-switch v-model="editing.isVariable"></el-switch>
              </div>
            </div>
          </div>

          <input v-if="form.skus.length" type="hidden" name="variables" :value="JSON.stringify(form.variables)">

          <div class="form-group" v-if="editing.isVariable">
            <div class="row align-items-center">
              <label for="" class="col-sm-2 col-form-label"></label>
              <div class="col-sm-10">
                <div class="selectable-variants">
                  <div>
                    <div v-for="(variant, variantIndex) in source.variables" :id="'selectable-variant-' + variantIndex">
                      <div class="title">
                        <div>
                          <b>@{{ variant.name[current_language_code] }}</b>
                          <el-link type="primary" @click="modalVariantOpenButtonClicked(variantIndex, null)">编辑</el-link>
                          <el-link type="danger" class="ms-2" @click="removeSourceVariant(variantIndex)">移除</el-link>
                        </div>
                        <div>
                          <el-checkbox v-model="variant.isImage" border size="mini" class="me-2 bg-white">添加规格图片</el-checkbox>
                          <el-button type="primary" plain size="mini" @click="modalVariantOpenButtonClicked(variantIndex, -1)">Add value</el-button>
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
                        <div class="p-2" @click="modalVariantOpenButtonClicked(variantIndex, -1)">请添加 Value</div>
                      </div>
                    </div>

                    <el-button type="primary" size="small" @click="modalVariantOpenButtonClicked(-1, null)" class="btn btn-xs mr-1 mb-1">Add variant</el-button>
                  </div>

                  <div v-if="form.skus.length" class="mt-3">
                    <table class="table table-bordered table-hover">
                      <thead>
                        <th v-for="(variant, index) in form.variables" :key="'pv-header-' + index">
                          @{{ variant.name[current_language_code] || 'No name' }}
                        </th>
                        <th>image</th>
                        <th>model</th>
                        <th>sku</th>
                        <th>price</th>
                        <th>orgin price</th>
                        <th>cost price</th>
                        <th>quantity</th>
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
                            <div class="open-file-manager variants-producr-img">
                              <div>
                                <img :src="thumbnail(sku.image)" class="img-fluid">
                              </div>
                            </div>
                            <input type="hidden" class="form-control" v-model="sku.image" :name="'skus[' + skuIndex + '][image]'"
                              placeholder="image">

                            <input type="hidden" class="form-control" :name="'skus[' + skuIndex + '][is_default]'" :value="skuIndex == 0 ? 1 : 0">
                            <input v-for="(variantValueIndex, j) in sku.variants" type="hidden"
                              :name="'skus[' + skuIndex + '][variants][' + j + ']'" :value="variantValueIndex">
                          </td>
                          <td><input type="text" class="form-control" v-model="sku.model" :name="'skus[' + skuIndex + '][model]'"
                              placeholder="model"></td>
                          <td><input type="text" class="form-control" v-model="sku.sku" :name="'skus[' + skuIndex + '][sku]'" placeholder="sku">
                          </td>
                          <td><input type="text" class="form-control" v-model="sku.price" :name="'skus[' + skuIndex + '][price]'"
                              placeholder="price"></td>
                          <td><input type="text" class="form-control" v-model="sku.origin_price" :name="'skus[' + skuIndex + '][origin_price]'"
                              placeholder="origin_price"></td>
                          <td><input type="text" class="form-control" v-model="sku.cost_price" :name="'skus[' + skuIndex + '][cost_price]'"
                              placeholder="cost_price">
                          </td>
                          <td><input type="text" class="form-control" v-model="sku.quantity" :name="'skus[' + skuIndex + '][quantity]'"
                              placeholder="quantity"></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <template v-if="!editing.isVariable">
            <div class="form-group">
              <div class="row align-items-center">
                <label for="" class="col-sm-2 col-form-label">图片</label>
                <div class="col-sm-10">

                  <div class="open-file-manager set-product-img">
                    <div>
                      <img :src="thumbnail(form.skus[0].image)" class="img-fluid">
                    </div>
                  </div>
                  <input type="hidden" v-model="form.skus[0].image" name="skus[0][image]">
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="row align-items-center">
                <label for="" class="col-sm-2 col-form-label">model</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control form-control-sm short" name="skus[0][model]" placeholder="model"
                    value="{{ old('skus.0.model', $product->skus[0]->model ?? '') }}">
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="row align-items-center">
                <label for="" class="col-sm-2 col-form-label">sku</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control form-control-sm short" name="skus[0][sku]" placeholder="sku"
                    value="{{ old('skus.0.sku', $product->skus[0]->sku ?? '') }}">
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="row align-items-center">
                <label for="" class="col-sm-2 col-form-label">price</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control form-control-sm short" name="skus[0][price]" placeholder="price"
                    value="{{ old('skus.0.price', $product->skus[0]->price ?? '') }}">
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row align-items-center">
                <label for="" class="col-sm-2 col-form-label">origin_price</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control form-control-sm short" name="skus[0][origin_price]" placeholder="origin_price"
                      value="{{ old('skus.0.origin_price', $product->skus[0]->origin_price ?? '') }}">
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row align-items-center">
                <label for="" class="col-sm-2 col-form-label">cost_price</label>
                <div class="col-sm-10">
                  <input type="text" name="skus[0][cost_price]" class="form-control form-control-sm short" placeholder="cost_price"
                    value="{{ old('skus.0.cost_price', $product->skus[0]->cost_price ?? '') }}">
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="row align-items-center">
                <label for="" class="col-sm-2 col-form-label">quantity</label>
                <div class="col-sm-10">
                   <input type="text" class="form-control form-control-sm short" name="skus[0][quantity]" placeholder="quantity"
                      value="{{ old('skus.0.quantity', $product->skus[0]->quantity ?? '') }}">
                </div>
              </div>
            </div>
            <input type="hidden" name="skus[0][variants]" placeholder="variants" value="">
            <input type="hidden" name="skus[0][position]" placeholder="position" value="0">
            <input type="hidden" name="skus[0][is_default]" placeholder="is_default" value="1">
          </template>
        </div>

        <div class="form-group">
          <div class="row">
            <div class="col-sm-2"></div>
            <div class="col-sm-10"><button type="submit" class="btn btn-primary">Save</button></div>
          </div>
        </div>

        <el-dialog
          title="编辑"
          :visible.sync="dialogVariables.show"
          width="400"
          @close="closedialogVariablesFormDialog('form')"
          :close-on-click-modal="false"
          >
          <el-form ref="form" :rules="rules" :model="dialogVariables.form" label-width="100px">
            <el-form-item label="名称" required class="language-inputs">
              <el-form-item  :prop="'name.' + lang.code" :inline-message="true"  v-for="lang, lang_i in source.languages" :key="lang_i"
                :rules="[
                  { required: true, message: '输入框不能为空', trigger: 'blur' },
                ]"
              >
                <el-input size="mini" v-model="dialogVariables.form.name[lang.code]" placeholder="请填写名称"><template slot="prepend">@{{lang.name}}</template></el-input>
              </el-form-item>
            </el-form-item>

            <el-form-item>
              <el-button type="primary" @click="dialogVariablesFormSubmit('form')">保存</el-button>
              <el-button @click="closedialogVariablesFormDialog('form')">取消</el-button>
            </el-form-item>
          </el-form>
        </el-dialog>
      </form>
    </div>
  </div>
@endsection


@push('footer')
  <script>
    Vue.prototype.thumbnail = function thumbnail(image, width, height) {
      // 判断 image 是否以 http 开头
      if (image.indexOf('http') === 0) {
        return image;
      }

      return '{{ asset('catalog') }}' + image;
    };

    var app = new Vue({
      el: '#app',
      data: {
        current_language_code: '{{ current_language_code() }}',
        isMove: false,
        form: {
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
        // addVariant() {
        //   this.source.variables.push({
        //     name: '',
        //     values: []
        //   });
        // },

        // datadragEnd(e) {
        //   console.log(e);
        // },

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

        removeSourceVariant() {

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
                image: '',
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
  </script>
@endpush

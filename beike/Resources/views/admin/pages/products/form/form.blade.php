@extends('beike::admin.layouts.master')

@push('header')
  <script src="https://cdn.bootcdn.net/ajax/libs/vue/2.6.14/vue.js"></script>
@endpush

@section('content')
  <div class="card">
    <div class="card-body">
      <h2>product</h2>
      <form action="{{ $product->id ? route('admin.products.update', $product) : route('admin.products.store') }}" method="POST" id="app">
        @csrf
        @method($product->id ? 'PUT' : 'POST')
        <input type="hidden" name="_redirect" value="{{ $_redirect }}" />

        @foreach (locales() as $index => $locale)
          <input type="hidden" name="descriptions[{{ $index }}][locale]" value="{{ $locale['code'] }}">
        @endforeach

        <x-beike-form-input-locale name="descriptions.*.name" title="名称" :value="$descriptions" required />
        <x-beike-form-input name="image" title="主图" :value="old('image', $product->image ?? '')" />
        <x-beike-form-input name="video" title="视频" :value="old('video', $product->video ?? '')" />
        <x-beike-form-input name="position" title="排序" :value="old('position', $product->position ?? '')" />
        <x-beike-form-switch name="active" title="状态" :value="old('active', $product->active ?? 1)" />

        <div>
          <h2>skus</h2>
          <input type="radio" v-model="editing.isVariable" :value="false"> 单规格
          <input type="radio" v-model="editing.isVariable" :value="true"> 多规格
          <div v-if="editing.isVariable">
            <div>
              <div v-for="(variant, variantIndex) in source.variables">
                <div>
                  <input type="text" v-model="variant.name" placeholder="variant name">

                  <div v-for="(value, valueIndex) in variant.values">
                    <input v-model="variant.values[valueIndex].name" type="text" placeholder="variant value name">
                  </div>
                  <button type="button" @click="addVariantValue(variantIndex)">Add value</button>
                </div>
              </div>

              <button type="button" @click="addVariant">Add variant</button>
            </div>

            <div v-if="form.skus.length">
              <input v-if="form.skus.length" type="hidden" name="variables" :value="JSON.stringify(form.variables)">
              <table>
                <thead>
                  <th v-for="(variant, index) in form.variables" :key="'pv-header-'+index">
                    @{{ variant.name || 'No name' }}
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
                  <tr v-for="(sku, skuIndex) in form.skus">
                    <template v-for="(variantValueIndex, j) in sku.variants">
                      <td v-if="skuIndex % variantValueRepetitions[j] == 0" :key="'pvv'+skuIndex+'-'+j" :rowspan="variantValueRepetitions[j]">
                        <span>@{{ form.variables[j].values[variantValueIndex].name || 'No name' }}</span>
                      </td>
                      </template>
                    <td>
                      <input type="text" v-model="sku.image" :name="'skus[' + skuIndex + '][image]'" placeholder="image">
                      <input type="hidden" :name="'skus[' + skuIndex + '][is_default]'" :value="skuIndex == 0 ? 1 : 0">
                      <input v-for="(variantValueIndex, j) in sku.variants" type="hidden" :name="'skus[' + skuIndex + '][variants][' + j + ']'" :value="variantValueIndex">
                    </td>
                    <td><input type="text" v-model="sku.model" :name="'skus[' + skuIndex + '][model]'" placeholder="model"></td>
                    <td><input type="text" v-model="sku.sku" :name="'skus[' + skuIndex + '][sku]'" placeholder="sku"></td>
                    <td><input type="text" v-model="sku.price" :name="'skus[' + skuIndex + '][price]'" placeholder="price"></td>
                    <td><input type="text" v-model="sku.origin_price" :name="'skus[' + skuIndex + '][origin_price]'" placeholder="origin_price"></td>
                    <td><input type="text" v-model="sku.cost_price" :name="'skus[' + skuIndex + '][cost_price]'" placeholder="cost_price"></td>
                    <td><input type="text" v-model="sku.quantity" :name="'skus[' + skuIndex + '][quantity]'" placeholder="quantity"></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <div v-if="!editing.isVariable">
            <div>
              <input type="text" name="skus[0][image]" placeholder="image" value="{{ old('skus.0.image', $product->skus[0]->image ?? '') }}">
              <input type="text" name="skus[0][model]" placeholder="model" value="{{ old('skus.0.model', $product->skus[0]->model ?? '') }}">
              <input type="text" name="skus[0][sku]" placeholder="sku" value="{{ old('skus.0.sku', $product->skus[0]->sku ?? '') }}">
              <input type="text" name="skus[0][price]" placeholder="price" value="{{ old('skus.0.price', $product->skus[0]->price ?? '') }}">
              <input type="text" name="skus[0][origin_price]" placeholder="origin_price" value="{{ old('skus.0.origin_price', $product->skus[0]->origin_price ?? '') }}">
              <input type="text" name="skus[0][cost_price]" placeholder="cost_price" value="{{ old('skus.0.cost_price', $product->skus[0]->cost_price ?? '') }}">
              <input type="text" name="skus[0][quantity]" placeholder="quantity" value="{{ old('skus.0.quantity', $product->skus[0]->quantity ?? '') }}">
              <input type="hidden" name="skus[0][variants]" placeholder="variants" value="">
              <input type="hidden" name="skus[0][position]" placeholder="position" value="0">
              <input type="hidden" name="skus[0][is_default]" placeholder="is_default" value="1">
            </div>
          </div>
        </div>

        <button type="submit" class="btn btn-primary">Save</button>
      </form>
    </div>
  </div>
@endsection

@push('footer')
  <script>
    var app = new Vue({
      el: '#app',
      data: {
        form: {
          variables: @json($product->variables_decoded ?? []),
          skus: @json($product->skus ?? []),
        },
        source: {
          variables: @json($product->variables_decoded ?? []),
        },
        editing: {
          isVariable: @json(($product->variables ?? null) != null),
        }
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
          handler: function () {
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
            this.remakeSkus();
          }
        }
      },
      methods: {
        addVariant() {
          this.source.variables.push({name: '', values: []});
        },

        addVariantValue(variantIndex) {
          this.source.variables[variantIndex].values.push({name: '', image: ''});
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
          skus[0].model = this.form.model;
          skus[0].sku = this.form.sku;
          skus[0].price = this.form.price;
          skus[0].quantity = this.form.quantity;
          skus[0].status = this.form.status;

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

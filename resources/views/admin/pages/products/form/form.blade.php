@extends('admin.layouts.master')

@push('header')
    <script src="https://cdn.bootcdn.net/ajax/libs/vue/2.6.14/vue.js"></script>
@endpush

@section('content')
    <h2>product</h2>
    <form action="{{ route('admin.products.store') }}" method="POST" id="app">
        @csrf

        <input type="text" name="name" placeholder="Name" value="{{ old('name', $product->name ?? '') }}">
        <input type="text" name="image" placeholder="image" value="{{ old('image', $product->image ?? '') }}">
        <input type="text" name="video" placeholder="video" value="{{ old('video', $product->video ?? '') }}">
        <input type="text" name="sort_order" placeholder="sort_order" value="{{ old('sort_order', $product->sort_order ?? 0) }}">
        <input type="text" name="status" placeholder="status" value="{{ old('status', $product->status ?? 1) }}">
        
        <div>
            <h2>skus</h2>
            <input type="radio" v-model="editing.isVariable" :value="false"> 单规格
            <input type="radio" v-model="editing.isVariable" :value="true"> 多规格
            <div v-if="editing.isVariable">
                <div>
                    <div v-for="variant in form.variable">
                        <input type="text" v-model="variant.name" name="variant[0][name]" placeholder="variant name">
                        
                        <input v-for="(value, valueIndex) in variant.values" v-model="variant.values[valueIndex]" type="text" name="variant[0][values][0]" placeholder="variant value name">
                    </div>
                </div>

                <div>
                    <table>
                        <tr v-for="(sku, skuIndex) in form.skus">
                            <td><input type="text" v-model="sku.image" name="skus[0][image]" placeholder="image"></td>
                            <td><input type="text" v-model="sku.model" name="skus[0][model]" placeholder="model"></td>
                            <td><input type="text" v-model="sku.sku" name="skus[0][sku]" placeholder="sku"></td>
                            <td><input type="text" v-model="sku.price" name="skus[0][price]" placeholder="price" value="10"></td>
                            <td><input type="text" v-model="sku.quantity" name="skus[0][quantity]" placeholder="quantity" value="10"></td>
                            <td><input type="text" v-model="sku.is_default" name="skus[0][is_default]" placeholder="is_default" value="1"></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div v-if="!editing.isVariable">
                <div>
                    <input type="text" name="skus[0][image]" placeholder="image">
                    <input type="text" name="skus[0][model]" placeholder="model">
                    <input type="text" name="skus[0][sku]" placeholder="sku">
                    <input type="text" name="skus[0][price]" placeholder="price" value="10">
                    <input type="text" name="skus[0][quantity]" placeholder="quantity" value="10">
                    <input type="text" name="skus[0][is_default]" placeholder="is_default" value="1">
                </div>
            </div>
        </div>

        <button type="submit">Save</button>
    </form>
@endsection

@push('footer')
    <script>
        new Vue({
            el: '#app',
            data: {
                form: {
                    variable: @json($product->variable_decoded ?? []),
                    skus: @json($product->skus ?? []),
                },
                editing: {
                    isVariable: @json(($product->variable ?? null) != null),
                }
            }
        });
    </script>
@endpush
@extends('admin::layouts.master')

@section('title', '顾客管理')

@section('content')
    <div id="customer-app" class="card">
        <div class="card-header">
            编辑顾客
        </div>
        <div class="card-body">
            <form action="{{ admin_route($customer->id ? 'customers.update' : 'customers.store', $customer) }}"
                  method="POST">
                @csrf
                @method($customer->id ? 'PUT' : 'POST')
                <input type="hidden" name="_redirect" value="{{ $_redirect }}">


                <x-admin-form-switch title="状态" name="active" :value="old('active', $customer->active ?? 1)"/>

                <x-admin::form.row>
                  <button type="submit" class="btn btn-primary">保存</button>
                  <a href="{{ $_redirect }}" class="btn btn-outline-secondary">返回</a>
                </x-admin::form.row>
            </form>

        </div>
    </div>
@endsection

@push('footer')
@endpush

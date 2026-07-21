@extends('admin::layouts.master')

@section('title', __('order.express_company'))

@section('content-area-class', 'w-max-1200')

@section('page-title-back', admin_route('settings.index'))

@section('head-form-btns', true)

@section('content')
  @if (session('success'))
    <x-admin-alert type="success" msg="{{ session('success') }}" class="mt-4"/>
  @endif
  @if (session('error'))
    <div class="alert alert-danger">
      {!! session('error') !!}
    </div>
  @endif
  <div class="card h-min-600">
    <div class="card-body">
      @hook('admin.setting.express.content.before')
      <form action="{{ admin_route('settings.store') }}" class="needs-validation" novalidate method="POST" id="form-app">
        @csrf
        <input type="hidden" name="return_url" value="{{ url()->full() }}"/>

        @hook('admin.setting.express.before')
        <x-admin::form.row title="{{ __('order.express_company') }}">
          <table class="table table-bordered w-max-600">
            <thead>
              <th>{{ __('order.express_company') }}</th>
              <th>Code</th>
              @hook('admin.setting.express.table.thead.th')
              <th></th>
            </thead>
            <tbody>
              <tr v-for="item, index in express_company" :key="index">
                <td>
                  <input required placeholder="{{ __('order.express_company') }}" type="text" :name="'express_company['+ index +'][name]'" v-model="item.name" class="form-control">
                  <div class="invalid-feedback">{{ __('common.error_required', ['name' => __('order.express_company')]) }}</div>
                </td>
                <td>
                  <input required placeholder="{{ __('admin/setting.express_code_help') }}" type="text" :name="'express_company['+ index +'][code]'" v-model="item.code" class="form-control">
                  <div class="invalid-feedback">{{ __('common.error_required', ['name' => 'Code']) }}</div>
                </td>
                @hook('admin.setting.express.table.tbody.td')
                <td><i @click="express_company.splice(index, 1)" class="bi bi-x-circle fs-4 text-danger cursor-pointer"></i></td>
              </tr>
              <tr>
                <td colspan="2"><input v-if="!express_company.length" name="express_company" class="d-none"></td>
                <td><i class="bi bi-plus-circle cursor-pointer fs-4" @click="addCompany"></i></td>
              </tr>
            </tbody>
          </table>
        </x-admin::form.row>
        @hook('admin.setting.express.after')

        <x-admin::form.row title="">
          <button type="submit" class="btn btn-primary d-none mt-4">{{ __('common.submit') }}</button>
        </x-admin::form.row>
      </form>
      @hook('admin.setting.express.content.after')
    </div>
  </div>
@endsection

@push('footer')
  <script>
    let app = new Vue({
      el: '#form-app',
      data: {
        express_company: @json(old('express_company', system_setting('base.express_company', []))),
      },
      methods: {
        addCompany() {
          if (typeof this.express_company == 'string') {
            this.express_company = [];
          }

          this.express_company.push({name: '', code: ''})
        },
      }
    });
  </script>
@endpush
@extends('admin::layouts.master')

@section('title', __('admin/common.account_index'))

@section('content-area-class', 'w-max-1200')

@section('page-title-back', true)

@section('content')
<div id="plugins-app-form" class="card h-min-600">
  <div class="card-body">

    <form action="{{ admin_route('account.update') }}" id="form-app" class="needs-validation" novalidate method="post">
      @csrf
      @method('PUT')
      @if (session('success'))
        <x-admin-alert type="success" msg="{{ session('success') }}" class="mt-4"/>
      @endif
      @hook('admin.account.token.before')
      <x-admin::form.row title="">
        <div class="col-auto">
          <table class="table table-bordered" id="token-app">
            <thead>
              <th>Key</th>
              <th class="text-center wp-100">{{ __('common.action') }}</th>
            </thead>
            <tbody>
              <tr v-for="item, index in tokens" :key="index">
                <td>
                  <textarea class="form-control bg-light" :name="'tokens['+ index +']'" readonly>@{{ item }}</textarea>
                </td>
                <td class="text-center wp-100">
                  <button type="button" class="btn btn-outline-secondary btn-sm" @click="tokens.splice(index, 1)">{{ __('common.delete') }}</button>
                </td>
              </tr>
              <tr>
                <td colspan="1"><input v-if="!tokens.length" name="tokens" value="" class="d-none"></td>
                <td class="text-center">
                  <button type="button" class="btn btn-outline-info btn-sm" @click="addToken">{{ __('common.add') }}</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </x-admin::form.row>
      @hook('admin.account.token.after')
    </form>
  </div>
</div>
@endsection

@push('footer')
<script>
  @hook('admin.account.script.before')
  const tokenApp = new Vue({
    el: '#token-app',
    data: {
      tokens: @json(old('tokens', $current_user->tokens->pluck('token')->toArray() ?? [])),
      @hook('admin.account.vue.data')
    },
    methods: {
      addToken() {
        this.tokens.push(bk.randomString(64));
        setTimeout(() => {
          update(false);
        }, 0);
      },
      @hook('admin.account.vue.methods')
    }
  });

  function update(isRefresh = true) {
    $http.post($('#form-app').attr('action'), $('#form-app').serialize()).then(res => {
      layer.msg(res.message);
      if (isRefresh) {
        location.reload();
      }
    })
  }
  @hook('admin.account.script.after')
</script>
@endpush

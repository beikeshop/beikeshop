@extends('admin::layouts.master')

@section('title', __('admin/common.account_index'))

@section('content')
<div id="plugins-app-form" class="card h-min-600">
  <div class="card-body">
    <form action="{{ admin_route('account.update') }}" id="form-account" class="needs-validation" novalidate method="post">
      @csrf
      @method('PUT')
      @if (session('success'))
        <x-admin-alert type="success" msg="{{ session('success') }}" class="mt-4"/>
      @endif

      <ul class="nav nav-tabs nav-bordered mb-3  mb-lg-5" role="tablist">
        <li class="nav-item" role="presentation">
          <a class="nav-link active" data-bs-toggle="tab" href="#tab-general">{{ __('admin/setting.basic_settings') }}</a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link" data-bs-toggle="tab" href="#tab-token">{{ __('admin/account.create_token') }}</a>
        </li>
        @hook('admin.account.nav.after')
      </ul>

      <div class="tab-content">
        <div class="tab-pane fade show active" id="tab-general">
          <x-admin-form-input name="name" title="{{ __('common.name') }}" value="{{ old('name', $current_user->name) }}" />
          <x-admin-form-input name="email" title="{{ __('common.email') }}" type="email" value="{{ old('email', $current_user->email) }}" />
          <x-admin-form-input name="password" title="{{ __('shop/login.password') }}" value="{{ old('password', '') }}">
            <div class="help-text font-size-12 lh-base">{{ __('admin/account.password_text') }}</div>
          </x-admin-form-input>
          <x-admin-form-select title="{{ __('common.language') }}" name="locale" :value="old('locale', $current_user->locale)" :options="$admin_languages" key="code" label="name" />
        </div>
        <div class="tab-pane fade show" id="tab-token">
          <x-admin::form.row :title="__('admin/account.create_token')">
            <div class="col-auto wp-200-">
              <table class="table table-bordered w-max-500" id="token-app">
                <thead>
                  <th>Token</th>
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
        </div>
      </div>
      <x-admin::form.row title="">
        <div class="mt-4">
          <button type="button" class="btn btn-lg w-min-100 btn-primary btn-submit" onclick="update()" form="form-account">{{ __('common.save') }}</button>
          <button class="btn btn-lg btn-default w-min-100 ms-3" onclick="bk.back()">{{ __('common.return') }}</button>
        </div>
      </x-admin::form.row>
    </form>
  </div>
</div>
@endsection

@push('footer')
<script>
  const tokenApp = new Vue({
    el: '#token-app',
    data: {
      tokens: @json(old('tokens', $current_user->tokens->pluck('token')->toArray() ?? [])),
    },
    methods: {
      addToken() {
        this.tokens.push(bk.randomString(64));
        setTimeout(() => {
          update(false);
        }, 0);
      }
    }
  });

  function update(isRefresh = true) {
    $http.post($('#form-account').attr('action'), $('#form-account').serialize()).then(res => {
      layer.msg(res.message);
      if (isRefresh) {
        location.reload();
      }
    })
  }
</script>
@endpush

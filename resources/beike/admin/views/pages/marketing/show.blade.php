@extends('admin::layouts.master')

@section('title', __('admin/marketing.marketing_show'))

@section('body-class', 'page-marketing-info')

@section('content')
  <div class="marketing-iframe-wrap">
    @include('admin::shared.loading-am')
    <iframe id="marketing-iframe" src="{{ beike_url() }}/plugin/{{ $plugin_code }}?iframe=1&domain={{ request()->getSchemeAndHttpHost() }}&token={{ system_setting('base.developer_token') }}&system_version={{ config('beike.version') }}&locale={{ admin_locale() == 'zh_cn' ? 'zh_cn' : 'en' }}&feature=iframe_marketing&return_url={{ admin_route('marketing.show', $plugin_code) }}" class="w-100 marketing-iframe"></iframe>
  </div>
@endsection

@push('footer')
<script>
  const marketingIframe = document.getElementById('marketing-iframe');
  const developerToken = @json(system_setting('base.developer_token'));

  $('#marketing-iframe').on('load', function() {
    $('.loading-am').hide();
  });

  window.addEventListener('message', function (event) {
    if (event.origin != '{{ beike_url() }}') return;

    // token 逻辑，如果官网那边传回来了 token，说明该用户在登录插件市场 这时候需要更新 token
    if (event.data.type == 'set_token' && event.data.data.token != developerToken) {
      $http.post('{{ admin_route('settings.store_token') }}', {developer_token: event.data.data.token}).then((res) => {
        // 如果有 login_plugin 说明是登录插件市场，需要刷新页面
        if (event.data.data.login_plugin) {
          layer.load(2, {shade: [0.3,'#fff'] })
          window.location.reload();
        }
      })
    }

    if (event.data.type == 'marketing_buy') {
      const params = { payment_code: event.data.data.payment_code, return_url: '{{ admin_route('marketing.show', ['code' => $plugin_code]) }}'};
      $http.post('{{ admin_route('marketing.buy', ['code' => $plugin_code]) }}', params).then((res) => {
        marketingIframe.contentWindow.postMessage({ type: 'marketing_buy_callback', data: res }, '{{ beike_url() }}');
      })
    }

    if (event.data.type == 'marketing_buy_services') {
      const params = { payment_code: event.data.data.payment_code, return_url: '{{ admin_route('marketing.show', ['code' => $plugin_code]) }}'};
      $http.post(`marketing/${event.data.data.id}/buy_service`, params).then((res) => {
        marketingIframe.contentWindow.postMessage({ type: 'marketing_buy_services_callback', data: res }, '{{ beike_url() }}');
      })
    }

    if (event.data.type == 'check_domain') {
      $http.post('{{ admin_route('marketing.check_domain') }}', {token: event.data.data.token, location_host: config.app_url}, {hload: true}).then((res) => {
        if (res.status == 'success') {
          if (res.message == 'fail') {
            layer.alert(res.data, {icon: 2, area: ['400px'], btn: ['{{ __('common.confirm') }}'], title: '{{__("common.text_hint")}}'});
          }
        }
      })
    }

    // 下载插件 逻辑
    if (event.data.type == 'download_plugin') {
      $http.post(`marketing/${event.data.data.code}/download`, null, {hmsg:true}).then((res) => {
        marketingIframe.contentWindow.postMessage({ type: 'download_plugin_done', data: {message: res.message} }, '{{ beike_url() }}');
      }).catch((err) => {
        if (err.response.data.message == 'plugin_pending') {
          layer.alert('{{__('admin/marketing.pluginstatus_pending')}}', {btn: ['{{ __('common.confirm') }}'], title: '{{__("common.text_hint")}}'});
        } else if (err.response.data.message == 'Not a zip archive') {
          layer.alert('{{ __('admin/marketing.not_zip_archive') }}', {icon: 2, area: ['400px'], btn: ['{{ __('common.confirm') }}'], title: '{{__("common.text_hint")}}'});
        } else {
          layer.msg(err.response.data.message || res.message,{time: 3000}, ()=>{});
        }
      })
    }

    // 去我的插件列表
    if (event.data.type == 'to_plugins_index') {
      location.href = '{{ admin_route('plugins.index') }}';
    }

    if (event.data.type == 'to_marketing_index') {
      location.href = '{{ admin_route('marketing.index') }}';
    }

    if (event.data.type == 'to_plugins_type') {
      location.href = '{{ admin_route('marketing.index') }}?type=' + event.data.data.type;
    }

    if (event.data.type == 'search_plugins') {
      location.href = '{{ admin_route('marketing.index') }}?keyword=' + event.data.data.keyword;
    }
  }, false);
</script>
@endpush

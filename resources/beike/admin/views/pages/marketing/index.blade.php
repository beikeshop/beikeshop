@extends('admin::layouts.master')

@section('title', __('admin/marketing.marketing_list'))

@section('body-class', 'page-marketing')

@section('content')
  <div class="marketing-iframe-wrap">
    @include('admin::shared.loading-am')
    <iframe id="marketing-iframe" src="{{ beike_url() }}/plugin?iframe=1&domain={{ request()->getSchemeAndHttpHost() }}&token={{ system_setting('base.developer_token') }}&system_version={{ config('beike.version') }}&locale={{ admin_locale() == 'zh_cn' ? 'zh_cn' : 'en' }}&feature=iframe_marketing&keyword={{ request('keyword') }}&type={{ request('type') }}" class="w-100 marketing-iframe"></iframe>
  </div>
  <div id="app" v-cloak>
    <v-set-token ref="v-set-token" />
  </div>
  @include('admin::pages.marketing.set-token-dialog')
@endsection

@push('footer')
<script>
  const marketingIframe = document.getElementById('marketing-iframe');

  $('#marketing-iframe').on('load', function() {
    $('.loading-am').hide();
  });

  let app = new Vue({
    el: '#app',
    data: {},
    created() {
      const self = this;

      window.addEventListener('message', function (event) {
        if (event.origin != '{{ beike_url() }}') return;

        if (event.data.type == 'plugin_show' && event.data.data.code) {
          location.href = '{{ admin_route('marketing.index') }}/' + event.data.data.code;
        }

        if (event.data.type == 'set_token') {
          self.$refs['v-set-token'].setToken();
        }

        if (event.data.type == 'marketing_load_data_url') {
          const params = new URLSearchParams(event.data.data);
          const type = params.get('type') || '';
          const keyword = params.get('keyword') || '';

          if (window.location.href.indexOf('type') !== -1) {
            window.history.pushState({}, 0, bk.updateQueryStringParameter(window.location.href, 'type', type));
          }

          if (window.location.href.indexOf('keyword') !== -1) {
            window.history.pushState({}, 0, bk.updateQueryStringParameter(window.location.href, 'keyword', keyword));
          }
        }
      }, false);
    },
  })
</script>
@endpush

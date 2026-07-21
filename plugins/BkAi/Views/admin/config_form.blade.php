<form class="needs-validation" novalidate action="{{ admin_route('plugins.update', [$plugin->code]) }}" method="POST">
  @csrf
  {{ method_field('put') }}
  <div class="fs-5">{{ __('BkAi::common.last_quota') }}：<span class="get-quota fs-4"></span> <a class="fs-6 ms-3" href="{{ beike_url() }}/subscribe/bk_ai?domain={{ request()->getHost() }}" target="_blank">{{ __('BkAi::common.set_quota') }} <i class="bi bi-arrow-up-right-square"></i></a></div>

  <div class="alert alert-info mt-3" role="alert">
    <div class="mb-3 d-flex">
      <strong>{{ __('BkAi::common.fee_rules') }}：</strong>
      <div>
        {{ __('BkAi::common.fee_rules_1') }}<br>
        {{ __('BkAi::common.fee_rules_2') }}<br>
        <div class="mt-1">{{ __('BkAi::common.fee_rules_3') }}</div>
      </div>
    </div>

    <div class="mb-3"><strong>{{ __('BkAi::common.product_descriptions') }}：</strong>{{ __('BkAi::common.product_descriptions_1') }}</div>
    <div><strong>{{ __('BkAi::common.image_generation') }}：</strong>{{ __('BkAi::common.image_generation_1') }}</div>
  </div>
</form>

@push('footer')
  <script>
    $(function () {
      $http.get('{{ admin_route('plugin.bk_ai.get_quota') }}', null, {hload: true}).then((res) => {
        let data = JSON.parse(res);
        $('.get-quota').text(data.data);
      })
    });
  </script>
@endpush
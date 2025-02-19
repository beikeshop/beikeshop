<button type="button" class="btn border fw-bold w-100 mb-3" class="provider-btn" onclick="bk.openWin('{{ shop_route('social.redirect', $provider['provider']) }}')">
    <img src="{{ plugin_resize('social' , '/image/' . $provider['provider'] . '.png') }}" alt="{{ $provider['provider'] }}" class="img-fluid wh-20 me-2">
    {{ __("Social::providers.".$provider['provider']) }}
</button>

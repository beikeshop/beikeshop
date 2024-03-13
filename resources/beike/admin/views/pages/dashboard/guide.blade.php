<div class="card mb-4 dashboard-guide-section">
  <div class="card-header d-flex justify-content-between align-items-start">
    <h5 class="card-title">{{ __('admin/guide.heading_title') }}</h5>
    <div class="cursor-pointer guide-close"><i class="bi bi-x-lg"></i></div>
  </div>
  <div class="card-body">
    <ul class="nav nav-tabs mb-3">
      <li class="nav-item">
        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#basic-tab" type="button">{{ __('admin/guide.tab_basic') }}</button>
      </li>
      <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#language-tab" type="button">{{ __('admin/guide.tab_language') }}</button>
      </li>
      <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#product-tab" type="button">{{ __('admin/guide.tab_product') }}</button>
      </li>
      <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#theme-tab" type="button">{{ __('admin/guide.tab_theme') }}</button>
      </li>
      <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#payment-tab" type="button">{{ __('admin/guide.tab_payment_shipping') }}</button>
      </li>
      <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#mail-tab" type="button">{{ __('admin/guide.tab_mail') }}</button>
      </li>
    </ul>
    <div class="tab-content">
      <div class="tab-pane fade show active" id="basic-tab">
        <div class="guide-info">
          <div class="left"><span><i class="bi bi-gear"></i></span></div>
          <div class="right">
            <p>{{ __('admin/guide.text_greeting') }}</p>
            <p>{{ __('admin/guide.text_greeting_1') }}</p>
            <p>{{ __('admin/guide.text_basic_1') }}</p>
            <ol class="mb-3">
              <li>
                <a href="{{ admin_route('settings.index', ['tab' => 'tab-general']) }}">
                  {{ __('admin/guide.button_setting_general') }}
                </a>
              </li>
              <li>
                <a href="{{ admin_route('settings.index', ['tab' => 'tab-store']) }}">
                  {{ __('admin/setting.store_settings') }}
                </a>
              </li>
              <li>
                <a href="{{ admin_route('settings.index', ['tab' => 'tab-image']) }}">
                  {{ __('admin/guide.button_setting_logo') }}
                </a>
              </li>
              <li>
                <a href="{{ admin_route('settings.index', ['tab' => 'tab-checkout']) }}">
                  {{ __('admin/setting.checkout_settings') }}
                </a>
              </li>
            </ol>
            <a href="{{ admin_route('settings.index') }}" class="btn btn-outline-primary">{{ __('admin/guide.button_setting') }}</a>
          </div>
        </div>
      </div>

      <div class="tab-pane fade" id="language-tab">
        <div class="guide-info">
          <div class="left"><span><i class="bi bi-translate"></i></span></div>
          <div class="right">
            <p>{{ __('admin/guide.text_language_1') }}</p>
            <p class="mb-3">{{ __('admin/guide.text_language_2') }}</p>
            <a href="{{ admin_route('languages.index') }}" class="btn btn-outline-primary">{{ __('admin/guide.button_language') }}</a>
            <a href="{{ admin_route('currencies.index') }}" class="btn btn-outline-primary">{{ __('admin/guide.button_currency') }}</a>
          </div>
        </div>
      </div>

      <div class="tab-pane fade" id="product-tab">
        <div class="guide-info">
          <div class="left"><span><i class="bi bi-bag-check"></i></span></div>
          <div class="right">
            <p>{!! __('admin/guide.text_product_1') !!}</p>
            <p class="mb-3">{!! __('admin/guide.text_product_2') !!}</p>
            <a href="{{ admin_route('products.index') }}" class="btn btn-outline-primary">{{ __('admin/guide.button_product') }}</a>
            <a href="{{ admin_route('products.create') }}" class="btn btn-outline-primary">{{ __('admin/guide.button_product_create') }}</a>
          </div>
        </div>
      </div>

      <div class="tab-pane fade" id="theme-tab">
        <div class="guide-info">
          <div class="left"><span><i class="bi bi-palette"></i></span></div>
          <div class="right">
            <p>{!! __('admin/guide.text_theme_1') !!}</p>
            <p>{{ __('admin/guide.text_theme_2') }}</p>
            <p class="mb-3">{!! __('admin/guide.text_theme_3') !!}</p>
            <a href="{{ admin_route('design.index') }}" target="_blank" class="btn btn-outline-primary">{{ __('admin/common.design_index') }}</a>
            <a href="{{ admin_route('design_menu.index') }}" class="btn btn-outline-primary">{{ __('admin/common.design_menu_index') }}</a>
            <a href="{{ admin_route('design_footer.index') }}" target="_blank" class="btn btn-outline-primary">{{ __('admin/common.design_footer_index') }}</a>
          </div>
        </div>
      </div>

      <div class="tab-pane fade" id="payment-tab">
        <div class="guide-info">
          <div class="left"><span><i class="bi bi-wallet2"></i></span></div>
          <div class="right">
            <p>{{ __('admin/guide.text_payment_1') }}</p>
            <p>{!! __('admin/guide.text_payment_2') !!}</p>
            <p>{!! __('admin/guide.text_payment_3') !!}</p>
            <p class="mb-3">{!! __('admin/guide.text_payment_4') !!}</p>
            <a href="{{ admin_route('plugins.payment') }}" class="btn btn-outline-primary">{{ __('admin/guide.button_payment') }}</a>
            <a href="{{ admin_route('plugins.shipping') }}" class="btn btn-outline-primary">{{ __('admin/guide.button_shipping') }}</a>
          </div>
        </div>
      </div>

      <div class="tab-pane fade" id="mail-tab">
        <div class="guide-info">
          <div class="left"><span><i class="bi bi-envelope-paper"></i></span></div>
          <div class="right">
            <p>{{ __('admin/guide.text_mail_1') }}</p>
            <p class="mb-3">{{ __('admin/guide.text_mail_2') }}</p>
            <a href="{{ admin_route('settings.index', ['tab' => 'tab-mail']) }}" class="btn btn-outline-primary">{{ __('admin/guide.button_mail') }}</a>
          </div>
        </div>
      </div>
    </div>
    <div class="tab-footer">
      <label class=""><input type="checkbox" name="hide_guide"> {{ __('admin/guide.button_hide') }}</label>
    </div>
  </div>
</div>

@push('footer')
  <script>
    $('.guide-close').on('click', function() {
      if ($('input[name="hide_guide"]').is(':checked')) {
        $http.put('settings/values', {guide: 0}, {hload: true});
      }

      $('.dashboard-guide-section').remove();
    });
  </script>
@endpush
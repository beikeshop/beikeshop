@php
  $setting = $plugin->getSetting() ?: [];
  $apiMode = old('api_mode', $setting['api_mode'] ?? 'rest') === 'nvp' ? 'nvp' : 'rest';
  $sandboxMode = old('sandbox_mode', $setting['sandbox_mode'] ?? 1);
  $status = old('status', (int) ($setting['status'] ?? 0));
@endphp

<form class="needs-validation" novalidate action="{{ admin_route('plugins.update', [$plugin->code]) }}" method="POST" id="form-app">
  @csrf
  {{ method_field('put') }}

  <div class="paypal-config">
    <div class="row g-3 mb-4">
      <div class="col-12 col-lg-6">
        <div class="paypal-option {{ $apiMode == 'rest' ? 'active' : '' }}" data-api-mode="rest">
          <div class="form-check">
            <input class="form-check-input" type="radio" name="api_mode" id="paypal-api-rest" value="rest" {{ $apiMode == 'rest' ? 'checked' : '' }}>
            <label class="form-check-label" for="paypal-api-rest">
              <span class="paypal-option-title">{{ __('Paypal::setting.rest_title') }}</span>
              <span class="paypal-option-desc">{{ __('Paypal::setting.rest_desc') }}</span>
            </label>
          </div>
        </div>
      </div>
      <div class="col-12 col-lg-6">
        <div class="paypal-option {{ $apiMode == 'nvp' ? 'active' : '' }}" data-api-mode="nvp">
          <div class="form-check">
            <input class="form-check-input" type="radio" name="api_mode" id="paypal-api-nvp" value="nvp" {{ $apiMode == 'nvp' ? 'checked' : '' }}>
            <label class="form-check-label" for="paypal-api-nvp">
              <span class="paypal-option-title">{{ __('Paypal::setting.nvp_title') }}</span>
              <span class="paypal-option-desc">{{ __('Paypal::setting.nvp_desc') }}</span>
            </label>
          </div>
        </div>
      </div>
    </div>

    <div class="paypal-tab-content" data-api-content="rest">
      <div class="paypal-section">
        <div class="paypal-section-head">
          <div>
            <h6 class="mb-1">{{ __('Paypal::setting.rest_credentials') }}</h6>
            <div class="text-secondary small">{{ __('Paypal::setting.rest_credentials_desc') }}</div>
          </div>
          <a href="https://developer.paypal.com/dashboard/applications/live" target="_blank" class="btn btn-sm btn-outline-secondary">{{ __('Paypal::setting.open_paypal_dashboard') }}</a>
        </div>

        <div class="row">
          <div class="col-12 col-xl-6">
            <div class="paypal-env-card">
              <div class="paypal-env-title">{{ __('Paypal::setting.sandbox_credentials') }}</div>
              <x-admin-form-input
                name="sandbox_client_id"
                title="Sandbox Client ID"
                value="{{ old('sandbox_client_id', $setting['sandbox_client_id'] ?? '') }}" />
              <x-admin-form-input
                name="sandbox_secret"
                title="Sandbox Secret"
                type="password"
                value="{{ old('sandbox_secret', $setting['sandbox_secret'] ?? '') }}" />
            </div>
          </div>
          <div class="col-12 col-xl-6">
            <div class="paypal-env-card">
              <div class="paypal-env-title">{{ __('Paypal::setting.live_credentials') }}</div>
              <x-admin-form-input
                name="live_client_id"
                title="Live Client ID"
                value="{{ old('live_client_id', $setting['live_client_id'] ?? '') }}" />
              <x-admin-form-input
                name="live_secret"
                title="Live Secret"
                type="password"
                value="{{ old('live_secret', $setting['live_secret'] ?? '') }}" />
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="paypal-tab-content" data-api-content="nvp">
      <div class="paypal-section">
        <div class="paypal-section-head">
          <div>
            <h6 class="mb-1">{{ __('Paypal::setting.nvp_credentials') }}</h6>
            <div class="text-secondary small">{{ __('Paypal::setting.nvp_credentials_desc') }}</div>
          </div>
          <span class="paypal-legacy-tag">{{ __('Paypal::setting.legacy_api') }}</span>
        </div>

        <div class="alert alert-warning border-0 paypal-warning">
          {{ __('Paypal::setting.nvp_warning') }}
        </div>

        <div class="row">
          <div class="col-12 col-xl-6">
            <div class="paypal-env-card">
              <div class="paypal-env-title">{{ __('Paypal::setting.sandbox_credentials') }}</div>
              <x-admin-form-input
                name="sandbox_api_username"
                :title="__('Paypal::setting.sandbox_api_username')"
                value="{{ old('sandbox_api_username', $setting['sandbox_api_username'] ?? '') }}" />
              <x-admin-form-input
                name="sandbox_api_password"
                :title="__('Paypal::setting.sandbox_api_password')"
                type="password"
                value="{{ old('sandbox_api_password', $setting['sandbox_api_password'] ?? '') }}" />
              <x-admin-form-input
                name="sandbox_api_signature"
                :title="__('Paypal::setting.sandbox_api_signature')"
                type="password"
                value="{{ old('sandbox_api_signature', $setting['sandbox_api_signature'] ?? '') }}" />
            </div>
          </div>
          <div class="col-12 col-xl-6">
            <div class="paypal-env-card">
              <div class="paypal-env-title">{{ __('Paypal::setting.live_credentials') }}</div>
              <x-admin-form-input
                name="live_api_username"
                :title="__('Paypal::setting.live_api_username')"
                value="{{ old('live_api_username', $setting['live_api_username'] ?? '') }}" />
              <x-admin-form-input
                name="live_api_password"
                :title="__('Paypal::setting.live_api_password')"
                type="password"
                value="{{ old('live_api_password', $setting['live_api_password'] ?? '') }}" />
              <x-admin-form-input
                name="live_api_signature"
                :title="__('Paypal::setting.live_api_signature')"
                type="password"
                value="{{ old('live_api_signature', $setting['live_api_signature'] ?? '') }}" />
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="paypal-section mt-4">
      <div class="paypal-section-head">
        <div>
          <h6 class="mb-1">{{ __('Paypal::setting.base_settings') }}</h6>
          <div class="text-secondary small">{{ __('Paypal::setting.base_settings_desc') }}</div>
        </div>
      </div>

      <x-admin-form-input
        name="currency"
        title="Currency Code"
        :required="true"
        value="{{ old('currency', $setting['currency'] ?? '') }}"
        description="{{ __('Paypal::setting.currency_desc') }}" />

      <x-admin-form-switch
        name="sandbox_mode"
        :title="__('Paypal::setting.sandbox_mode')"
        value="{{ $sandboxMode }}" />

      <x-admin-form-switch
        name="status"
        :title="__('admin/common.status')"
        value="{{ $status }}" />
    </div>
  </div>

  <x-admin::form.row title="">
    <button type="submit" class="btn btn-primary d-none btn-lg mt-4">{{ __('common.submit') }}</button>
  </x-admin::form.row>
</form>

<style>
  .paypal-config {
    --paypal-border: #e8edf5;
    --paypal-bg: #f7f9fc;
    --paypal-soft: #f0f5ff;
    --paypal-ink: #15213a;
  }

  .paypal-hero,
  .paypal-section,
  .paypal-option {
    border: 1px solid var(--paypal-border);
    border-radius: 22px;
    background: rgba(255, 255, 255, .92);
    box-shadow: 0 18px 45px rgba(17, 34, 68, .06);
  }

  .paypal-hero {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    padding: 24px;
    background:
      radial-gradient(circle at top right, rgba(0, 112, 186, .14), transparent 32%),
      linear-gradient(135deg, #ffffff 0%, #f6f9ff 100%);
  }

  .paypal-eyebrow,
  .paypal-badge,
  .paypal-legacy-tag {
    display: inline-flex;
    align-items: center;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 600;
  }

  .paypal-eyebrow {
    color: #0070ba;
    letter-spacing: .08em;
    margin-bottom: 8px;
    text-transform: uppercase;
  }

  .paypal-badge {
    flex: 0 0 auto;
    color: #005ea6;
    background: #eaf4ff;
    padding: 8px 14px;
  }

  .paypal-option {
    cursor: pointer;
    min-height: 124px;
    padding: 20px;
    transition: border-color .2s ease, box-shadow .2s ease, transform .2s ease;
  }

  .paypal-option.active {
    border-color: #0070ba;
    box-shadow: 0 20px 48px rgba(0, 112, 186, .14);
    transform: translateY(-1px);
  }

  .paypal-option .form-check {
    min-height: 100%;
  }

  .paypal-option-title,
  .paypal-option-desc {
    display: block;
  }

  .paypal-option-title {
    color: var(--paypal-ink);
    font-size: 16px;
    font-weight: 700;
    margin-bottom: 8px;
  }

  .paypal-option-desc {
    color: #667085;
    line-height: 1.6;
  }

  .paypal-section {
    padding: 24px;
  }

  .paypal-section-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    border-bottom: 1px solid var(--paypal-border);
    margin-bottom: 22px;
    padding-bottom: 18px;
  }

  .paypal-env-card {
    border: 1px solid var(--paypal-border);
    border-radius: 18px;
    background: var(--paypal-bg);
    height: 100%;
    padding: 20px 20px 4px;
  }

  .paypal-env-title {
    color: var(--paypal-ink);
    font-weight: 700;
    margin-bottom: 18px;
  }

  .paypal-legacy-tag {
    color: #9a5b00;
    background: #fff3d7;
    padding: 7px 12px;
  }

  .paypal-warning {
    background: #fff8e8;
    color: #7a4b00;
  }

  @media (max-width: 767.98px) {
    .paypal-hero,
    .paypal-section-head {
      align-items: flex-start;
      flex-direction: column;
    }
  }
</style>

@push('footer')
  <script>
    $(function () {
      function togglePaypalApiMode(mode) {
        $('.paypal-option').removeClass('active');
        $('.paypal-option[data-api-mode="' + mode + '"]').addClass('active');
        $('.paypal-tab-content').hide();
        $('.paypal-tab-content[data-api-content="' + mode + '"]').show();
      }

      togglePaypalApiMode($('input[name="api_mode"]:checked').val() || 'rest');

      $('.paypal-option').on('click', function () {
        const mode = $(this).data('api-mode');
        $('input[name="api_mode"][value="' + mode + '"]').prop('checked', true).trigger('change');
      });

      $('input[name="api_mode"]').on('change', function () {
        togglePaypalApiMode($(this).val());
      });
    });
  </script>
@endpush

@if (has_translator())
  <div class="mt-1 auto-translation-wrap">
    <div class="auto-translation-info d-flex align-items-center">
      {{ __('admin/common.auto_translation') }}：
      <select class="form-select form-select-sm w-auto from-locale-code">
        @foreach (locales() as $locale)
          <option value="{{ $locale['code'] }}" {{ $locale['code'] == locale() ? 'selected': '' }}>{{ $locale['name'] }}</option>
        @endforeach
      </select>
      <span class="mx-1"><i class="bi bi-arrow-right"></i></span>
      <select class="form-select form-select-sm w-auto to-locale-code">
        <option value="all">{{ __('admin/common.all_others') }}</option>
        @foreach (locales() as $locale)
          <option value="{{ $locale['code'] }}">{{ $locale['name'] }}</option>
        @endforeach
      </select>
      <button type="button" class="btn btn-outline-secondary btn-sm ms-2 translate-btn">{{ __('admin/common.text_translate') }}</button>
    </div>
  </div>
@endif
<x-admin::form.row :title="$title">
  <div class="mb-1 mt-2">
    <div class="form-check form-check-inline">
      <input class="form-check-input" id="{{ $name }}-1" type="radio" name="{{ $name }}" id="{{ $name }}-1" value="1" {{ $value ? 'checked' : '' }}>
      <label class="form-check-label" for="{{ $name }}-1">{{ __('common.enable') }}</label>
    </div>
    <div class="form-check form-check-inline">
      <input class="form-check-input" id="{{ $name }}-0" type="radio" name="{{ $name }}" id="{{ $name }}-0" value="0" {{ !$value ? 'checked' : '' }}>
      <label class="form-check-label" for="{{ $name }}-0">{{ __('common.disable') }}</label>
    </div>
  </div>
  {{ $slot }}
</x-admin::form.row>

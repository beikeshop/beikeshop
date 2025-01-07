<x-admin::form.row :title="$title" :required="$required">
  <div>
    <textarea {{ $disabled ? 'disabled' : '' }} rows="4" type="text" name="{{ $name }}" class="form-control wp-400 {{ $error ? 'is-invalid' : '' }}" @if ($required) required @endif placeholder="{{ $title }}">{{ $value }}</textarea>
    <span class="invalid-feedback" role="alert">
      @if ($error)
        {{ $error }}
      @else
      {{ __('common.error_required', ['name' => $title]) }}
      @endif
    </span>
  </div>
  {{ $slot }}
</x-admin::form.row>

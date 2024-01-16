<x-admin::form.row :title="$title" :required="$required">
  <input type="{{ $type }}" name="{{ $name }}"
    class="form-control wp-{{ $width }} {{ $error ? 'is-invalid' : '' }}" value="{{ $value }}"
    placeholder="{{ $placeholder ?: $title }}" @if ($required) required @endif @if ($step) step="{{ $step }}" @endif>
    @if ($description)
    <div class="help-text font-size-12 lh-base">{!! $description !!}</div>
    @endif

  <span class="invalid-feedback" role="alert">
    @if ($error)
      {{ $error }}
    @else
    {{ __('common.error_required', ['name' => $title]) }}
    @endif
  </span>
  {{ $slot }}
</x-admin::form.row>

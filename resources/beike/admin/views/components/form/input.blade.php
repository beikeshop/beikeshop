<x-admin::form.row :title="$title" :required="$required">
  @php
    $is_invalid = $error ? 'is-invalid' : '';
    $placeholder = $placeholder ?: $title;
  @endphp

  @if ($groupLeft || $groupRight)
  <div class="input-group wp-{{ $width }}">
    @if ($groupLeft) <span class="input-group-text">{{ $groupLeft }}</span> @endif
    <input type="{{ $type }}" name="{{ $name }}"
      {{ $disabled ? 'disabled' : '' }}
      class="form-control wp-{{ $width }} {{ $is_invalid }}" value="{{ $value }}"
      placeholder="{{ $placeholder }}" @if ($required) required
      @endif @if ($step) step="{{ $step }}" @endif>
    <span class="invalid-feedback" role="alert">@if ($error) {{ $error }} @else {{ __('common.error_required', ['name' => $title]) }} @endif</span>
    @if ($groupRight) <span class="input-group-text">{{ $groupRight }}</span> @endif
  </div>
  @else
  <input type="{{ $type }}" name="{{ $name }}"
         {{ $disabled ? 'disabled' : '' }}
         class="form-control wp-{{ $width }} {{ $is_invalid }}" value="{{ $value }}"
         placeholder="{{ $placeholder }}" @if ($required) required
         @endif @if ($step) step="{{ $step }}" @endif>
  <span class="invalid-feedback" role="alert">@if ($error) {{ $error }} @else {{ __('common.error_required', ['name' => $title]) }} @endif</span>
  @endif

  @if ($description)
    <div class="help-text font-size-12 lh-base">{!! $description !!}</div>
  @endif
  {{ $slot }}
</x-admin::form.row>

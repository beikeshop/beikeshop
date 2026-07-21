<div class="mb-3">
  @if ($title ?? false)
    <label class="col-form-label text-end {{ isset($required) && $required ? 'required' : '' }}">{{ $title ?? '' }}</label>
  @endif
  <div class="col-auto">
    {{ $slot }}
  </div>
</div>
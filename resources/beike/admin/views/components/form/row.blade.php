<div class="row g-3 mb-3">
  <label class="wp-200 col-form-label text-end {{ isset($required) && $required ? 'required' : '' }}">{{ $title ?? '' }}</label>
  <div class="col-auto wp-200-">
    {{ $slot }}
  </div>
</div>
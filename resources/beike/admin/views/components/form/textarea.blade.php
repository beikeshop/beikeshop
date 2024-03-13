<x-admin::form.row :title="$title" :required="$required">
  <div>
    <textarea rows="4" type="text" name="{{ $name }}" class="form-control wp-400" @if ($required) required @endif placeholder="{{ $title }}">{{ $value }}</textarea>
    <span class="invalid-feedback" role="alert">
      {{ __('common.error_required', ['name' => $title]) }}
    </span>
  </div>
  {{ $slot }}
</x-admin::form.row>

<x-admin::form.row :title="$title" :required="$required">
  <input type="text" name="{{ $name }}" class="form-control wp-{{ $width }} {{ $error ? 'is-invalid' : '' }}" value="{{ $value }}" placeholder="{{ $title }}">
  @if ($error)
    <span class="invalid-feedback" role="alert">{{ $error }}</span>
  @endif
  {{ $slot }}
</x-admin::form.row>

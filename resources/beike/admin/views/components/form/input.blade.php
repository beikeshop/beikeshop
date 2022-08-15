<x-admin::form.row :title="$title" :required="$required">
  <input type="text" name="{{ $name }}" class="form-control wp-{{ $width }} {{ $errors->has($name) ? 'is-invalid' : '' }}" value="{{ $value }}" placeholder="{{ $title }}">
  @if ($errors->has($name))
    <span class="invalid-feedback" role="alert">{{ $errors->first($name) }}</span>
  @endif
  {{ $slot }}
</x-admin::form.row>

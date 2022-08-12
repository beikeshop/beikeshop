@if (isset($html) && $html)
  @push('header')
  <script src="{{ asset('vendor/tinymce/5.9.1/tinymce.min.js') }}"></script>
  @endpush
@endif

<x-admin::form.row :title="$title" :required="$required">
  <div class="{{ isset($html) && $html ? 'w-max-1000' : '' }}">
    <textarea rows="4" type="text" name="{{ $name }}" class="{{ isset($html) && $html ? 'tinymce' : 'form-control wp-400' }}" placeholder="{{ $title }}">{{ $value }}</textarea>
  </div>
  {{ $slot }}
</x-admin::form.row>

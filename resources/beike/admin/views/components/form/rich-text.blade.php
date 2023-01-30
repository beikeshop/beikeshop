@push('header')
<script src="{{ asset('vendor/tinymce/5.9.1/tinymce.min.js') }}"></script>
@endpush

<x-admin::form.row :title="$title" :required="$required">
  <div class="w-max-1000">
    <textarea rows="4" type="text" name="{{ $name }}" class="tinymce" placeholder="{{ $title }}">{{ $value }}</textarea>
  </div>
  {{ $slot }}
</x-admin::form.row>

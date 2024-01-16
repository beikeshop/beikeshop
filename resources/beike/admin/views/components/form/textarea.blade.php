<x-admin::form.row :title="$title" :required="$required">
  <div>
    <textarea rows="4" type="text" name="{{ $name }}" class="form-control wp-400" placeholder="{{ $title }}">{{ $value }}</textarea>
  </div>
  {{ $slot }}
</x-admin::form.row>

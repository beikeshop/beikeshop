<x-admin::form.row :title="$title" :required="$required">
  <input type="text" name="{{ $name }}" class="form-control wp-400 {{ $class }}" value="{{ $value }}" placeholder="{{ $title }}">
  {{ $slot }}
</x-admin::form.row>

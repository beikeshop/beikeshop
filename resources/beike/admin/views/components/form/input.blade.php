<x-admin::form.row :title="$title">
  <input type="text" name="{{ $name }}" class="form-control wp-400" value="{{ $value }}" placeholder="{{ $title }}">
  {{ $slot }}
</x-admin::form.row>

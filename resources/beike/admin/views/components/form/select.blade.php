<x-admin::form.row :title="$title">
  <select class="form-select me-3 wp-{{ $width }}" name="{{ $name }}">
    @foreach ($options as $option)
      <option value="{{ $option[$key] }}" {{ $option[$key] == $value ? 'selected': '' }}>{{ $option[$label] }}</option>
    @endforeach
  </select>
  {{ $slot }}
</x-admin::form.row>

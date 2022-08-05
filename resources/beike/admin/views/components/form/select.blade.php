<x-admin::form.row :title="$title">
  <select class="form-select wp-200 me-3" name="{{ $name }}">
    @foreach ($options as $option)
      <option value="{{ $option[$key] }}" {{ $option[$key] == $value ? 'selected': '' }}>{{ $option[$label] }}</option>
    @endforeach
  </select>
  {{ $slot }}
</x-admin::form.row>

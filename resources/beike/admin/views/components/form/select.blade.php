<x-admin::form.row :title="$title">
  {{-- <input type="text" name="{{ $name }}" class="form-control wp-400" value="{{ $value }}" placeholder="{{ $title }}"> --}}
  <select class="form-select" aria-label="Default select example">
    <option selected>Open this select menu</option>
    <option value="1">One</option>
    <option value="2">Two</option>
    <option value="3">Three</option>
  </select>
  {{ $slot }}
</x-admin::form.row>

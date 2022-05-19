@if (0)
<div class="form-group">
  <div class="row">
    <label for="" class="col-sm-2 col-form-label">{{ $title }}</label>
    <div class="col-sm-10">
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="{{ $name }}" id="{{ $name }}-1" value="1" {{ $value ? 'checked' : '' }}>
        <label class="form-check-label" for="{{ $name }}-1">启用</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="{{ $name }}" id="{{ $name }}-0" value="0" {{ !$value ? 'checked' : '' }}>
        <label class="form-check-label" for="{{ $name }}-0">禁用</label>
      </div>
    </div>
  </div>
</div>
@endif

<x-admin::form.row :title="$title">
  <div class="form-check form-check-inline">
    <input class="form-check-input" type="radio" name="{{ $name }}" id="{{ $name }}-1" value="1" {{ $value ? 'checked' : '' }}>
    <label class="form-check-label" for="{{ $name }}-1">启用</label>
  </div>
  <div class="form-check form-check-inline">
    <input class="form-check-input" type="radio" name="{{ $name }}" id="{{ $name }}-0" value="0" {{ !$value ? 'checked' : '' }}>
    <label class="form-check-label" for="{{ $name }}-0">禁用</label>
  </div>
</x-admin::form.row>

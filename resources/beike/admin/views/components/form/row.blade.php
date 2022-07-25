<div class="row g-3 mb-3">
  <label for="" class="wp-200 col-form-label text-end">{{ $title ?? '' }}</label>
  <div class="col-auto">
    {{ $slot }}
  </div>
</div>

@if (0)
<div class="el-form-item el-form-item--small">
  <label for="" class="el-form-item__label" style="width: 200px">{{ $title ?? '' }}</label>
  <div class="el-form-item__content" style="margin-left: 200px">
    {{ $slot }}
  </div>
</div>
@endif

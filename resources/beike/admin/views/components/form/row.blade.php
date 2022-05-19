@if (0)
<div class="form-group">
  <div class="row">
    <label for="" class="col-sm-2 col-form-label">{{ $title ?? '' }}</label>
    <div class="col-sm-10">
      {{ $slot }}
    </div>
  </div>
</div>
@endif

<div class="el-form-item el-form-item--small">
  <label for="" class="el-form-item__label" style="width: 200px">{{ $title ?? '' }}</label>
  <div class="el-form-item__content" style="margin-left: 200px">
    {{ $slot }}
  </div>
</div>

@if (0)
<div class="open-file-manager">
  <div>
    @if ($image)
    <img src="{{ image_resize($image) }}" class="img-fluid">
    @else
    <i class="bi bi-plus fs-1 text-muted"></i>
    @endif
  </div>
</div>
<input type="hidden" value="{{ $value }}" name="{{ $name }}">
@endif
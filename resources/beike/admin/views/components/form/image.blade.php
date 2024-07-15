<x-admin::form.row :title="$title">
  <div class="open-file-manager bg-light wh-80 border d-flex justify-content-center align-items-center me-2 mb-2 position-relative">
    @if ($value)
      <img src="{{ image_resize($value) }}" class="img-fluid">
    @else
      <i class="bi bi-plus fs-1 text-muted"></i>
    @endif
  </div>
  @if ($description)
  <div class="help-text font-size-12 lh-base">{!! $description !!}</div>
  @endif
  <input type="hidden" value="{{ $value }}" name="{{ $name }}">
  {{ $slot }}
</x-admin::form.row>

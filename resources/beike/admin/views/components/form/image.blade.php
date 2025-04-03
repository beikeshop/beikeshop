<x-admin::form.row :title="$title">
  <div class="open-file-manager bg-light wh-80 border d-flex justify-content-center align-items-center me-2 mb-2 position-relative">
    @if ($isRemove)
      <div class="position-absolute top-0 end-0 img-components-remove {{ !$value ? 'd-none' : '' }}">
        <button class="btn btn-danger btn-sm wh-20 p-0" type="button"><i class="bi bi-trash"></i></button>
      </div>
    @endif
    @if ($value)
      <img src="{{ image_resize($value) }}" class="img-fluid">
    @else
      <i class="bi bi-plus fs-1 text-muted icon-plus"></i>
    @endif
  </div>
  @if ($description)
  <div class="help-text font-size-12 lh-base">{!! $description !!}</div>
  @endif
  <input type="hidden" value="{{ $value }}" name="{{ $name }}">
  {{ $slot }}
</x-admin::form.row>

@pushOnce('footer')
<script>
$(document).on('click', '.img-components-remove', function (e) {
  e.stopPropagation();
  let parentDiv = $(this).closest('.open-file-manager');
  parentDiv.find('img').replaceWith('<i class="bi bi-plus fs-1 text-muted icon-plus"></i>');
  parentDiv.siblings('input[name="{{ $name }}"]').val('');
  $(this).addClass('d-none');
});
</script>
<style>
  .img-components-remove {
    display: none;
  }

  .open-file-manager:hover .img-components-remove {
    display: block;
  }
</style>
@endPushOnce

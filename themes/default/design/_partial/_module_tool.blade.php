@if ($design)
<div class="module-edit">
  <div class="edit-wrap">
    <div class="down" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{ __('admin/builder.move_down') }}"><i class="bi bi-chevron-down"></i></div>
    <div class="up" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{ __('admin/builder.move_up') }}"><i class="bi bi-chevron-up"></i></div>
    <div class="delete" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{ __('common.delete') }}"><i class="bi bi-x-lg"></i></div>
    <div class="edit" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{ __('common.edit') }}"><i class="bi bi-pencil-square"></i></div>
  </div>
</div>
@endif

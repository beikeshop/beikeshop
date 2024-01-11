<div class="alert alert-{{ $type }} alert-dismissible">
  @php
    $class = 'check-circle-fill';
    if ($type == 'danger') {
      $class = 'x-circle-fill';
    } elseif ($type == 'warning') {
      $class = 'exclamation-circle-fill';
    } elseif ($type == 'info') {
      $class = 'info-circle-fill';
    }
  @endphp
  <i class="bi bi-{{ $class }}"></i>
  {!! $msg !!}
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
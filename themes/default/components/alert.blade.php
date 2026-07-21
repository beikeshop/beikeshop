<div class="alert alert-{{ $type }} alert-dismissible">
  @if ($type == 'success')
    <i class="bi bi-check-circle-fill"></i>
  @elseif ($type == 'danger')
    <i class="bi bi-exclamation-circle-fill"></i>
  @endif
  {{ $msg }}
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
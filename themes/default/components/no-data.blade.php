<div class="d-flex flex-column align-center align-items-center mb-4">
  <img src="{{ asset('image/no-data.svg') }}" class="img-fluid wp-200 my-3">
  <div class="text-secondary fs-4 mb-3">{{ $text }}</div>
  @if ($link)
    <a href="{{ $link }}" class="btn btn-primary"><i class="bi bi-box-arrow-left"></i> {{ $btn }}</a>
  @endif
</div>
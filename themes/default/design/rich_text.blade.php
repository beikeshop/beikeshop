<section class="module-item {{ $design ? 'module-item-design' : ''}}" id="module-{{ $module_id }}">
  @include('design._partial._module_tool')

  <div class="module-info mb-3 mb-md-5">
    @if ($content['data'])
    {!! $content['data'] !!}
    @elseif (!$content['data'] and $design)
    <div class="text-center p-3 fs-4 text-secondary">{{ __('admin/builder.modules_enter_content') }}</div>
    @endif
  </div>
</section>
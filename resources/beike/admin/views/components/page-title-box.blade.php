@hasSection('title')
<div class="page-title-box d-flex align-items-center justify-content-between @hasSection('head-form-btns') head-edit-tool @endif">
  <div class="d-flex align-items-center page-title-left">
    @if(trim($__env->yieldContent('head-form-btns')) || trim($__env->yieldContent('page-title-back')))
      @if($backUrl && $backUrl !== '1')
        <a href="{{ $backUrl }}" class="btn btn-default btn-sm me-2"><i class="bi bi-chevron-left"></i></a>
      @else
        <div class="btn btn-default btn-sm me-2" onclick="history.back()"><i class="bi bi-chevron-left"></i></div>
      @endif
    @else
    <div class="btn btn-sm me-2 cursor-auto"><i class="bi bi-list"></i></div>
    @endif
    <h5 class="page-title d-flex align-items-center">
      @yield('title')
    </h5>
    <div class="ms-4">@yield('page-title-after')</div>
  </div>

  @hasSection('head-form-btns')
    <div class="page-title-btn-wrap">
      @if (trim($__env->yieldContent('head-exit-hide')) !== '1')
      <button type="button" class="btn btn-default exit-edit">{{ __('admin/common.text_exit_edit') }}</button>
      @endif
      <button type="button" class="btn btn-primary submit-form" form="@yield('submit-form', 'form-app')">{{ __('common.save') }}</button>
    </div>
  @endif

  <div class="text-nowrap page-title-right">
    @yield('page-title-right')

    @hasSection('head-form-btns')
    <div class="close-form-btns ms-2"><i class="bi bi-x-circle"></i></div>
    @endif
  </div>
</div>
@endif
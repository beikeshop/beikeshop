@extends('admin::layouts.master')

@section('title', __('admin/page.index'))

@push('header')
  <script src="{{ asset('vendor/tinymce/5.9.1/tinymce.min.js') }}"></script>
@endpush

@section('content')
  <div id="plugins-app-form" class="card h-min-600">
    <div class="card-body">
      <form novalidate class="needs-validation" action="{{ $page->id ? admin_route('pages.update', [$page->id]) : admin_route('pages.store') }}" method="POST">
        @csrf
        @method($page->id ? 'PUT' : 'POST')
        <ul class="nav nav-tabs nav-bordered mb-3" role="tablist">
          @foreach ($admin_languages as $language)
            <li class="nav-item" role="presentation">
              <button class="nav-link {{ $loop->first ? 'active' : '' }}" data-bs-toggle="tab" data-bs-target="#tab-{{ $language['code'] }}" type="button" >{{ $language['name'] }}</button>
            </li>
          @endforeach
        </ul>
        <div class="tab-content">
          @foreach ($admin_languages as $language)
            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="tab-{{ $language['code'] }}">
              @php
                $error_title = $errors->first("descriptions.{$language['code']}.title");
              @endphp
              <x-admin-form-input
                error="{{ $error_title }}"
                name="descriptions[{{ $language['code'] }}][title]"
                title="{{ __('admin/page.info_title') }}"
                :required="true"
                value="{{ old('title', $descriptions[$language['code']]['title'] ?? '') }}"
              />

              <x-admin::form.row title="{{ __('admin/page.info_content') }}">
                <div class="w-max-1000">
                  <textarea name="descriptions[{{ $language['code'] }}][content]" data-tinymce-height="600" class="form-control tinymce">
                    {{ old('content', $descriptions[$language['code']]['content'] ?? '') }}
                  </textarea>
                </div>
                @if ($errors->has("descriptions.{$language['code']}.content"))
                  <span class="invalid-feedback d-block" role="alert">{{ $errors->first("descriptions.{$language['code']}.content") }}</span>
                @endif
              </x-admin::form.row>

              <input type="hidden" name="descriptions[{{ $language['code'] }}][locale]" value="{{ $language['code'] }}">
              <x-admin-form-input name="descriptions[{{ $language['code'] }}][meta_title]" title="{{ __('admin/setting.meta_tiele') }}" value="{{ old('meta_title', $descriptions[$language['code']]['meta_title'] ?? '') }}" />
              <x-admin-form-input name="descriptions[{{ $language['code'] }}][meta_description]" title="{{ __('admin/setting.meta_description') }}" value="{{ old('meta_description', $descriptions[$language['code']]['meta_description'] ?? '') }}" />
              <x-admin-form-input name="descriptions[{{ $language['code'] }}][meta_keyword]" title="{{ __('admin/setting.meta_keyword') }}" value="{{ old('meta_keyword', $descriptions[$language['code']]['meta_keyword'] ?? '') }}" />
            </div>
          @endforeach

          <x-admin-form-switch name="active" title="{{ __('common.status') }}" value="{{ old('active', $page->active) }}" />
          <x-admin::form.row title="">
            <button type="submit" class="mt-3 btn btn-primary">{{ __('common.submit') }}</button>
          </x-admin::form.row>
        </div>
      </form>
    </div>
  </div>
@endsection

@push('footer')
  {{-- <script>
    $(function() {

    });
  </script> --}}
@endpush




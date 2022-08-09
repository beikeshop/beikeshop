@extends('admin::layouts.master')

@section('title', '信息页面')

@push('header')
  <script src="{{ asset('vendor/tinymce/5.9.1/tinymce.min.js') }}"></script>
@endpush

@section('content')
  <div id="plugins-app-form" class="card h-min-600">
    <div class="card-body">
      {{-- <h6 class="border-bottom pb-3 mb-4">编辑信息页面</h6> --}}
      <form action="{{ admin_route('pages.store') }}" method="POST">
        @csrf

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
              <x-admin-form-input name="{{ $language['code'] }}[title]" title="信息标题" value="{{ old('title', '') }}" />
              <x-admin::form.row title="内容">
                <div class="w-max-1000">
                  <textarea name="{{ $language['code'] }}[content]" data-tinymce-height="600" class="form-control tinymce">
                    {{ old('content', '') }}
                  </textarea>
                </div>
              </x-admin::form.row>
              <x-admin-form-input name="{{ $language['code'] }}[meta_title]" title="Meta Tag 标题" value="{{ old('meta_title', '') }}" />
              <x-admin-form-input name="{{ $language['code'] }}[meta_description]" title="Meta Tag 描述" value="{{ old('meta_description', '') }}" />
              <x-admin-form-input name="{{ $language['code'] }}[meta_keyword]" title="Meta Tag 关键字" value="{{ old('meta_keyword', '') }}" />
              <x-admin::form.row title="">
                <button type="submit" class="mt-3 btn btn-primary">提交</button>
              </x-admin::form.row>
            </div>
          @endforeach
        </div>

      </form>
    </div>
  </div>
@endsection

@push('footer')
  <script>
    $(function() {

    });
  </script>
@endpush




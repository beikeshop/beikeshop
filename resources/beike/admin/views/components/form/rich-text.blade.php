@push('header')
<script src="{{ asset('vendor/tinymce/5.9.1/tinymce.min.js') }}"></script>
@endpush

<x-admin::form.row :title="$title" :required="$required">
  @if ($multiple)
    <ul class="nav nav-tabs w-max-1000 mb-3" id="myTab" role="tablist">
      @foreach (locales() as $locale)
        <li class="nav-item" role="presentation">
          <button class="nav-link {{ $loop->first ? 'active' : ''}}" id="{{ $locale['code'] }}" data-bs-toggle="tab" data-bs-target="#{{ $locale['code'] }}-pane" type="button">{{ $locale['name'] }}</button>
        </li>
      @endforeach
    </ul>

    <div class="tab-content w-max-1000" id="myTabContent">
      @foreach (locales() as $locale)
      <div class="tab-pane fade {{ $loop->first ? 'show active' : ''}}" id="{{ $locale['code'] }}-pane" role="tabpanel" aria-labelledby="{{ $locale['code'] }}">
        <textarea rows="4" type="text" name="{{ $name }}[{{ $locale['code'] }}]" class="tinymce" placeholder="{{ $title }}">{{ $value[$locale['code']] ?? '' }}</textarea>
      </div>
      @endforeach
    </div>
  @else
    <div class="w-max-1000">
      <textarea rows="4" type="text" name="{{ $name }}" class="tinymce" placeholder="{{ $title }}">{{ $value }}</textarea>
    </div>
  @endif
  {{ $slot }}
</x-admin::form.row>


<div class="form-group">
  <div class="row">
    <label for="" class="col-sm-2 col-form-label">{{ $title }}</label>
    <div class="col-sm-10">
      @foreach (locales() as $index => $locale)
        <div class="input-group input-group-sm short mb-1">
          <input type="text" class="form-control" name="{{ $formatName($index) }}" placeholder="{{ $locale['name'] }}" value="{{ $formatValue($index) }}">
          <div class="input-group-append">
            <span class="input-group-text" id="input-{{ $name }}-{{ $locale['code'] }}">{{ $locale['name'] }}</span>
          </div>
        </div>

        @if ($attributes->has('required'))
          @error($errorKey($index))
            <x-beike::form.error :message="$message" />
          @enderror
        @endif
      @endforeach
    </div>
  </div>
</div>

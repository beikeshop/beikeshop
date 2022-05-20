<x-admin::form.row :title="$title">
  @foreach (locales() as $index => $locale)
    <div class="input-group">
      <div class="input-group-prepend">
        <span class="input-group-text">{{ $locale['name'] }}</span>
      </div>
      <input type="text" name="{{ $formatName($locale['code']) }}" value="{{ $formatValue($locale['code']) }}" class="form-control" placeholder="{{ $locale['name'] }}">
    </div>
    @if ($attributes->has('required'))
      @error($errorKey($locale['code']))
        <x-admin::form.error :message="$message" />
      @enderror
    @endif
  @endforeach
</x-admin::form.row>


@if (0)
<div class="el-form-item el-form-item--small">
  <label for="" class="el-form-item__label" style="width: 200px">{{ $title }}</label>
  <div class="el-form-item__content" style="margin-left: 200px">
    <div style="max-width: 400px;">
      @foreach (locales() as $index => $locale)
        <div class="mb-1 el-input el-input--small el-input-group el-input-group--append">
          <input type="text" class="el-input__inner" name="{{ $formatName($locale['code']) }}" placeholder="{{ $locale['name'] }}" value="{{ $formatValue($locale['code']) }}">
          <div class="el-input-group__append">{{ $locale['name'] }}</div>
        </div>
        @if ($attributes->has('required'))
          @error($errorKey($locale['code']))
            <x-admin::form.error :message="$message" />
          @enderror
        @endif
      @endforeach
    </div>
  </div>
</div>
@endif

<x-admin::form.row :title="$title">
  @foreach (locales() as $index => $locale)
    <div class="d-flex wp-{{ $width }}">
      <span class="input-group-text wp-100 px-1" id="basic-addon1">{{ $locale['name'] }}</span>
      <input type="text" name="{{ $formatName($locale['code']) }}" value="{{ $formatValue($locale['code']) }}" class="form-control short" placeholder="{{ $locale['name'] }}">
    </div>
    @if ($attributes->has('required'))
      @error($errorKey($locale['code']))
        <x-admin::form.error :message="$message" />
      @enderror
    @endif
  @endforeach
</x-admin::form.row>

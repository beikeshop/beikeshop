@if($format)
  <x-admin::form.row :title="$title">
    <select class="{{ $class }}" name="{{ $name }}" {{ $attributes }}>
      @foreach ($options as $option)
        <option
          value="{{ $option[$key] }}" {{ $option[$key] == $value ? 'selected': '' }}>{{ $option[$label] }}</option>
      @endforeach
    </select>
    {{ $slot }}
  </x-admin::form.row>
@else

  @if(isTwoDimensionalArray($options))
    <select class="{{ $class }}" name="{{ $name }}" {{ $attributes  }}>
      @foreach ($options as $option)
        <option
          value="{{ $option[$key] }}" {{ $option[$key] == $value ? 'selected': '' }}>{{ $option[$label] }}</option>
      @endforeach
    </select>
  @else
    <select class="{{ $class }}" name="{{ $name }}" {{ $attributes  }}>
      @foreach ($options as $option)
        <option value="{{ $option }}" {{ $option == $value ? 'selected': '' }}>{{ eval($label) }}</option>
      @endforeach
    </select>
  @endif
@endif


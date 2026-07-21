@hook('components.breadcrumbs.before')
@unless ($breadcrumbs->isEmpty())
@if (request('_from') != 'app')
<div class="breadcrumb-wrap">
  <div class="container{{ $isFull ?? false ? '-fluid' : '' }}">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        @foreach ($breadcrumbs as $breadcrumb)
          @if (isset($breadcrumb['url']) && $breadcrumb['url'])
          <li class="breadcrumb-item"><a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['title'] }}</a></li>
          @else
          <li class="breadcrumb-item active" aria-current="page">{{ $breadcrumb['title'] }}</li>
          @endif
        @endforeach
      </ol>
    </nav>
  </div>
</div>
@else
<br>
@endif
@endunless
@hook('components.breadcrumbs.after')

<ul class="list-unstyled navbar-nav">
  @foreach ($links as $link)
    @if (is_mobile())
      @if (!$link['hide_mobile'])
        <li class="nav-item {{ $link['active'] ? 'active' : '' }}">
          <a target="{{ $link['new_window'] ? '_blank' : '_self' }}" class="nav-link" href="{{ $link['url'] }}"> {{ $link['title'] }}</a>
        </li>
      @endif
    @else
      <li class="nav-item {{ $link['active'] ? 'active' : '' }}">
        <a target="{{ $link['new_window'] ? '_blank' : '_self' }}" class="nav-link" href="{{ $link['url'] }}"> {{ $link['title'] }}</a>
      </li>
    @endif
  @endforeach
</ul>


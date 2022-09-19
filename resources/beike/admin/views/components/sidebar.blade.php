<div class="">
  <ul class="list-unstyled navbar-nav">
    @foreach ($links as $link)
      <li class="nav-item {{ $link['active'] ? 'active' : '' }}">
        <a target="{{ $link['new_window'] ? '_blank' : '_self' }}" class="nav-link" href="{{ $link['url'] }}"> {{ $link['title'] }}</a>
      </li>
    @endforeach
  </ul>
</div>

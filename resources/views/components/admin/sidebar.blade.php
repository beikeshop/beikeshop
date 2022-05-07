<div class="">
  <ul class="list-unstyled navbar-nav">
    @foreach ($links as $link)
      <li class="nav-item {{ $link['active'] ? 'active' : '' }}">
        <a class="nav-link" href="{{ $link['url'] }}"><i class="iconfont">&#xe65c;</i> {{ $link['title'] }}</a>
      </li>
    @endforeach
  </ul>
</div>

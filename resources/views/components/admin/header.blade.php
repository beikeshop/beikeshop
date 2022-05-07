<div class="header-wrap">
  <div class="header-left">
    <div class="logo">
      {{-- <img src="http://dummyimage.com/200x50" class="img-fluid"> --}}
      <div class="text-center"><h5 class="mb-0">beike admin</h5></div>
    </div>
  </div>
  <div class="header-right">
    <ul class="navbar navbar-right">
      @foreach ($links as $link)
        <li class="nav-item {{ $link['active'] ? 'active' : '' }}"><a href="{{ $link['url'] }}" class="nav-link">{{ $link['title'] }}</a></li>
      @endforeach
    </ul>
    <ul class="navbar">
      <li class="nav-item"><a href="{{ admin_route('logout.index') }}" class="nav-link">退出登录</a></li>
      <li class="nav-item">

        <a href="" class="nav-link">
          <img src="http://dummyimage.com/100x100" class="avatar img-fluid rounded-circle me-1">
          <span class="text-dark ml-2">{{ auth()->user()->name }}</span>
        </a>
      </li>
    </ul>
  </div>
</div>

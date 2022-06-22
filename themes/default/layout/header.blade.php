<header>
  <div class="top-wrap">
    <div class="container d-flex justify-content-between align-items-center">
      <div class="left d-flex align-items-center">
        <div class="dropdown">
          <a class="btn dropdown-toggle" href="#" role="button" id="currency-dropdown" data-toggle="dropdown"
            aria-expanded="false">
            $ USD
          </a>

          <div class="dropdown-menu" aria-labelledby="currency-dropdown">
            <a class="dropdown-item" href="#">Action</a>
            <a class="dropdown-item" href="#">Another action</a>
            <a class="dropdown-item" href="#">Something else here</a>
          </div>
        </div>
        <div class="dropdown">
          <a class="btn dropdown-toggle" href="#" role="button" id="language-dropdown" data-toggle="dropdown"
            aria-expanded="false">
            Language
          </a>

          <div class="dropdown-menu" aria-labelledby="language-dropdown">
            <a class="dropdown-item" href="#">Action</a>
            <a class="dropdown-item" href="#">Another action</a>
            <a class="dropdown-item" href="#">Something else here</a>
          </div>
        </div>
      </div>
      <div class="right">
        <a href="">Delivery</a>
        <a href="">Help</a>
        <span>028-0000000</span>
      </div>
    </div>
  </div>
  <div class="header-content py-3">
    <div class="container navbar-expand-lg">
      <div class="logo"><a href="http://"><img src="{{ asset('image/logo.png') }}" class="img-fluid"></a></div>
      <div class="menu-wrap">
        <ul class="navbar-nav mx-auto">
          <li class="dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Home</a>
            <ul class="dropdown-menu">
              <li><a href="" class="dropdown-item">sssss</a></li>
              <li><a href="" class="dropdown-item">sssss</a></li>
              <li><a href="" class="dropdown-item">sssss</a></li>
              <li><a href="" class="dropdown-item">sssss</a></li>
            </ul>
          </li>
          <li><a class="nav-link" href="#">夏季新品</a></li>
          <li><a class="nav-link" href="#">今日上心</a></li>
          <li><a class="nav-link" href="#">今日上心</a></li>
          <li><a class="nav-link" href="#">今日上心</a></li>
        </ul>

        @foreach ($categories as $category)
          <a href="{{ shop_route('categories.show', $category) }}">{{ $category->description->name }}</a>
        @endforeach
      </div>
      <div class="right-btn">
        <ul class="navbar-nav flex-row">
          <li class="nav-item"><a href="" class="nav-link"><i class="iconfont">&#xe8d6;</i></a></li>
          <li class="nav-item"><a href="" class="nav-link"><i class="iconfont">&#xe662;</i></a></li>
          <li class="nav-item"><a href="" class="nav-link"><i class="iconfont">&#xe619;</i></a></li>
          <li class="nav-item"><a href="" class="nav-link"><i class="iconfont">&#xe634;</i></a></li>
        </ul>
      </div>
    </div>
  </div>
</header>

<ul class="navbar-nav mx-auto">
  @hook('header.menu.before')
  @foreach ($menu_content as $menu)
    @if ($menu['name'])
      <li
        class="nav-item {{ isset($menu['children_group']) ? 'dropdown' : '' }} {{ isset($menu['isFull']) && $menu['isFull'] ? 'position-static' : '' }}">
        <a class="nav-link fw-bold {{ isset($menu['children_group']) ? 'dropdown-toggle' : '' }}"
          target="{{ isset($menu['new_window']) && $menu['new_window'] ? '_blank' : '_self' }}"
          href="{{ $menu['link'] ?: 'javascript:void(0)' }}">
          {{ $menu['name'] }}
          @if (isset($menu['badge']) && $menu['badge']['name'])
            <span class="badge"
              style="background-color: {{ $menu['badge']['bg_color'] }}; color: {{ $menu['badge']['text_color'] }}; border-color: {{ $menu['badge']['bg_color'] }}">
              {{ $menu['badge']['name'] }}
            </span>
          @endif
        </a>
        @if (isset($menu['children_group']) && $menu['children_group'])
          <div class="dropdown-menu {{ $menu['isFull'] ? 'w-100' : '' }}"
            style="min-width: {{ count($menu['children_group']) * 240 }}px">
            <div class="card card-lg">
              <div class="card-body">
                <div class="container">
                  <div class="row">
                    @forelse ($menu['children_group'] as $group)
                      <div class="col-6 col-md">
                        @if ($group['name'])
                          <div class="mb-3 fw-bold group-name">{{ $group['name'] }}</div>
                        @endif
                        @if ($group['type'] == 'image')
                          <a
                          target="{{ isset($group['image']['link']['new_window']) && $group['image']['link']['new_window'] ? '_blank' : '_self' }}"
                          href="{{ $group['image']['link'] ?: 'javascript:void(0)' }}"><img src="{{ $group['image']['image'] }}"
                              class="img-fluid"></a>
                        @else
                          <ul class="nav flex-column ul-children">
                            @foreach ($group['children'] as $children)
                              @if (!is_array($children['link']['text']) && $children['link']['text'])
                                <li class="nav-item">
                                  <a
                                  target="{{ isset($children['link']['new_window']) && $children['link']['new_window'] ? '_blank' : '_self' }}"
                                  class="nav-link px-0"
                                    href="{{ $children['link']['link'] ?: 'javascript:void(0)' }}">{{ $children['link']['text'] }}</a>
                                </li>
                              @endif
                            @endforeach
                          </ul>
                        @endif
                      </div>
                    @endforeach
                  </div>
                </div>
              </div>
            </div>
          </div>
        @endif
      </li>
    @endif
  @endforeach
  @hook('header.menu.after')
</ul>

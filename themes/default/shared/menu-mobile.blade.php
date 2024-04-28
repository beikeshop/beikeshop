<div class="accordion accordion-flush" id="menu-accordion">
  @hook('header.menu.mobile.before')
  @foreach ($menu_content as $key => $menu)
    @if ($menu['name'])
      <div class="accordion-item">
        <div class="nav-item-text">
          <a class="nav-link" target="{{ isset($menu['new_window']) && $menu['new_window'] ? '_blank' : '_self' }}" href="{{ $menu['link'] ?: '#flush-menu-' . $key }}" data-bs-toggle="{{  !$menu['link'] ? 'collapse' : ''}}">
            {{ $menu['name'] }}
            @if (isset($menu['badge']) && $menu['badge']['name'])
            <span class="badge" style="background-color: {{ $menu['badge']['bg_color'] }}; color: {{ $menu['badge']['text_color'] }}; border-color: {{ $menu['badge']['bg_color'] }}">
              {{ $menu['badge']['name'] }}
            </span>
            @endif
          </a>
          @if (isset($menu['children_group']) && $menu['children_group'])
          <span class="collapsed" data-bs-toggle="collapse" data-bs-target="#flush-menu-{{ $key }}"><i class="bi bi-chevron-down"></i></span>
          @endif
        </div>
        @if (isset($menu['children_group']) && $menu['children_group'])
        <div class="accordion-collapse collapse" id="flush-menu-{{ $key }}" data-bs-parent="#menu-accordion">
          @forelse ($menu['children_group'] as $c_key => $group)
          <div class="children-group">
            @if ($group['name'])
            <div class="d-flex justify-content-between align-items-center children-title" data-bs-toggle="collapse" data-bs-target="#children-menu-{{ $key }}-{{ $c_key }}">
              <div>{{ $group['name'] }}</div>
              @if ($group['children'])
              <span class="collapsed" data-bs-toggle="collapse" data-bs-target="#children-menu-{{ $key }}-{{ $c_key }}"><i class="bi bi-plus-lg"></i></span>
              @endif
            </div>
            @endif
            <div class="accordion-collapse collapse {{ !$group['name'] ? 'd-block' : '' }}" id="children-menu-{{ $key }}-{{ $c_key }}" data-bs-parent="#flush-menu-{{ $key }}">
              @if ($group['type'] == 'image')
              <a target="{{ isset($group['image']['link']['new_window']) && $group['image']['link']['new_window'] ? '_blank' : '_self' }}" href="{{ $group['image']['link'] }}"><img src="{{ $group['image']['image'] }}" class="img-fluid"></a>
              @else
              <ul class="nav flex-column ul-children">
                @foreach ($group['children'] as $children)
                @if (!is_array($children['link']['text']) && $children['link']['text'])
                <li class="nav-item">
                  <a target="{{ isset($children['link']['new_window']) && $children['link']['new_window'] ? '_blank' : '_self' }}" class="nav-link px-0" href="{{ $children['link']['link'] }}">{{ $children['link']['text'] }}</a>
                </li>
                @endif
                @endforeach
              </ul>
              @endif
            </div>
          </div>
          @endforeach
        </div>
        @endif
      </div>
    @endif
  @endforeach
  @hook('header.menu.mobile.after')
</div>

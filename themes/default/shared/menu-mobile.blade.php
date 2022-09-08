<div class="accordion accordion-flush" id="menu-accordion">
  @foreach ($menu_content as $key => $menu)
    <div class="accordion-item">
      <div class="nav-item-text">
        <a class="nav-link"
          target="{{ isset($menu['new_window']) && $menu['new_window'] ? '_blank' : '_self' }}"
          href="{{ $menu['link'] ?? '' }}">
          {{ $menu['name'] }}
          @if (isset($menu['badge']) && $menu['badge']['name'])
            <span class="badge"
              style="background-color: {{ $menu['badge']['bg_color'] }}; color: {{ $menu['badge']['text_color'] }}; border-color: {{ $menu['badge']['bg_color'] }}">
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
          @forelse ($menu['children_group'] as $group)
            <div class="children-group mb-3">
              @if ($group['name'])
                <div class="group-name">{{ $group['name'] }}</div>
              @endif
              @if ($group['type'] == 'image')
                <a
                target="{{ isset($group['image']['link']['new_window']) && $group['image']['link']['new_window'] ? '_blank' : '_self' }}"
                href="{{ $group['image']['link'] }}"><img src="{{ $group['image']['image'] }}"
                    class="img-fluid"></a>
              @else
                <ul class="nav flex-column ul-children">
                  @foreach ($group['children'] as $children)
                    @if (!is_array($children['link']['text']) && $children['link']['text'])
                      <li class="nav-item">
                        <a
                        target="{{ isset($children['link']['new_window']) && $children['link']['new_window'] ? '_blank' : '_self' }}"
                        class="nav-link px-0"
                          href="{{ $children['link']['link'] }}">{{ $children['link']['text'] }}</a>
                      </li>
                    @endif
                  @endforeach
                </ul>
              @endif
            </div>
          @endforeach
        </div>
      @endif
      </div>
  @endforeach
  </div>
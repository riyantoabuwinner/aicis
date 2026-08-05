@foreach($items as $item)
    @if($item->children->count() > 0)
        <div class="menu-dropdown">
            <a href="{{ $item->url ? url($item->url) : '#' }}" class="dropdown-toggle {{ request()->is(ltrim($item->url, '/')) ? 'active' : '' }}">
                {{ $item->title }} <i class="fas fa-chevron-down" style="font-size: 0.7em; margin-left: 4px;"></i>
            </a>
            <div class="menu-dropdown-content">
                @include('components.menu-items', ['items' => $item->children])
            </div>
        </div>
    @else
        <a href="{{ $item->url ? url($item->url) : '#' }}" class="{{ request()->is(ltrim($item->url, '/')) ? 'active' : '' }}">
            {{ $item->title }}
        </a>
    @endif
@endforeach

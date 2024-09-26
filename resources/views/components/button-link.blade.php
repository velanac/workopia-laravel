@props([
    'url' => '/',
    'icon' => null,
    'bgClass' => 'bg-yellow-500',
    'hoverClass' => 'hover:bg-yellow-600',
    'textClass' => 'text-black',
    'block' => false,
])

<a href="{{ url($url) }}"
    class="{{ $bgClass }} {{ $hoverClass }} {{ $textClass }} {{ $block ? 'block' : '' }} 
    px-4 py-2 rounded hover:shadow-md transition duration-300">
    @if ($icon)
        <i class="fa fa-{{ $icon }}"></i>
    @endif
    {{ $slot }}
</a>

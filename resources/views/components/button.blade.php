@props(['type' => 'button', 'variant' => 'primary'])

<button type="{{ $type }}" {{ $attributes->merge(['class' => 'btn-' . $variant]) }}>
    {{ $slot }}
</button>

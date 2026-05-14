@props(['label', 'value', 'icon' => ''])

<div {{ $attributes->merge(['class' => 'glass stat-card']) }}>
    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
        <div>
            <p class="stat-label">{{ $label }}</p>
            <h3 class="stat-value">{{ $value }}</h3>
        </div>
        @if($icon)
            <div style="width: 45px; height: 45px; border-radius: 12px; background: var(--accent-glow); display: flex; align-items: center; justify-content: center;">
                <i class="{{ $icon }}" style="color: var(--accent-primary); font-size: 1.2rem;"></i>
            </div>
        @endif
    </div>
</div>

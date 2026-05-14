@props(['label' => '', 'name' => '', 'type' => 'text', 'placeholder' => ''])

<div class="input-group" style="margin-bottom: 1.5rem;">
    @if($label)
        <label for="{{ $name }}" style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--text-dim); margin-bottom: 0.5rem;">{{ $label }}</label>
    @endif
    <input 
        type="{{ $type }}" 
        id="{{ $name }}" 
        name="{{ $name }}" 
        placeholder="{{ $placeholder }}"
        {{ $attributes->merge(['class' => 'input-glass']) }}
    >
</div>

@extends('layout.admin')

@section('content')
<div class="container-fluid">
    <div class="page-header animate-fade">
        <div>
            <h1 class="page-title">System Settings</h1>
            <p class="page-subtitle">Configure application-wide parameters and financial rules.</p>
        </div>
    </div>

    <form action="{{ route('settings.update') }}" method="POST" class="animate-fade">
        @csrf
        <div class="style-ba1ada">
            @foreach($settings as $group => $items)
            <div class="table-container glass style-46105d">
                <h2 class="style-a61c4c">
                    {{ $group }} Settings
                </h2>
                
                <div class="style-fc40eb">
                    @foreach($items as $setting)
                    <div class="form-group">
                        <label for="{{ $setting->key }}"  class="style-d4bdca">
                            {{ $setting->label }}
                        </label>
                        <input type="text" 
                               name="{{ $setting->key }}" 
                               id="{{ $setting->key }}" 
                               value="{{ $setting->value }}" 
                               class="form-control glass style-423751"
                               >
                        @if($setting->key == 'commission_rate')
                        <small class="style-ad8938">This percentage is deducted from each ride's total fare.</small>
                        @endif
                        @if($setting->key == 'vehicle_types')
                        <small class="style-ad8938">Separate multiple types with commas (e.g. Sedan, SUV, Luxury).</small>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach

            <div class="style-6e8e8a">
                <button type="submit" class="btn-primary style-fa434d">
                    <i class="fa-solid fa-floppy-disk"></i> Save All Settings
                </button>
            </div>
        </div>
    </form>
</div>

<link rel="stylesheet" href="{{ asset('css/dashboardview/settings/index.css') }}">
@endsection

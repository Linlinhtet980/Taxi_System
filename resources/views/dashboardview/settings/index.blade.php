@extends('layout.admin')

@section('content')
<div class="container-fluid">
    <div class="page-header animate-fade">
        <div>
            <h1 class="page-title">System Settings</h1>
            <p class="page-subtitle">Configure application-wide parameters and loyalty rules.</p>
        </div>
    </div>

    <!-- Setting Tabs Navigation -->
    <div class="settings-tabs glass animate-fade">
        @foreach($settings as $group => $items)
        <button class="tab-btn {{ $loop->first ? 'active' : '' }}" onclick="showTab('{{ $group }}', this)">
            {{ ucfirst($group) }} Settings
        </button>
        @endforeach
    </div>

    <form action="{{ route('settings.update') }}" method="POST" class="animate-fade">
        @csrf
        <div class="settings-content">
            @foreach($settings as $group => $items)
            <div id="tab-{{ $group }}" class="tab-content glass {{ $loop->first ? 'active' : '' }}">
                <h2 class="tab-title">
                    <i class="fa-solid fa-sliders mr-2"></i> {{ ucfirst($group) }} Configuration
                </h2>
                
                <div class="settings-grid">
                    @foreach($items as $setting)
                    <div class="form-group">
                        <label for="{{ $setting->key }}" class="setting-label">
                            {{ $setting->label }}
                        </label>
                        <input type="text" 
                               name="{{ $setting->key }}" 
                               id="{{ $setting->key }}" 
                               value="{{ $setting->value }}" 
                               class="form-control"
                               >
                        @if($setting->key == 'commission_rate')
                        <small class="helper-text">This percentage is deducted from each ride's total fare.</small>
                        @endif
                        @if($setting->key == 'vehicle_types')
                        <small class="helper-text">Separate multiple types with commas (e.g. Sedan, SUV, Luxury).</small>
                        @endif
                        @if($setting->key == 'point_earning_ratio_cash' || $setting->key == 'point_earning_ratio_digital')
                        <small class="helper-text">Points awarded for every 1,000 MMK spent.</small>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach

            <div class="save-bar glass animate-fade">
                <p class="save-info"><i class="fa-solid fa-circle-info mr-1"></i> Changes will apply to the entire system immediately.</p>
                <button type="submit" class="btn-primary">
                    <i class="fa-solid fa-floppy-disk mr-2"></i> Save Changes
                </button>
            </div>
        </div>
    </form>
</div>

<style>
    .settings-tabs {
        display: flex;
        gap: 10px;
        padding: 10px;
        margin-bottom: 25px;
        border-radius: 15px;
        overflow-x: auto;
    }
    .tab-btn {
        background: transparent;
        border: none;
        color: var(--text-dim);
        padding: 12px 25px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        transition: 0.3s;
        white-space: nowrap;
    }
    .tab-btn:hover { background: rgba(255, 255, 255, 0.05); color: white; }
    .tab-btn.active {
        background: var(--accent-purple);
        color: white;
        box-shadow: 0 4px 15px rgba(168, 85, 247, 0.3);
    }

    .tab-content { display: none; padding: 30px; border-radius: 20px; }
    .tab-content.active { display: block; animation: slideUp 0.4s ease-out; }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .tab-title { font-size: 18px; font-weight: 800; color: white; margin-bottom: 25px; }
    .settings-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 25px;
    }
    .setting-label { display: block; color: var(--text-dim); font-size: 12px; font-weight: 700; text-transform: uppercase; margin-bottom: 10px; letter-spacing: 1px; }
    .helper-text { display: block; color: var(--text-dim); font-size: 11px; margin-top: 8px; opacity: 0.7; }

    .form-control {
        width: 100%;
        background: rgba(255, 255, 255, 0.05) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        border-radius: 12px;
        padding: 12px 15px;
        color: white !important;
        font-size: 14px;
        transition: 0.3s;
    }
    .form-control:focus {
        border-color: var(--accent-purple) !important;
        background: rgba(255, 255, 255, 0.1) !important;
        outline: none;
    }

    .save-bar {
        position: sticky;
        bottom: 20px;
        margin-top: 30px;
        padding: 20px 30px;
        border-radius: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    .save-info { color: var(--text-dim); font-size: 13px; font-weight: 500; }

    @media (max-width: 992px) {
        .settings-grid { grid-template-columns: 1fr; }
    }
</style>

<script>
    function showTab(groupId, btn) {
        // Hide all contents
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.remove('active');
        });
        
        // Remove active from all buttons
        document.querySelectorAll('.tab-btn').forEach(b => {
            b.classList.remove('active');
        });
        
        // Show target content
        document.getElementById('tab-' + groupId).classList.add('active');
        
        // Mark button as active
        btn.classList.add('active');
    }
</script>
@endsection

@extends('layout.admin')

@push('css')
<style>
    .form-container { padding: 20px; max-width: 800px; }
    .page-header { margin-bottom: 30px; }
    .page-header h1 { font-size: 24px; font-weight: 800; color: white; margin-bottom: 5px; }
    .page-header p { color: var(--text-dim); font-size: 14px; }
    
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--accent-purple);
        text-decoration: none;
        font-weight: 700;
        font-size: 14px;
        margin-bottom: 20px;
        transition: 0.3s;
    }
    .back-link:hover { opacity: 0.8; transform: translateX(-5px); }

    .glass-card {
        background: rgba(255, 255, 255, 0.03);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 24px;
        padding: 40px;
    }

    .form-group { margin-bottom: 25px; }
    .form-label {
        display: block;
        color: var(--text-dim);
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 10px;
        letter-spacing: 0.5px;
    }
    
    .form-control {
        width: 100%;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 15px 20px;
        color: white;
        font-size: 15px;
        font-family: inherit;
        transition: 0.3s;
    }
    .form-control:focus {
        outline: none;
        border-color: var(--accent-purple);
        background: rgba(255, 255, 255, 0.08);
        box-shadow: 0 0 0 4px rgba(168, 85, 247, 0.1);
    }
    
    .grid-cols-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }

    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
    }
    .checkbox-group input { width: 18px; height: 18px; cursor: pointer; accent-color: var(--accent-purple); }
    .checkbox-group label { color: white; font-weight: 600; font-size: 14px; cursor: pointer; }

    .btn-submit {
        background: linear-gradient(135deg, #a855f7, #6366f1);
        color: white;
        border: none;
        padding: 18px;
        border-radius: 15px;
        font-weight: 800;
        font-size: 16px;
        width: 100%;
        cursor: pointer;
        box-shadow: 0 10px 20px rgba(168, 85, 247, 0.2);
        transition: 0.3s;
        margin-top: 10px;
    }
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 25px rgba(168, 85, 247, 0.3);
    }

    @media (max-width: 600px) {
        .grid-cols-2 { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="form-container">
    <a href="{{ route('point-rewards.index') }}" class="back-link">
        <i class="fa-solid fa-arrow-left"></i> Back to List
    </a>

    <div class="page-header">
        <h1>Add New Point Reward</h1>
        <p>Define a new exchange option for your customers.</p>
    </div>

    <div class="glass-card">
        <form action="{{ route('point-rewards.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label class="form-label">Reward Title</label>
                <input type="text" name="title" class="form-control" placeholder="e.g., 5,000 MMK Wallet Balance" required>
            </div>

            <div class="grid-cols-2">
                <div class="form-group">
                    <label class="form-label">Points Required</label>
                    <input type="number" name="points_required" class="form-control" placeholder="500" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Reward Amount (Ks)</label>
                    <input type="number" name="reward_amount" class="form-control" placeholder="5000" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="4" placeholder="Briefly describe this reward..."></textarea>
            </div>

            <div class="form-group">
                <div class="checkbox-group">
                    <input type="checkbox" name="is_active" id="is_active" value="1" checked>
                    <label for="is_active">Enable this reward immediately</label>
                </div>
            </div>

            <button type="submit" class="btn-submit">
                <i class="fa-solid fa-plus mr-2"></i> Create Reward
            </button>
        </form>
    </div>
</div>
@endsection

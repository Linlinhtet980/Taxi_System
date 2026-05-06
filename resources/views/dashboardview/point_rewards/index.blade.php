@extends('layout.admin')

@push('css')
<style>
    .point-rewards-container { padding: 20px; }
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }
    .header-title h1 { font-size: 24px; font-weight: 800; margin-bottom: 5px; color: white; }
    .header-title p { color: var(--text-dim); font-size: 14px; }
    
    .rewards-card {
        background: rgba(255, 255, 255, 0.03);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 20px;
        overflow: hidden;
    }

    .table-responsive { width: 100%; overflow-x: auto; }
    
    table { width: 100%; border-collapse: collapse; }
    th {
        text-align: left;
        padding: 20px;
        color: var(--text-dim);
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }
    td {
        padding: 20px;
        color: white;
        font-size: 14px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.03);
        vertical-align: middle;
    }
    
    .reward-id { color: var(--accent-purple); font-weight: 700; font-size: 12px; }
    .reward-title { font-weight: 600; color: white; margin-bottom: 4px; display: block; }
    .reward-desc { color: var(--text-dim); font-size: 12px; }
    
    .badge-pts { background: rgba(168, 85, 247, 0.1); color: #a855f7; padding: 6px 12px; border-radius: 8px; font-weight: 700; font-size: 12px; border: 1px solid rgba(168, 85, 247, 0.2); }
    .badge-ks { background: rgba(34, 197, 94, 0.1); color: #22c55e; padding: 6px 12px; border-radius: 8px; font-weight: 700; font-size: 12px; border: 1px solid rgba(34, 197, 94, 0.2); }
    
    .status-pill {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }
    .status-active { background: rgba(34, 197, 94, 0.1); color: #22c55e; }
    .status-inactive { background: rgba(239, 68, 68, 0.1); color: #ef4444; }

    .action-btns { display: flex; gap: 8px; justify-content: flex-end; }
    .btn-action {
        width: 36px; height: 36px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        text-decoration: none; transition: 0.3s; border: 1px solid rgba(255, 255, 255, 0.05);
    }
    .btn-edit { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .btn-edit:hover { background: #3b82f6; color: white; }
    .btn-delete { background: rgba(239, 68, 68, 0.1); color: #ef4444; border: none; cursor: pointer; }
    .btn-delete:hover { background: #ef4444; color: white; }

    .btn-add {
        background: linear-gradient(135deg, #a855f7, #6366f1);
        color: white; padding: 12px 24px; border-radius: 12px;
        text-decoration: none; font-weight: 700; font-size: 14px;
        box-shadow: 0 4px 15px rgba(168, 85, 247, 0.3); transition: 0.3s;
    }
    .btn-add:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(168, 85, 247, 0.4); }
</style>
@endpush

@section('content')
<div class="point-rewards-container">
    <div class="page-header">
        <div class="header-title">
            <h1>Point Rewards Management</h1>
            <p>Manage the list of rewards available for point exchange.</p>
        </div>
        <a href="{{ route('point-rewards.create') }}" class="btn-add">
            <i class="fa-solid fa-plus mr-2"></i> Add New Reward
        </a>
    </div>

    @if(session('success'))
    <div style="background: rgba(34, 197, 94, 0.1); color: #22c55e; padding: 15px; border-radius: 12px; border: 1px solid rgba(34, 197, 94, 0.2); margin-bottom: 20px;">
        <i class="fa-solid fa-check-circle mr-2"></i> {{ session('success') }}
    </div>
    @endif

    <div class="rewards-card">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title & Description</th>
                        <th>Points Required</th>
                        <th>Reward (MMK)</th>
                        <th>Status</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rewards as $reward)
                    <tr>
                        <td class="reward-id">#{{ str_pad($reward->id, 3, '0', STR_PAD_LEFT) }}</td>
                        <td>
                            <span class="reward-title">{{ $reward->title }}</span>
                            <span class="reward-desc">{{ Str::limit($reward->description, 50) }}</span>
                        </td>
                        <td><span class="badge-pts">{{ number_format($reward->points_required) }} Pts</span></td>
                        <td><span class="badge-ks">{{ number_format($reward->reward_amount) }} Ks</span></td>
                        <td>
                            <span class="status-pill {{ $reward->is_active ? 'status-active' : 'status-inactive' }}">
                                {{ $reward->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <div class="action-btns">
                                <a href="{{ route('point-rewards.edit', $reward->id) }}" class="btn-action btn-edit" title="Edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('point-rewards.destroy', $reward->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this reward?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-action btn-delete" title="Delete">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 40px; color: var(--text-dim);">
                            <i class="fa-solid fa-gift d-block mb-3" style="font-size: 30px; opacity: 0.2;"></i>
                            No rewards found in the list.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <div style="margin-top: 20px;">
        {{ $rewards->links() }}
    </div>
</div>
@endsection

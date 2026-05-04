@extends('layout.admin')

@section('content')
<div class="container-fluid">
    <div class="page-header animate-fade">
        <div>
            <h1 class="page-title">Withdrawal Requests</h1>
            <p class="page-subtitle">Manage payout requests from drivers.</p>
        </div>
    </div>

    <div class="table-container glass animate-fade">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Driver</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Requested At</th>
                    <th>Status</th>
                    <th class="style-cdd8ca">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($withdrawals as $w)
                <tr>
                    <td><span class="id-badge">DRV-{{ str_pad($w->driver->id, 4, '0', STR_PAD_LEFT) }}</span></td>
                    <td>
                        <div class="style-df2b77">
                            <img src="{{ $w->driver->profile_picture ? asset('storage/' . $w->driver->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($w->driver->full_name) . '&background=a855f7&color=fff' }}" 
                                  class="style-76290e">
                            <div>
                                <div class="style-1aecdc">{{ $w->driver->full_name }}</div>
                                <div class="style-831dee">{{ $w->driver->license_no }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="style-1f7302">{{ number_format($w->amount) }} MMK</div>
                    </td>
                    <td>
                        <span class="style-30f144">{{ $w->payment_method }}</span>
                    </td>
                    <td>
                        <div class="style-e7992d">{{ $w->created_at->format('d M Y') }}</div>
                        <div class="style-fbd666">{{ $w->created_at->format('h:i A') }}</div>
                    </td>
                    <td>
                        @php
                            $colors = ['pending' => '#fbbf24', 'approved' => '#4ade80', 'rejected' => '#f43f5e'];
                            $color = $colors[$w->status] ?? '#94a3b8';
                        @endphp
                        <span  style="background: {{ $color }}20; color: {{ $color }}; padding: 5px 12px; border-radius: 50px; font-size: 0.75rem; font-weight: 700; border: 1px solid {{ $color }}40; text-transform: uppercase;">
                            {{ $w->status }}
                        </span>
                    </td>
                    <td>
                        @if($w->status == 'pending')
                        <div class="style-cc8ca4">
                            <!-- Approve Modal Trigger -->
                            <button onclick="document.getElementById('approve-modal-{{ $w->id }}').style.display='flex'" class="action-btn-success" title="Approve Request">
                                <i class="fa-solid fa-check"></i>
                            </button>
                            
                            <button onclick="document.getElementById('reject-modal-{{ $w->id }}').style.display='flex'" class="btn-icon style-baf106" title="Reject" >
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>

                        <!-- Approve Modal -->
                        <div id="approve-modal-{{ $w->id }}"  class="style-2f5608">
                            <div class="glass style-315c37">
                                <h3 class="style-c6641d">
                                    <i class="fa-solid fa-circle-check style-a5e3c2"></i> Confirm Approval
                                </h3>
                                <p class="style-75c22c">
                                    Confirm payment of <strong>{{ number_format($w->amount) }} MMK</strong> to <strong>{{ $w->driver->full_name }}</strong> via <strong>{{ strtoupper($w->payment_method) }}</strong>.
                                </p>
                                <form action="{{ route('admin.withdrawals.approve', $w->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="style-7002f9">
                                        <label class="style-378c81">Upload Payment Receipt (Optional)</label>
                                        <div class="glass style-093cd6">
                                            <input type="file" name="screenshot" id="ss-{{ $w->id }}" accept="image/*"  class="style-daa30f">
                                            <i class="fa-solid fa-cloud-arrow-up style-4f769f"></i>
                                            <div id="fn-{{ $w->id }}" class="style-d9a249">Click or drag receipt here</div>
                                        </div>
                                    </div>
                                    <div class="style-0ed0e9">
                                        <button type="button" onclick="document.getElementById('approve-modal-{{ $w->id }}').style.display='none'" class="btn-secondary style-49cdf8" >Cancel</button>
                                        <button type="submit" class="btn-primary style-896acc">Complete Payment</button>
                                    </div>
                                </form>
                                <script>
                                    document.getElementById('ss-{{ $w->id }}').addEventListener('change', function(e) {
                                        if(this.files && this.files[0]) {
                                            document.getElementById('fn-{{ $w->id }}').innerText = this.files[0].name;
                                            document.getElementById('fn-{{ $w->id }}').style.color = '#4ade80';
                                        }
                                    });
                                </script>
                            </div>
                        </div>

                        <!-- Reject Modal -->
                        <div id="reject-modal-{{ $w->id }}"  class="style-2f5608">
                            <div class="glass style-6ff9b2">
                                <h3 class="style-7002f9">Reject Request</h3>
                                <form action="{{ route('admin.withdrawals.reject', $w->id) }}" method="POST">
                                    @csrf
                                    <textarea name="reason" placeholder="Reason for rejection..." class="style-46e6db"></textarea>
                                    <div class="style-0ed0e9">
                                        <button type="button" onclick="document.getElementById('reject-modal-{{ $w->id }}').style.display='none'" class="btn-secondary style-49cdf8" >Cancel</button>
                                        <button type="submit" class="btn-primary style-d08d8a">Reject</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @else
                        <div class="style-cdd8ca">
                             <span class="style-0b4371">Processed</span>
                             @if($w->screenshot)
                                <a href="{{ asset('storage/' . $w->screenshot) }}" target="_blank"  class="style-28eb31">View Receipt</a>
                             @endif
                        </div>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="style-4a02cd">No withdrawal requests found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="style-194b57">
        {{ $withdrawals->links() }}
    </div>
</div>
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('css/dashboardview/finance/withdrawals.css') }}">
@endpush


<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Core\Withdrawal;
use App\Models\Auth\Driver;
use App\Models\Core\Notification as NotificationModel;
use App\Models\Core\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class WithdrawalController extends Controller
{
    public function index()
    {
        $withdrawals = Withdrawal::query()->with('driver')->latest()->paginate(15);
        return view('dashboardview.finance.withdrawals', compact('withdrawals'));
    }

    public function approve(Request $request, int $id)
    {
        $withdrawal = Withdrawal::query()->findOrFail($id);
        
        if ($withdrawal->status != 'pending') {
            return back()->with('error', 'This request has already been processed.');
        }

        $driver = $withdrawal->driver;
        
        if ($driver->wallet_balance < $withdrawal->amount) {
            return back()->with('error', 'Driver has insufficient balance.');
        }

        $screenshotPath = null;
        if ($request->hasFile('screenshot')) {
            $screenshotPath = $request->file('screenshot')->store('withdrawals', 'public');
        }

        DB::beginTransaction();
        try {
            $driver->wallet_balance -= $withdrawal->amount;
            $driver->save();

            $withdrawal->update([
                'status' => 'approved',
                'screenshot' => $screenshotPath
            ]);

            // Create financial record
            Transaction::create([
                'driver_id' => $driver->id,
                'amount' => $withdrawal->amount,
                'payment_method' => 'Digital',
                'type' => 'Withdrawal',
                'note' => "Withdrawal request #{$withdrawal->id} approved.",
                'status' => 'Completed',
                'reference_number' => $withdrawal->id
            ]);

            // Automatically reject other identical pending requests
            Withdrawal::query()->where('driver_id', $driver->id)
                ->where('status', 'pending')
                ->where('amount', $withdrawal->amount)
                ->where('id', '!=', $withdrawal->id)
                ->update([
                    'status' => 'rejected',
                    'notes' => 'Duplicate request identified after manual approval.'
                ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Approval failed: ' . $e->getMessage());
        }

        // Notify Driver
        NotificationModel::send(
            'Withdrawal Success! 💰',
            "Your withdrawal of " . number_format($withdrawal->amount) . " MMK has been processed. Tap to see receipt.",
            'success',
            route('driver.withdrawal.detail', ['id' => $driver->id, 'withdrawalId' => $withdrawal->id]),
            $driver
        );

        return back()->with('success', 'Withdrawal approved and driver notified.');
    }

    public function reject(Request $request, int $id)
    {
        $withdrawal = Withdrawal::query()->findOrFail($id);
        $withdrawal->update([
            'status' => 'rejected',
            'notes' => $request->reason
        ]);

        return back()->with('success', 'Withdrawal request rejected.');
    }
}

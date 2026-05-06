<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Core\PointReward;
use App\Models\Core\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PointController extends Controller
{
    public function index()
    {
        $customer = Auth::guard('customer')->user();
        $rewards = PointReward::query()->where('is_active', true)->get();
        return view('customerview.points_exchange', compact('customer', 'rewards'));
    }

    public function exchange(Request $request, int $rewardId)
    {
        $customer = Auth::guard('customer')->user();
        $reward = PointReward::query()->findOrFail($rewardId);

        if ($customer->loyalty_points < $reward->points_required) {
            return back()->with('error', 'အမှတ် မလုံလောက်ပါ။');
        }

        DB::beginTransaction();
        try {
            // Deduct points
            $customer->decrement('loyalty_points', (int)$reward->points_required, []);
            
            // Add wallet balance
            $customer->increment('wallet_balance', (float)$reward->reward_amount, []);

            // Record transaction
            Transaction::create([
                'user_id' => $customer->id,
                'user_type' => 'customer',
                'amount' => $reward->reward_amount,
                'type' => 'deposit',
                'description' => "Exchanged {$reward->points_required} points for {$reward->reward_amount} Ks wallet balance.",
                'status' => 'completed',
            ]);

            DB::commit();
            return back()->with('success', 'လဲလှယ်မှု အောင်မြင်ပါသည်။ Wallet ထဲသို့ ငွေထည့်သွင်းပြီးပါပြီ။');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'အမှားအယွင်း တစ်ခု ဖြစ်ပွားခဲ့သည်။');
        }
    }
}

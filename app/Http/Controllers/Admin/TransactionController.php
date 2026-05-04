<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Core\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::query()->with(['booking', 'driver', 'customer'])->latest()->paginate(10);
        
        // Quick stats
        $totalRevenue = Transaction::query()->where('status', 'Completed')->where('type', 'Ride Fare')->sum('amount');
        $totalCommission = Transaction::query()->where('status', 'Completed')->sum('commission_amount');
        $totalPayouts = Transaction::query()->where('status', 'Completed')->where('type', 'Withdrawal')->sum('amount');

        return view('dashboardview.transaction.index', compact('transactions', 'totalRevenue', 'totalCommission', 'totalPayouts'));
    }

    public function show(Transaction $transaction)
    {
        return view('dashboardview.transaction.show', compact('transaction'));
    }
}

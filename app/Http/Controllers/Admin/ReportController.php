<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Core\Transaction;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function exportTransactionsCsv()
    {
        $transactions = Transaction::with(['driver', 'customer', 'booking'])->latest()->get();
        
        $filename = "transactions_report_" . date('Y-m-d_H-i-s') . ".csv";
        
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['ID', 'Date', 'Type', 'Booking ID', 'Driver', 'Customer', 'Amount (MMK)', 'Commission', 'Net Driver', 'Method', 'Status'];

        $callback = function() use ($transactions, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($transactions as $tx) {
                fputcsv($file, [
                    $tx->id,
                    $tx->created_at->format('Y-m-d H:i'),
                    $tx->type,
                    $tx->booking_id ?? 'N/A',
                    $tx->driver?->full_name ?? 'N/A',
                    $tx->customer?->name ?? 'N/A',
                    $tx->amount,
                    $tx->commission_amount,
                    $tx->driver_amount,
                    $tx->payment_method,
                    $tx->status,
                ]);
            }

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    public function printTransactions()
    {
        $transactions = Transaction::with(['driver', 'customer', 'booking'])->latest()->get();
        return view('dashboardview.reports.transactions_print', compact('transactions'));
    }
}


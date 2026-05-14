<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\BranchStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $branchId = auth()->user()->branch_id;
        $month    = $request->get('month', now()->month);
        $year     = $request->get('year', now()->year);

        $transactions = Transaction::where('branch_id', $branchId)
                                   ->whereMonth('created_at', $month)
                                   ->whereYear('created_at', $year)
                                   ->with('kasir', 'items', 'promotion')
                                   ->latest()
                                   ->get();

        $summary = [
            'total_income'       => $transactions->where('status', 'completed')->sum('total'),
            'total_transactions' => $transactions->where('status', 'completed')->count(),
            'cancelled'          => $transactions->where('status', 'cancelled')->count(),
        ];

        return view('admin.transactions.index',
            compact('transactions', 'summary', 'month', 'year'));
    }

    // Batalkan transaksi — hanya jika masih pending
    public function cancel(Request $request, Transaction $transaction)
    {
        $request->validate([
            'cancel_reason' => 'required|string|min:5',
        ]);

        if ($transaction->status !== 'pending') {
            return back()->with('error',
                'Transaksi tidak dapat dibatalkan karena sudah ' . $transaction->status . '!');
        }

        DB::transaction(function () use ($request, $transaction) {
            // Kembalikan stok
            foreach ($transaction->items as $item) {
                BranchStock::where('branch_id', $transaction->branch_id)
                           ->where('menu_id', $item->menu_id)
                           ->increment('stock', $item->quantity);
            }

            $transaction->update([
                'status'        => 'cancelled',
                'cancel_reason' => $request->cancel_reason,
                'cancelled_by'  => auth()->id(),
                'cancelled_at'  => now(),
            ]);
        });

        return back()->with('success', 'Transaksi berhasil dibatalkan & stok dikembalikan!');
    }
}
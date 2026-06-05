<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Transaction;
use App\Models\Expense;
use App\Models\StockRequest;
use Illuminate\Http\Request;
use DateTime;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $month    = $request->get('month', now()->month);
        $year     = $request->get('year', now()->year);
        $branchId = $request->get('branch_id');

        // ── Data Grafik Pemasukan 12 Bulan Terakhir ──────────
        $incomeChart = [];
        $expenseChart = [];
        $labels = [];

        for ($m = 1; $m <= 12; $m++) {
            $labels[] = \DateTime::createFromFormat('!m', $m)->format('M');

            $q = Transaction::where('status', 'completed')
                            ->whereYear('created_at', $year)
                            ->whereMonth('created_at', $m);

            $qe = Expense::where('status', 'verified')
                         ->whereYear('expense_date', $year)
                         ->whereMonth('expense_date', $m);

            if ($branchId) {
                $q->where('branch_id', $branchId);
                $qe->where('branch_id', $branchId);
            }

            $incomeChart[]  = (float) $q->sum('total');
            $expenseChart[] = (float) $qe->sum('amount');
        }

        // ── Summary Bulan Ini ─────────────────────────────────
        $incomeQuery = Transaction::where('status', 'completed')
                                  ->whereMonth('created_at', $month)
                                  ->whereYear('created_at', $year);

        $expenseQuery = Expense::where('status', 'verified')
                               ->whereMonth('expense_date', $month)
                               ->whereYear('expense_date', $year);

        if ($branchId) {
            $incomeQuery->where('branch_id', $branchId);
            $expenseQuery->where('branch_id', $branchId);
        }

        $totalIncome      = $incomeQuery->sum('total');
        $totalExpense     = $expenseQuery->sum('amount');
        $totalProfit      = $totalIncome - $totalExpense;
        $totalTransaction = $incomeQuery->count();

        // ── Performa Per Cabang ───────────────────────────────
        $branchPerformance = Branch::where('status', 'active')
            ->get()
            ->map(function ($branch) use ($month, $year) {
                $income  = Transaction::where('branch_id', $branch->id)
                                      ->where('status', 'completed')
                                      ->whereMonth('created_at', $month)
                                      ->whereYear('created_at', $year)
                                      ->sum('total');

                $expense = Expense::where('branch_id', $branch->id)
                                  ->where('status', 'verified')
                                  ->whereMonth('expense_date', $month)
                                  ->whereYear('expense_date', $year)
                                  ->sum('amount');

                $trxCount = Transaction::where('branch_id', $branch->id)
                                       ->where('status', 'completed')
                                       ->whereMonth('created_at', $month)
                                       ->whereYear('created_at', $year)
                                       ->count();

                return [
                    'name'    => $branch->name,
                    'income'  => $income,
                    'expense' => $expense,
                    'profit'  => $income - $expense,
                    'trx'     => $trxCount,
                ];
            })
            ->sortByDesc('income');

        $branches = Branch::where('status', 'active')->get();

        $transactions = Transaction::where('status', 'completed')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->with('branch', 'kasir', 'items', 'promotion')
            ->when($branchId, fn ($query) => $query->where('branch_id', $branchId))
            ->latest()
            ->get();

        return view('manager.reports.index', compact(
            'month', 'year', 'branchId', 'branches',
            'labels', 'incomeChart', 'expenseChart',
            'totalIncome', 'totalExpense', 'totalProfit', 'totalTransaction',
            'branchPerformance', 'transactions'
        ));
    }

    public function export(Request $request)
    {
        $month    = $request->get('month', now()->month);
        $year     = $request->get('year', now()->year);
        $branchId = $request->get('branch_id');

        $transactions = \App\Models\Transaction::with('branch', 'kasir', 'items')
            ->where('status', 'completed')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->get();

        $expenses = \App\Models\Expense::with('branch', 'createdBy')
            ->where('status', 'verified')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->get();

        $monthName = \DateTime::createFromFormat('!m', $month)->format('F');
        $filename  = "Laporan_{$monthName}_{$year}.csv";

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($transactions, $expenses, $monthName, $year) {
            $file = fopen('php://output', 'w');

            // Header laporan
            fputcsv($file, ["LAPORAN KEUANGAN - {$monthName} {$year}"]);
            fputcsv($file, []);

            // Pemasukan
            fputcsv($file, ['=== PEMASUKAN (TRANSAKSI) ===']);
            fputcsv($file, ['Invoice', 'Cabang', 'Kasir', 'Total', 'Metode', 'Tanggal']);
            foreach ($transactions as $t) {
                fputcsv($file, [
                    $t->invoice_number,
                    $t->branch?->name ?? '-',
                    $t->kasir?->name ?? '-',
                    $t->total,
                    $t->payment_method,
                    $t->created_at->format('d/m/Y H:i'),
                ]);
            }
            fputcsv($file, ['', '', '', 'TOTAL', $transactions->sum('total'), '']);
            fputcsv($file, []);

            // Pengeluaran
            fputcsv($file, ['=== PENGELUARAN ===']);
            fputcsv($file, ['Judul', 'Cabang', 'Kategori', 'Jumlah', 'Diajukan Oleh', 'Tanggal']);
            foreach ($expenses as $e) {
                fputcsv($file, [
                    $e->title,
                    $e->branch?->name ?? '-',
                    $e->category,
                    $e->amount,
                    $e->createdBy?->name ?? '-',
                    $e->created_at->format('d/m/Y'),
                ]);
            }
            fputcsv($file, ['', '', '', 'TOTAL', $expenses->sum('amount'), '']);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

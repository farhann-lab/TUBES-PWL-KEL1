<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\BranchStock;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    // Halaman POS utama
    public function index()
    {
        $branchId = auth()->user()->branch_id;

        // Menu tersedia dengan stok > 0
        $stocks = BranchStock::with('menu')
                             ->where('branch_id', $branchId)
                             ->where('stock', '>', 0)
                             ->whereHas('menu', fn($q) => $q->where('is_available', true))
                             ->get();

        // Promo aktif (global + cabang)
        $promotions = Promotion::where('is_active', true)
                               ->where('start_date', '<=', today())
                               ->where('end_date', '>=', today())
                               ->where(function($q) use ($branchId) {
                                   $q->where('type', 'global')
                                     ->orWhere('branch_id', $branchId);
                               })
                               ->get();

        // Riwayat transaksi hari ini
        $todayTransactions = Transaction::where('branch_id', $branchId)
                                        ->whereDate('created_at', today())
                                        ->with('items')
                                        ->latest()
                                        ->get();

        return view('kasir.transactions.index',
            compact('stocks', 'promotions', 'todayTransactions'));
    }

    // Proses transaksi
    public function store(Request $request)
    {
        $request->validate([
            'items'                 => 'required|array|min:1',
            'items.*.menu_stock_id' => 'required|exists:branch_stocks,id',
            'items.*.quantity'      => 'required|integer|min:1',
            'payment_method'        => 'required|in:cash,transfer,qris',
            'promotion_id'          => 'nullable|exists:promotions,id',
        ]);

        $branchId      = auth()->user()->branch_id;
        $transactionResult = null;

        try {
            DB::transaction(function () use ($request, $branchId, &$transactionResult) {
                $subtotal  = 0;
                $itemsData = [];

                foreach ($request->items as $item) {
                    $branchStock = BranchStock::with('menu')->findOrFail($item['menu_stock_id']);

                    if ($branchStock->stock < $item['quantity']) {
                        throw new \Exception(
                            "Stok {$branchStock->menu->name} tidak cukup! Tersisa: {$branchStock->stock}"
                        );
                    }

                    $price        = $branchStock->custom_price ?? $branchStock->menu->base_price;
                    $itemSubtotal = $price * $item['quantity'];
                    $subtotal    += $itemSubtotal;

                    $itemsData[] = [
                        'branch_stock' => $branchStock,
                        'quantity'     => $item['quantity'],
                        'price'        => $price,
                        'subtotal'     => $itemSubtotal,
                    ];
                }

                $discountAmount = 0;
                $promotionId    = null;

                if ($request->promotion_id) {
                    $promo = Promotion::find($request->promotion_id);
                    if ($promo && $promo->is_valid) {
                        $discountAmount = $promo->calculateDiscount($subtotal);
                        $promotionId    = $promo->id;
                    }
                }

                $total         = $subtotal - $discountAmount;
                $invoiceNumber = 'INV-' . now()->format('Ymd') . '-' .
                                str_pad(Transaction::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);

                $transaction = Transaction::create([
                    'invoice_number'  => $invoiceNumber,
                    'branch_id'       => $branchId,
                    'kasir_id'        => auth()->id(),
                    'promotion_id'    => $promotionId,
                    'subtotal'        => $subtotal,
                    'discount_amount' => $discountAmount,
                    'total'           => $total,
                    'payment_method'  => $request->payment_method,
                    'status'          => 'pending',
                ]);

                foreach ($itemsData as $itemData) {
                    TransactionItem::create([
                        'transaction_id' => $transaction->id,
                        'menu_id'        => $itemData['branch_stock']->menu_id,
                        'menu_name'      => $itemData['branch_stock']->menu->name,
                        'price'          => $itemData['price'],
                        'quantity'       => $itemData['quantity'],
                        'subtotal'       => $itemData['subtotal'],
                    ]);

                    $itemData['branch_stock']->decrement('stock', $itemData['quantity']);
                }

                $transactionResult = $transaction;
            });

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }

        return response()->json([
            'success'        => true,
            'message'        => 'Transaksi berhasil!',
            'invoice'        => $transactionResult->invoice_number,
            'transaction_id' => $transactionResult->id,
        ]);
    }

    // Detail transaksi
    public function show(Transaction $transaction)
    {
        $transaction->load('items', 'promotion', 'kasir');
        return view('kasir.transactions.show', compact('transaction'));
    }

    // Selesaikan transaksi
    public function complete(Transaction $transaction)
    {
        if ($transaction->kasir_id !== auth()->id()) {
            abort(403);
        }

        if ($transaction->status !== 'pending') {
            return back()->with('error', 'Transaksi tidak bisa diselesaikan!');
        }

        $transaction->update(['status' => 'completed']);

        return back()->with('success', 'Transaksi berhasil diselesaikan!');
    }

    public function requestCancel(Request $request, Transaction $transaction)
    {
        $request->validate([
            'cancel_reason' => 'required|string|min:5',
        ]);

        if ($transaction->kasir_id !== auth()->id()) {
            abort(403);
        }

        if ($transaction->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi tidak bisa dibatalkan!'
            ], 422);
        }

        $transaction->update([
            'cancel_reason' => '[REQUEST CANCEL] ' . $request->cancel_reason,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Permintaan pembatalan dikirim ke Admin!'
        ]);
    }
}
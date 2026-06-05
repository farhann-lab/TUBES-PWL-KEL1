<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\BranchStock;
use App\Models\IngredientStock;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TransactionController extends Controller
{
    // ── Halaman POS utama ─────────────────────────────────────────────────────

    public function index()
    {
        $branchId = auth()->user()->branch_id;

        /**
         * Untuk minuman (bahan_baku): tampilkan menu yang bahan-bahannya
         * masih mencukupi minimal 1 porsi.
         * Untuk makanan/snack (kuantitas_jadi): tampilkan yang stok pcs > 0.
         * BranchStock.stock untuk minuman dikosongkan (tidak dipakai),
         * untuk makanan dipakai sebagai jumlah pcs.
         */
        $stocks = BranchStock::with(['menu.ingredients.ingredient'])
            ->where('branch_id', $branchId)
            ->whereHas('menu', fn($q) => $q->where('is_available', true))
            ->get()
            ->filter(function (BranchStock $bs) use ($branchId) {
                $menu = $bs->menu;
                if ($menu->isQuantityBased()) {
                    $bs->available_portions = (int) $bs->stock;
                    $bs->is_out_of_stock    = $bs->stock <= 0;
                    return true; // tetap tampilkan
                }
                $bs->available_portions = (int) $menu->availablePortions($branchId);
                $bs->is_out_of_stock    = $bs->available_portions <= 0;
                return true; // tetap tampilkan
            })
            ->values();

        $ingredientStocks = IngredientStock::where('branch_id', $branchId)
            ->pluck('stok_sekarang', 'ingredient_id');

        // Promo aktif (global + cabang)
        $promotions = Promotion::where('is_active', true)
                               ->where('start_date', '<=', today())
                               ->where('end_date', '>=', today())
                               ->where(function ($q) use ($branchId) {
                                   $q->where('type', 'global')
                                     ->orWhere('branch_id', $branchId);
                               })
                               ->get();

        // Riwayat transaksi terbaru
        $todayTransactions = Transaction::where('branch_id', $branchId)
                                         ->with('items')
                                         ->latest()
                                         ->limit(50)
                                         ->get();

        return view('kasir.transactions.index',
            compact('stocks', 'promotions', 'todayTransactions', 'ingredientStocks'));
    }

    // ── Proses transaksi ──────────────────────────────────────────────────────

    public function store(Request $request)
    {
        if (!$request->expectsJson() && $request->isJson()) {
            $request->headers->set('Accept', 'application/json');
        }

        // Normalisasi payment_method
        $request->merge([
            'payment_method' => strtolower($request->input('payment_method', 'cash')),
        ]);

        $request->validate([
            'items'                 => 'required|array|min:1',
            'items.*.menu_stock_id' => 'required|exists:branch_stocks,id',
            'items.*.quantity'      => 'required|integer|min:1',
            'payment_method'        => 'required|in:cash,transfer,qris',
            'promotion_id'          => 'nullable|exists:promotions,id',
        ]);

        $branchId          = auth()->user()->branch_id;
        $transactionResult = null;

        try {
            DB::transaction(function () use ($request, $branchId, &$transactionResult) {
                $subtotal  = 0;
                $itemsData = [];
                $itemQuantities = collect($request->items)
                    ->groupBy('menu_stock_id')
                    ->map(fn ($items) => (int) $items->sum('quantity'))
                    ->sortKeys();

                $branchStocks = BranchStock::where('branch_id', $branchId)
                    ->whereIn('id', $itemQuantities->keys())
                    ->lockForUpdate()
                    ->get()
                    ->load(['menu.ingredients.ingredient'])
                    ->keyBy('id');

                if ($branchStocks->count() !== $itemQuantities->count()) {
                    throw new \Exception('Menu tidak tersedia untuk cabang ini.');
                }

                $ingredientNeeds = [];
                $ingredientMeta  = [];

                foreach ($itemQuantities as $menuStockId => $qty) {
                    $branchStock = $branchStocks->get($menuStockId);

                    if (! $branchStock || ! $branchStock->menu) {
                        throw new \Exception('Menu tidak tersedia untuk cabang ini.');
                    }

                    $menu = $branchStock->menu;

                    // ── Cek ketersediaan stok ──────────────────────────────
                    if ($menu->isQuantityBased()) {
                        // Makanan / snack → cek stok pcs
                        if ($branchStock->stock < $qty) {
                            throw new \Exception(
                                "Stok {$menu->name} tidak cukup! Tersisa: {$branchStock->stock} pcs"
                            );
                        }
                    } else {
                        // Minuman → cek bahan baku
                        if ($menu->ingredients->isEmpty()) {
                            throw new \Exception("Resep bahan baku {$menu->name} belum diatur.");
                        }

                        foreach ($menu->ingredients as $menuIngredient) {
                            $ingredientId = $menuIngredient->ingredient_id;
                            $needed = (float) $menuIngredient->jumlah_per_sajian * $qty;

                            $ingredientNeeds[$ingredientId] = ($ingredientNeeds[$ingredientId] ?? 0) + $needed;
                            $ingredientMeta[$ingredientId] = [
                                'bahan' => $menuIngredient->ingredient?->nama_bahan ?? 'Bahan baku',
                                'satuan' => $menuIngredient->ingredient?->satuan ?? '',
                            ];
                        }
                    }

                    $price        = $branchStock->custom_price ?? $menu->base_price;
                    $itemSubtotal = $price * $qty;
                    $subtotal    += $itemSubtotal;

                    $itemsData[] = [
                        'branch_stock' => $branchStock,
                        'menu'         => $menu,
                        'quantity'     => $qty,
                        'price'        => $price,
                        'subtotal'     => $itemSubtotal,
                    ];
                }

                // ── Promo ─────────────────────────────────────────────────
                $ingredientStocks = collect();
                if (! empty($ingredientNeeds)) {
                    $ingredientStocks = IngredientStock::where('branch_id', $branchId)
                        ->whereIn('ingredient_id', array_keys($ingredientNeeds))
                        ->lockForUpdate()
                        ->get()
                        ->keyBy('ingredient_id');

                    $shortages = [];
                    foreach ($ingredientNeeds as $ingredientId => $needed) {
                        $stock = $ingredientStocks->get($ingredientId);
                        $available = (float) ($stock?->stok_sekarang ?? 0);

                        if (! $stock || $available < $needed) {
                            $meta = $ingredientMeta[$ingredientId];
                            $shortages[] = "{$meta['bahan']}: butuh {$needed} {$meta['satuan']}, tersedia {$available} {$meta['satuan']}";
                        }
                    }

                    if (! empty($shortages)) {
                        throw new \Exception('Bahan baku tidak cukup! ' . implode('; ', $shortages));
                    }
                }

                $discountAmount = 0;
                $promotionId    = null;

                if ($request->promotion_id) {
                    $promo = Promotion::find($request->promotion_id);
                    if ($promo) {
                        if (!$promo->is_valid) {
                            throw new \Exception('Promo sudah tidak berlaku atau telah berakhir.');
                        }

                        // Cek minimum pembelian jika kolom ada
                        if (!empty($promo->min_purchase) && $subtotal < $promo->min_purchase) {
                            throw new \Exception(
                                'Pembelian tidak memenuhi syarat promo ini. Minimum pembelian Rp ' .
                                number_format($promo->min_purchase, 0, ',', '.') . '.'
                            );
                        }

                        $discountAmount = $promo->calculateDiscount($subtotal);
                        $promotionId    = $promo->id;
                    }
                }

                $total         = $subtotal - $discountAmount;
                $invoiceNumber = 'INV-' . now()->format('Ymd') . '-' .
                    str_pad(Transaction::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);

                $transactionPayload = [
                    'invoice_number'  => $invoiceNumber,
                    'branch_id'       => $branchId,
                    'kasir_id'        => auth()->id(),
                    'promotion_id'    => $promotionId,
                    'subtotal'        => $subtotal,
                    'discount_amount' => $discountAmount,
                    'total'           => $total,
                    'payment_method'  => $request->payment_method,
                    'status'          => 'completed',
                ];

                if (Schema::hasColumn('transactions', 'kasir_nama_display')) {
                    $transactionPayload['kasir_nama_display'] = session('kasir_nama', auth()->user()->name);
                }

                $transaction = Transaction::create($transactionPayload);

                foreach ($itemsData as $itemData) {
                    TransactionItem::create([
                        'transaction_id' => $transaction->id,
                        'menu_id'        => $itemData['menu']->id,
                        'menu_name'      => $itemData['menu']->name,
                        'price'          => $itemData['price'],
                        'quantity'       => $itemData['quantity'],
                        'subtotal'       => $itemData['subtotal'],
                    ]);

                    if ($itemData['menu']->isQuantityBased()) {
                        $itemData['branch_stock']->stock = (float) $itemData['branch_stock']->stock - $itemData['quantity'];
                        $itemData['branch_stock']->save();
                    }
                }

                foreach ($ingredientNeeds as $ingredientId => $needed) {
                    $stock = $ingredientStocks->get($ingredientId);
                    $stock->stok_sekarang = (float) $stock->stok_sekarang - $needed;
                    $stock->save();
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

    // ── Detail transaksi ──────────────────────────────────────────────────────

    public function show(Transaction $transaction)
    {
        $transaction->load('items', 'promotion', 'kasir', 'branch');
        return view('kasir.transactions.show', compact('transaction'));
    }

    // ── Request pembatalan oleh kasir ─────────────────────────────────────────

    public function requestCancel(Request $request, Transaction $transaction)
    {
        $request->validate([
            'cancel_reason' => 'required|string|min:5',
        ]);

        if ($transaction->kasir_id !== auth()->id()) {
            abort(403);
        }

        if ($transaction->status !== 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi tidak bisa dibatalkan!',
            ], 422);
        }

        if ($transaction->created_at->diffInMinutes(now()) > 60) {
            return response()->json([
                'success' => false,
                'message' => 'Batas waktu pembatalan (1 jam) telah lewat!',
            ], 422);
        }

        $transaction->update([
            'cancel_reason' => '[REQUEST CANCEL] ' . $request->cancel_reason,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Permintaan pembatalan dikirim ke Admin!',
        ]);
    }
}
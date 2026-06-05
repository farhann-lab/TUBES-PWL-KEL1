<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\StockRequest;
use App\Models\BranchStock;
use App\Models\Ingredient;
use App\Models\IngredientStock;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockRequestController extends Controller
{
    // Semua pengajuan dari semua cabang
    public function index()
    {
        $requests = StockRequest::with('branch', 'requestedBy', 'verifiedBy')
                                ->latest()
                                ->get();

        return view('manager.stock-requests.index', compact('requests'));
    }

    // Detail pengajuan
    public function show(StockRequest $stockRequest)
    {
        $stockRequest->load('branch', 'requestedBy', 'verifiedBy');
        return view('manager.stock-requests.show', compact('stockRequest'));
    }

    // Approve pengajuan
    public function approve(StockRequest $stockRequest)
    {
        if ($stockRequest->status !== 'pending') {
            return back()->with('error', 'Pengajuan ini sudah diproses!');
        }

        $stockRequest->update([
            'status'          => 'approved',
            'delivery_status' => 'waiting',
            'verified_by'     => auth()->id(),
            'verified_at'     => now(),
        ]);

        return back()->with('success', 'Pengajuan disetujui! Admin cabang akan mengkonfirmasi kedatangan barang.');
    }

// Manager final confirm → stok bertambah
    public function confirmDelivery(StockRequest $stockRequest)
    {
        if ($stockRequest->delivery_status !== 'delivered') {
            return back()->with('error', 'Admin cabang belum mengkonfirmasi kedatangan barang!');
        }

        try {
            DB::transaction(function () use ($stockRequest) {
                $stockRequest->update([
                    'delivery_status' => 'confirmed',
                ]);

                // Sekarang baru tambah stok
                if ($stockRequest->type === 'stock') {
                    $ingredient = Ingredient::where('nama_bahan', $stockRequest->item_name)->first();
                    $menu = Menu::where('name', $stockRequest->item_name)->first();

                    if ($stockRequest->stock_item_type === 'bahan_baku' || (! $stockRequest->stock_item_type && $ingredient)) {
                        if (! $ingredient) {
                            throw new \RuntimeException("Bahan baku {$stockRequest->item_name} tidak ditemukan.");
                        }

                        IngredientStock::firstOrCreate(
                            [
                                'branch_id' => $stockRequest->branch_id,
                                'ingredient_id' => $ingredient->id,
                            ],
                            ['stok_sekarang' => 0, 'stok_minimum' => 0]
                        )->increment('stok_sekarang', $stockRequest->quantity);

                        return;
                    }

                    if ($stockRequest->stock_item_type === 'produk_jadi' || (! $stockRequest->stock_item_type && $menu)) {
                        if (! $menu) {
                            throw new \RuntimeException("Menu {$stockRequest->item_name} tidak ditemukan.");
                        }

                        BranchStock::firstOrCreate(
                            [
                                'branch_id' => $stockRequest->branch_id,
                                'menu_id' => $menu->id,
                            ],
                            ['stock' => 0, 'custom_price' => null]
                        )->increment('stock', $stockRequest->quantity);

                        return;
                    }

                    throw new \RuntimeException("Item stok {$stockRequest->item_name} tidak ditemukan.");
                }
            });
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Pengiriman dikonfirmasi! Stok cabang telah bertambah.');
    }

    // Reject pengajuan
    public function reject(Request $request, StockRequest $stockRequest)
    {
        $request->validate([
            'rejection_note' => 'required|string|min:5',
        ]);

        if ($stockRequest->status !== 'pending') {
            return back()->with('error', 'Pengajuan ini sudah diproses!');
        }

        $stockRequest->update([
            'status'         => 'rejected',
            'verified_by'    => auth()->id(),
            'verified_at'    => now(),
            'rejection_note' => $request->rejection_note,
        ]);

        return back()->with('success', 'Pengajuan berhasil ditolak!');
    }
}

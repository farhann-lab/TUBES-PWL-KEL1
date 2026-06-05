<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StockRequest;
use App\Models\Ingredient;
use App\Models\Menu;
use Illuminate\Http\Request;

class StockRequestController extends Controller
{
    public function index()
    {
        $branchId = auth()->user()->branch_id;

        $requests = StockRequest::where('branch_id', $branchId)
                                ->with('requestedBy', 'verifiedBy')
                                ->latest()
                                ->get();

        return view('admin.stock-requests.index', compact('requests'));
    }

    public function create()
    {
        // Bahan baku untuk minuman (bahan_baku based)
        $ingredients = Ingredient::orderBy('nama_bahan')->get();

        // Menu makanan & snack (kuantitas_jadi based)
        $produkJadi = Menu::whereIn('category', ['makanan', 'snack'])
                          ->where('is_available', true)
                          ->orderBy('name')
                          ->get();

        return view('admin.stock-requests.create', compact('ingredients', 'produkJadi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items'              => 'required|array|min:1',
            'items.*.item_name'  => 'required|string|max:150',
            'items.*.quantity'   => 'required|numeric|min:1',
            'items.*.unit'       => 'nullable|string|max:20',
            'items.*.tipe'       => 'required|in:bahan_baku,produk_jadi,operasional',
            'reason'             => 'nullable|string',
        ]);

        foreach ($request->items as $item) {
            StockRequest::create([
                'branch_id'       => auth()->user()->branch_id,
                'requested_by'    => auth()->id(),
                'type'            => $item['tipe'] === 'operasional' ? 'operational' : 'stock',
                'stock_item_type' => in_array($item['tipe'], ['bahan_baku','produk_jadi']) ? $item['tipe'] : null,
                'item_name'       => $item['item_name'],
                'unit'            => $item['unit'] ?? null,
                'quantity'        => $item['quantity'],
                'reason'          => $request->reason,
                'status'          => 'pending',
            ]);
        }

        return redirect()->route('admin.stock-requests.index')
                        ->with('success', count($request->items) . ' pengajuan berhasil dikirim ke Manager!');
    }

    /**
     * Admin konfirmasi barang sampai + upload foto.
     * Setelah dikonfirmasi manager, stok di cabang diupdate otomatis.
     */
    public function confirmDelivery(Request $request, StockRequest $stockRequest)
    {
        $request->validate([
            'delivery_note'  => 'required|string|min:5',
            'delivery_photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        if ($stockRequest->status !== 'approved') {
            return back()->with('error', 'Pengajuan belum disetujui manager!');
        }

        if ($stockRequest->branch_id !== auth()->user()->branch_id) {
            abort(403);
        }

        $photoPath = null;
        if ($request->hasFile('delivery_photo')) {
            $photoPath = $request->file('delivery_photo')->store('deliveries', 'public');
        }

        $stockRequest->update([
            'delivery_status' => 'delivered',
            'delivery_note'   => $request->delivery_note,
            'delivery_photo'  => $photoPath,
            'delivered_at'    => now(),
            'delivered_by'    => auth()->id(),
        ]);

        return back()->with('success', 'Konfirmasi pengiriman berhasil dikirim ke Manager Pusat!');
    }
}

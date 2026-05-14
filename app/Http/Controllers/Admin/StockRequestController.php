<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StockRequest;
use Illuminate\Http\Request;

class StockRequestController extends Controller
{
    // Daftar semua pengajuan cabang ini
    public function index()
    {
        $branchId = auth()->user()->branch_id;

        $requests = StockRequest::where('branch_id', $branchId)
                                ->with('requestedBy', 'verifiedBy')
                                ->latest()
                                ->get();

        return view('admin.stock-requests.index', compact('requests'));
    }

    // Form buat pengajuan
    public function create()
    {
        return view('admin.stock-requests.create');
    }

    // Simpan pengajuan
    public function store(Request $request)
    {
        $request->validate([
            'type'      => 'required|in:stock,operational',
            'item_name' => 'required|string|max:100',
            'unit'      => 'nullable|string|max:20',
            'quantity'  => 'required|numeric|min:1',
            'reason'    => 'nullable|string',
        ]);

        StockRequest::create([
            'branch_id'    => auth()->user()->branch_id,
            'requested_by' => auth()->id(),
            'type'         => $request->type,
            'item_name'    => $request->item_name,
            'unit'         => $request->unit,
            'quantity'     => $request->quantity,
            'reason'       => $request->reason,
            'status'       => 'pending',
        ]);

        return redirect()->route('admin.stock-requests.index')
                         ->with('success', 'Pengajuan berhasil dikirim ke Manager Pusat!');
    }

    // Admin konfirmasi barang sampai + upload foto
    public function confirmDelivery(Request $request, StockRequest $stockRequest)
    {
        $request->validate([
            'delivery_note'  => 'required|string|min:5',
            'delivery_photo' => 'required|image|mimes:jpg,jpeg,png|max:3072',
        ]);

        if ($stockRequest->status !== 'approved') {
            return back()->with('error', 'Pengajuan belum disetujui manager!');
        }

        if ($stockRequest->branch_id !== auth()->user()->branch_id) {
            abort(403);
        }

        $photoPath = $request->file('delivery_photo')->store('deliveries', 'public');

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
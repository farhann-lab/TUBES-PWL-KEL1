<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PromotionController extends Controller
{
    public function index()
    {
        $branchId = auth()->user()->branch_id;

        $branchPromos = Promotion::where('branch_id', $branchId)
                                ->with('createdBy')
                                ->latest()->get();

        $globalPromos = Promotion::where('type', 'global')
                                ->where('is_active', true)
                                ->where('review_status', 'approved')
                                ->latest()->get();

        return view('admin.promotions.index', compact('branchPromos', 'globalPromos'));
    }

    public function create()
    {
        return view('admin.promotions.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validatePromotionRequest($request);

        Promotion::create([
            'branch_id'      => auth()->user()->branch_id,
            'created_by'     => auth()->id(),
            'name'           => $validated['name'],
            'description'    => $validated['description'] ?? null,
            'type'           => 'branch',
            'discount_type'  => $validated['discount_type'],
            'discount_value' => $validated['discount_value'],
            'min_purchase'   => $validated['min_purchase'] ?? 0,
            'start_date'     => $validated['start_date'],
            'end_date'       => $validated['end_date'],
            'is_active'      => false,
            'review_status'  => 'pending',
        ]);

        return redirect()->route('admin.promotions.index')
                        ->with('success', 'Promo dikirim ke Manager Pusat untuk ditinjau!');
    }

    public function edit(Promotion $promotion)
    {
        $this->authorizeBranchPromotion($promotion);

        return view('admin.promotions.edit', compact('promotion'));
    }

    public function update(Request $request, Promotion $promotion)
    {
        $this->authorizeBranchPromotion($promotion);

        $validated = $this->validatePromotionRequest($request);

        $promotion->update([
            'name'           => $validated['name'],
            'description'    => $validated['description'] ?? null,
            'discount_type'  => $validated['discount_type'],
            'discount_value' => $validated['discount_value'],
            'min_purchase'   => $validated['min_purchase'] ?? 0,
            'start_date'     => $validated['start_date'],
            'end_date'       => $validated['end_date'],
            'is_active'      => false,
            'review_status'  => 'pending',
            'review_note'    => null,
            'reviewed_by'    => null,
            'reviewed_at'    => null,
        ]);

        return redirect()->route('admin.promotions.index')
                        ->with('success', 'Promo berhasil diperbarui dan dikirim ulang untuk ditinjau!');
    }

    public function destroy(Promotion $promotion)
    {
        $this->authorizeBranchPromotion($promotion);

        DB::transaction(function () use ($promotion) {
            Transaction::where('promotion_id', $promotion->id)
                ->update(['promotion_id' => null]);

            $promotion->delete();
        });

        return back()->with('success', 'Promo berhasil dihapus!');
    }

    private function validatePromotionRequest(Request $request): array
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:100',
            'description'    => 'nullable|string',
            'discount_type'  => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'min_purchase'   => 'nullable|numeric|min:0',
            'start_date'     => 'required|date',
            'end_date'       => 'required|date|after_or_equal:start_date',
        ]);

        if ($validated['discount_type'] === 'percentage' && $validated['discount_value'] > 100) {
            throw ValidationException::withMessages([
                'discount_value' => 'Diskon persentase tidak boleh lebih dari 100%',
            ]);
        }

        return $validated;
    }

    private function authorizeBranchPromotion(Promotion $promotion): void
    {
        if ($promotion->type !== 'branch' || $promotion->branch_id !== auth()->user()->branch_id) {
            abort(403);
        }
    }
}

<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::withTrashed()->with('users')->latest()->get();
        return view('manager.branches.index', compact('branches'));
    }

    public function create()
    {
        $ingredients  = \App\Models\Ingredient::orderBy('nama_bahan')->get();
        $menusMakanan = \App\Models\Menu::whereIn('category', ['makanan', 'snack'])
                                        ->where('is_available', true)->get();
        return view('manager.branches.create', compact('ingredients', 'menusMakanan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:100',
            'address'        => 'required|string',
            'phone'          => 'nullable|string|max:20',
            'status'         => 'required|in:active,inactive',
            // ✅ Tambah regex @elco.com
            'admin_name'     => 'required|string|max:100',
            'admin_email'    => [
                'required',
                'email',
                'unique:users,email',
                'regex:/^[a-zA-Z0-9._%+\-]+@elco\.com$/',
            ],
            'admin_password' => 'required|string|min:8',
        ], [
            'admin_email.regex' => 'Email admin harus menggunakan domain @elco.com.',
        ]);

        $branch = Branch::create([
            'name'    => $request->name,
            'address' => $request->address,
            'phone'   => $request->phone,
            'status'  => $request->status,
        ]);

        User::create([
            'name'      => $request->admin_name,
            'email'     => $request->admin_email,
            'password'  => Hash::make($request->admin_password),
            'role'      => 'admin',
            'branch_id' => $branch->id,
        ]);

        DB::transaction(function () use ($request, $branch) {
            // Stok bahan baku
            foreach ($request->stok_bahan ?? [] as $ingId => $jumlah) {
                if ($jumlah > 0) {
                    \App\Models\IngredientStock::updateOrCreate(
                        ['branch_id' => $branch->id, 'ingredient_id' => $ingId],
                        ['stok_sekarang' => $jumlah, 'stok_minimum' => 0]
                    );
                }
            }
            // Stok makanan/snack
            foreach ($request->stok_makanan ?? [] as $menuId => $pcs) {
                if ($pcs > 0) {
                    \App\Models\BranchStock::updateOrCreate(
                        ['branch_id' => $branch->id, 'menu_id' => $menuId],
                        ['stock' => $pcs]
                    );
                }
            }
        });
        return redirect()->route('manager.branches.index')
            ->with('success', 'Cabang ' . $branch->name . ' berhasil dibuka dan akun admin telah dibuat!');;
    }

    public function edit(Branch $branch)
    {
        $admins = User::where('branch_id', $branch->id)->where('role', 'admin')->get();
        return view('manager.branches.edit', compact('branch', 'admins'));
    }

    public function update(Request $request, Branch $branch)
    {
        $request->validate([
            'name'    => 'required|string|max:100',
            'address' => 'required|string',
            'phone'   => 'nullable|string|max:20',
            'status'  => 'required|in:active,inactive',
        ]);

        $branch->update($request->only('name', 'address', 'phone', 'status'));

        return redirect()->route('manager.branches.index')
                         ->with('success', 'Cabang berhasil diperbarui!');
    }

    public function destroy(Branch $branch)
    {
        DB::transaction(function () use ($branch) {
            $userIds = User::where('branch_id', $branch->id)->pluck('id');

            \App\Models\Promotion::where('branch_id', $branch->id)
                ->orWhereIn('created_by', $userIds)
                ->delete();

            $branch->forceDelete();

            User::whereIn('id', $userIds)->delete();
        });

        return redirect()->route('manager.branches.index')
                        ->with('success', 'Cabang dan semua akun terkait berhasil dihapus permanen!');
    }

    public function deactivate(Branch $branch)
    {
        $branch->update(['status' => 'inactive']);
        return redirect()->route('manager.branches.index')
                        ->with('success', 'Cabang berhasil dinonaktifkan sementara!');
    }

    public function restore($id)
    {
        Branch::withTrashed()->findOrFail($id)->restore();
        return redirect()->route('manager.branches.index')
                         ->with('success', 'Cabang berhasil dipulihkan!');
    }

    public function addKasir(Request $request, Branch $branch)
    {
        $request->validate([
            'kasir_name'     => 'required|string|max:100',
            // ✅ Tambah regex @elco.com
            'kasir_email'    => [
                'required',
                'email',
                'unique:users,email',
                'regex:/^[a-zA-Z0-9._%+\-]+@elco\.com$/',
            ],
            'kasir_password' => 'required|string|min:8',
        ], [
            'kasir_email.regex' => 'Email kasir harus menggunakan domain @elco.com.',
        ]);

        User::create([
            'name'      => $request->kasir_name,
            'email'     => $request->kasir_email,
            'password'  => Hash::make($request->kasir_password),
            'role'      => 'kasir',
            'branch_id' => $branch->id,
        ]);

        return back()->with('success', 'Akun kasir berhasil ditambahkan!');
    }
}

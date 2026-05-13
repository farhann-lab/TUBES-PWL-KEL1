<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Branch;
use App\Models\BranchStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    // Tampilkan semua menu
    public function index()
    {
        $menus = Menu::withTrashed()->latest()->get();
        return view('manager.menus.index', compact('menus'));
    }

    // Form tambah menu
    public function create()
    {
        return view('manager.menus.create');
    }

    // Simpan menu baru
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string',
            'category'    => 'required|in:minuman,makanan,snack',
            'base_price'  => 'required|numeric|min:0',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Upload gambar jika ada
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('menus', 'public');
        }

        // Simpan menu
        $menu = Menu::create([
            'name'         => $request->name,
            'description'  => $request->description,
            'category'     => $request->category,
            'base_price'   => $request->base_price,
            'image'        => $imagePath,
            'is_available' => true,
        ]);

        // Otomatis buat branch_stock untuk semua cabang aktif (stok = 0)
        $branches = Branch::where('status', 'active')->get();
        foreach ($branches as $branch) {
            BranchStock::create([
                'branch_id'    => $branch->id,
                'menu_id'      => $menu->id,
                'stock'        => 0,
                'custom_price' => null,
            ]);
        }

        return redirect()->route('manager.menus.index')
                         ->with('success', 'Menu berhasil ditambahkan dan terdistribusi ke semua cabang!');
    }

    // Form edit menu
    public function edit(Menu $menu)
    {
        // Ambil stok per cabang untuk ditampilkan
        $branchStocks = BranchStock::with('branch')
                                   ->where('menu_id', $menu->id)
                                   ->get();
        return view('manager.menus.edit', compact('menu', 'branchStocks'));
    }

    // Update menu
    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'name'         => 'required|string|max:100',
            'description'  => 'nullable|string',
            'category'     => 'required|in:minuman,makanan,snack',
            'base_price'   => 'required|numeric|min:0',
            'image'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_available' => 'boolean',
        ]);

        // Upload gambar baru jika ada
        if ($request->hasFile('image')) {
            // Hapus gambar lama
            if ($menu->image) {
                Storage::disk('public')->delete($menu->image);
            }
            $menu->image = $request->file('image')->store('menus', 'public');
        }

        $menu->update([
            'name'         => $request->name,
            'description'  => $request->description,
            'category'     => $request->category,
            'base_price'   => $request->base_price,
            'image'        => $menu->image,
            'is_available' => $request->has('is_available'),
        ]);

        return redirect()->route('manager.menus.index')
                         ->with('success', 'Menu berhasil diperbarui!');
    }

    // Soft delete menu
    public function destroy(Menu $menu)
    {
        $menu->delete();
        return redirect()->route('manager.menus.index')
                         ->with('success', 'Menu berhasil dinonaktifkan!');
    }

    // Restore menu
    public function restore($id)
    {
        Menu::withTrashed()->findOrFail($id)->restore();
        return redirect()->route('manager.menus.index')
                         ->with('success', 'Menu berhasil dipulihkan!');
    }
}
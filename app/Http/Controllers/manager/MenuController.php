<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Branch;
use App\Models\BranchStock;
use App\Models\Ingredient;
use App\Models\IngredientStock;
use App\Models\MenuIngredient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class MenuController extends Controller
{
    // Tampilkan semua menu
    public function index()
    {
        $menus = Menu::latest()->get();
        return view('manager.menus.index', compact('menus'));
    }

    // Form tambah menu
    public function create()
    {
        $ingredients = Ingredient::orderBy('nama_bahan')->get();
        return view('manager.menus.create', compact('ingredients'));
    }

    // Simpan menu baru
    public function store(Request $request)
    {
        $request->validate([
            'name'                         => 'required|string|max:100',
            'description'                  => 'nullable|string',
            'category'                     => 'required|in:minuman,makanan,snack',
            'base_price'                   => 'required|numeric|min:0',
            'image'                        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            // Resep — wajib jika kategori minuman
            'ingredients'                  => 'nullable|array',
            'ingredients.*.ingredient_id'  => 'nullable|exists:ingredients,id',
            'ingredients.*.jumlah'         => 'nullable|numeric|min:0.001',
            // Stok awal untuk makanan/snack
            'stok_awal'                    => 'nullable|integer|min:0',
        ]);

        $ingredientRows = $this->validatedIngredientRows($request);

        // Tentukan stock_type otomatis dari kategori
        $stockType = ($request->category === 'minuman') ? 'bahan_baku' : 'kuantitas_jadi';

        // Upload gambar
        $imagePath = Menu::fallbackImageFor($request->name, $request->category);
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('menus', 'public');
        }

        DB::transaction(function () use ($request, $stockType, $imagePath, $ingredientRows) {
            // 1. Buat menu
            $menu = Menu::create([
                'name'         => $request->name,
                'description'  => $request->description,
                'category'     => $request->category,
                'stock_type'   => $stockType,
                'base_price'   => $request->base_price,
                'image'        => $imagePath,
                'is_available' => true,
            ]);

            $branches = Branch::where('status', 'active')->get();

            if ($stockType === 'bahan_baku') {
                // 2a. Minuman: simpan resep (menu_ingredients)
                foreach ($ingredientRows as $ing) {
                    MenuIngredient::create([
                        'menu_id'             => $menu->id,
                        'ingredient_id'       => $ing['ingredient_id'],
                        'jumlah_per_sajian'   => $ing['jumlah'],
                    ]);
                }

                // 2b. Buat branch_stock dengan stock = 0 (stok dikontrol via bahan baku)
                foreach ($branches as $branch) {
                    BranchStock::create([
                        'branch_id'    => $branch->id,
                        'menu_id'      => $menu->id,
                        'stock'        => 0,
                        'custom_price' => null,
                    ]);

                    // Pastikan ingredient_stock ada untuk semua bahan di cabang ini
                    foreach ($ingredientRows as $ing) {
                        IngredientStock::firstOrCreate(
                            ['branch_id' => $branch->id, 'ingredient_id' => $ing['ingredient_id']],
                            ['stok_sekarang' => 0, 'stok_minimum' => 0]
                        );
                    }
                }
            } else {
                // 2c. Makanan/snack: buat branch_stock dengan stok awal
                $stokAwal = (int) ($request->stok_awal ?? 0);
                foreach ($branches as $branch) {
                    BranchStock::create([
                        'branch_id'    => $branch->id,
                        'menu_id'      => $menu->id,
                        'stock'        => $stokAwal,
                        'custom_price' => null,
                    ]);
                }
            }
        });

        return redirect()->route('manager.menus.index')
                         ->with('success', 'Menu berhasil ditambahkan dan terdistribusi ke semua cabang!');
    }

    // Form edit menu
    public function edit(Menu $menu)
    {
        $branchStocks = BranchStock::with('branch')->where('menu_id', $menu->id)->get();
        $ingredients  = Ingredient::orderBy('nama_bahan')->get();
        $menuIngredients = $menu->ingredients()->with('ingredient')->get();

        return view('manager.menus.edit',
            compact('menu', 'branchStocks', 'ingredients', 'menuIngredients'));
    }

    // Update menu
    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'name'                         => 'required|string|max:100',
            'description'                  => 'nullable|string',
            'category'                     => 'required|in:minuman,makanan,snack',
            'base_price'                   => 'required|numeric|min:0',
            'image'                        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'is_available'                 => 'boolean',
            'ingredients'                  => 'nullable|array',
            'ingredients.*.ingredient_id'  => 'nullable|exists:ingredients,id',
            'ingredients.*.jumlah'         => 'nullable|numeric|min:0.001',
        ]);

        $ingredientRows = $this->validatedIngredientRows($request);
        $stockType = ($request->category === 'minuman') ? 'bahan_baku' : 'kuantitas_jadi';

        if ($request->hasFile('image')) {
            if ($menu->image && ! str_starts_with($menu->image, 'images/') && ! str_starts_with($menu->image, 'image/')) {
                Storage::disk('public')->delete($menu->image);
            }
            $menu->image = $request->file('image')->store('menus', 'public');
        }

        DB::transaction(function () use ($request, $menu, $stockType, $ingredientRows) {
            $menu->update([
                'name'         => $request->name,
                'description'  => $request->description,
                'category'     => $request->category,
                'stock_type'   => $stockType,
                'base_price'   => $request->base_price,
                'image'        => $menu->image ?: Menu::fallbackImageFor($request->name, $request->category),
                'is_available' => $request->has('is_available'),
            ]);

            // Update resep sesuai kategori
            $menu->ingredients()->delete();

            if ($stockType === 'bahan_baku') {
                foreach ($ingredientRows as $ing) {
                    MenuIngredient::create([
                        'menu_id'           => $menu->id,
                        'ingredient_id'     => $ing['ingredient_id'],
                        'jumlah_per_sajian' => $ing['jumlah'],
                    ]);
                }
            }
        });

        return redirect()->route('manager.menus.index')
                         ->with('success', 'Menu berhasil diperbarui!');
    }

    public function recipe(Menu $menu)
    {
        $menuIngredients = $menu->ingredients()->with('ingredient')->get();
        return view('manager.menus.recipe', compact('menu', 'menuIngredients'));
    }

    // Hapus permanen menu
    public function destroy(Menu $menu)
    {
        DB::transaction(function () use ($menu) {
            if ($menu->image && ! str_starts_with($menu->image, 'images/') && ! str_starts_with($menu->image, 'image/')) {
                Storage::disk('public')->delete($menu->image);
            }

            $menu->transactionItems()->update(['menu_id' => null]);
            $menu->ingredients()->delete();
            $menu->branchStocks()->delete();
            $menu->forceDelete();
        });

        return redirect()->route('manager.menus.index')
                         ->with('success', 'Menu berhasil dihapus permanen!');
    }

    // ── CRUD Ingredient (bahan baku) ─────────────────────────────────────────

    public function ingredients()
    {
        $ingredients = Ingredient::withCount('menuIngredients')->latest()->get();
        return view('manager.menus.ingredients', compact('ingredients'));
    }

    public function storeIngredient(Request $request)
    {
        $request->validate([
            'kode_bahan'  => 'required|string|unique:ingredients,kode_bahan',
            'nama_bahan'  => 'required|string|max:100',
            'kategori'    => 'nullable|string|max:50',
            'satuan'      => 'required|in:gram,ml,pcs',
        ]);

        $ingredient = Ingredient::create($request->only('kode_bahan', 'nama_bahan', 'kategori', 'satuan'));

        $branches = Branch::where('status', 'active')->get();
        foreach ($branches as $branch) {
            IngredientStock::firstOrCreate(
                ['branch_id' => $branch->id, 'ingredient_id' => $ingredient->id],
                ['stok_sekarang' => 0, 'stok_minimum' => 0]
            );
        }

        return redirect()->route('manager.menus.ingredients')
                         ->with('success', 'Bahan baku berhasil ditambahkan!');
    }

    public function updateIngredient(Request $request, Ingredient $ingredient)
    {
        $request->validate([
            'nama_bahan' => 'required|string|max:100',
            'kategori'   => 'nullable|string|max:50',
            'satuan'     => 'required|in:gram,ml,pcs',
        ]);

        $ingredient->update($request->only('nama_bahan', 'kategori', 'satuan'));

        return redirect()->route('manager.menus.ingredients')
                         ->with('success', 'Bahan baku berhasil diperbarui!');
    }

    private function validatedIngredientRows(Request $request): array
    {
        if ($request->category !== 'minuman') {
            return [];
        }

        $rows = collect($request->input('ingredients', []))
            ->filter(fn ($row) => filled($row['ingredient_id'] ?? null) || filled($row['jumlah'] ?? null))
            ->values();

        if ($rows->isEmpty()) {
            throw ValidationException::withMessages([
                'ingredients' => 'Menu minuman wajib memiliki minimal satu bahan baku.',
            ]);
        }

        $completeRows = $rows->filter(
            fn ($row) => filled($row['ingredient_id'] ?? null) && filled($row['jumlah'] ?? null)
        );

        if ($completeRows->count() !== $rows->count()) {
            throw ValidationException::withMessages([
                'ingredients' => 'Setiap baris bahan baku harus berisi nama bahan dan jumlah.',
            ]);
        }

        if ($completeRows->pluck('ingredient_id')->duplicates()->isNotEmpty()) {
            throw ValidationException::withMessages([
                'ingredients' => 'Bahan baku yang sama tidak boleh dipilih lebih dari satu kali.',
            ]);
        }

        return $completeRows
            ->map(fn ($row) => [
                'ingredient_id' => (int) $row['ingredient_id'],
                'jumlah'        => (float) $row['jumlah'],
            ])
            ->all();
    }
}

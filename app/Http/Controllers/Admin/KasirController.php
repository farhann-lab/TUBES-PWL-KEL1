<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class KasirController extends Controller
{
    public function index()
    {
        $kasirs = User::where('branch_id', auth()->user()->branch_id)
                      ->where('role', 'kasir')
                      ->latest()
                      ->get();
        return view('admin.kasirs.index', compact('kasirs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kasir_name'     => 'required|string|max:100',
            'kasir_email'    => [
                'required', 'email', 'unique:users,email',
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
            'branch_id' => auth()->user()->branch_id,
            'is_active' => true,
        ]);

        return redirect()->route('admin.kasirs.index')
                         ->with('success', 'Akun kasir berhasil dibuat!');
    }
}
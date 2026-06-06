<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::withTrashed()->with('users')->latest()->get();
        return view('manager.branches.index', compact('branches'));
    }

    public function create()
    {
        return view('manager.branches.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'address' => 'required|string',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive',
            'admin_name' => 'required|string|max:100',
            'admin_email' => [
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
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
            'status' => $request->status,
        ]);

        User::create([
            'name' => $request->admin_name,
            'email' => $request->admin_email,
            'password' => Hash::make($request->admin_password),
            'role' => 'admin',
            'branch_id' => $branch->id,
        ]);

        return redirect()->route('manager.branches.index')
            ->with('success', 'Cabang ' . $branch->name . ' berhasil dibuka dan akun admin telah dibuat!');
    }

    public function edit(Branch $branch)
    {
        $admins = User::where('branch_id', $branch->id)->where('role', 'admin')->get();
        return view('manager.branches.edit', compact('branch', 'admins'));
    }

    public function update(Request $request, Branch $branch)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'address' => 'required|string',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive',
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
}

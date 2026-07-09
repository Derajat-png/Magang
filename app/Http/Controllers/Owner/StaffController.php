<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\StoreStaffRequest;
use App\Http\Requests\UpdateStaffRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;

class StaffController extends Controller
{
    public function index()
    {
        $umkmId = auth()->user()->umkm_id;
        
        Gate::authorize('viewAny', User::class);

        $staffs = User::where('umkm_id', $umkmId)
            ->where('role', 'staff')
            ->latest()
            ->paginate(10);

        return view('owner.staff.index', compact('staffs'));
    }

    public function create()
    {
        Gate::authorize('viewAny', User::class);
        return view('owner.staff.create');
    }

    public function store(StoreStaffRequest $request)
    {
        $umkmId = auth()->user()->umkm_id;

        Gate::authorize('viewAny', User::class);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'staff',
            'status' => 'active',
            'umkm_id' => $umkmId,
        ]);

        return redirect()->route('owner.staff.index')->with('success', 'Staff baru berhasil didaftarkan.');
    }

    public function edit(User $user)
    {
        Gate::authorize('manage', $user);
        return view('owner.staff.edit', compact('user'));
    }

    public function update(UpdateStaffRequest $request, User $user)
    {
        Gate::authorize('manage', $user);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('owner.staff.index')->with('success', 'Data staff berhasil diperbarui.');
    }

    public function toggleStatus(User $user)
    {
        Gate::authorize('manage', $user);

        $user->update([
            'status' => $user->status === 'active' ? 'inactive' : 'active'
        ]);

        return redirect()->route('owner.staff.index')->with('success', 'Status staff berhasil diubah.');
    }
}

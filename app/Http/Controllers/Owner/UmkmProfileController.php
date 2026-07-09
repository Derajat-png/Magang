<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Umkm;
use App\Http\Requests\UpdateUmkmRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UmkmProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        $umkm = Umkm::findOrFail($user->umkm_id);
        
        Gate::authorize('update', $umkm);

        return view('owner.profile.edit', compact('umkm'));
    }

    public function update(UpdateUmkmRequest $request)
    {
        $user = auth()->user();
        $umkm = Umkm::findOrFail($user->umkm_id);

        Gate::authorize('update', $umkm);

        $umkm->update([
            'name' => $request->name,
            'business_type' => $request->business_type,
            'address' => $request->address,
            'phone' => $request->phone,
            'description' => $request->description,
        ]);

        return back()->with('success', 'Profil UMKM berhasil diperbarui.');
    }
}

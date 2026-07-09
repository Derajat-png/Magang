<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Umkm;
use App\Models\User;
use App\Http\Requests\StoreUmkmRequest;
use App\Http\Requests\UpdateUmkmRequest;
use Illuminate\Http\Request;

class UmkmController extends Controller
{
    public function index(Request $request)
    {
        $query = Umkm::with('owner');

        // Apply filters (Query parameters)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('business_type', $request->type);
        }

        $umkms = $query->latest()->paginate(10)->withQueryString();

        return view('admin.umkms.index', compact('umkms'));
    }

    public function create()
    {
        // Get owners who don't have a UMKM yet (or all owners)
        $owners = User::where('role', 'owner')->get();
        return view('admin.umkms.create', compact('owners'));
    }

    public function store(StoreUmkmRequest $request)
    {
        $umkm = Umkm::create([
            'owner_id' => $request->owner_id,
            'name' => $request->name,
            'business_type' => $request->business_type,
            'address' => $request->address,
            'phone' => $request->phone,
            'status' => $request->status,
        ]);

        // Link the owner user back to this UMKM
        $owner = User::findOrFail($request->owner_id);
        $owner->update(['umkm_id' => $umkm->id]);

        return redirect()->route('admin.umkms.index')->with('success', 'UMKM berhasil dibuat.');
    }

    public function edit(Umkm $umkm)
    {
        $owners = User::where('role', 'owner')->get();
        return view('admin.umkms.edit', compact('umkm', 'owners'));
    }

    public function update(UpdateUmkmRequest $request, Umkm $umkm)
    {
        $oldOwnerId = $umkm->owner_id;

        $umkm->update([
            'owner_id' => $request->owner_id,
            'name' => $request->name,
            'business_type' => $request->business_type,
            'address' => $request->address,
            'phone' => $request->phone,
            'status' => $request->status,
        ]);

        // Update owner umkm_id mappings
        if ($oldOwnerId != $request->owner_id) {
            // Remove UMKM link from old owner
            $oldOwner = User::find($oldOwnerId);
            if ($oldOwner) {
                $oldOwner->update(['umkm_id' => null]);
            }
        }

        $owner = User::findOrFail($request->owner_id);
        $owner->update(['umkm_id' => $umkm->id]);

        return redirect()->route('admin.umkms.index')->with('success', 'UMKM berhasil diperbarui.');
    }

    public function destroy(Umkm $umkm)
    {
        // Check if UMKM has any transactions (orders)
        if ($umkm->orders()->count() > 0) {
            // Prevent permanent deletion, suggest status inactive or use soft delete.
            // Since we use soft deletes, calling ->delete() is a soft delete!
            // But if we want to follow the requirement precisely, if it has transactions,
            // we should soft delete it and not allow forceDelete.
            // Let's perform a soft delete:
            $umkm->delete();
            return redirect()->route('admin.umkms.index')->with('success', 'UMKM memiliki transaksi aktif. UMKM berhasil di-nonaktifkan secara sistem (Soft Delete).');
        }

        // If no transactions, we can soft delete it or force delete it. Let's just soft delete.
        $umkm->delete();
        return redirect()->route('admin.umkms.index')->with('success', 'UMKM berhasil dihapus.');
    }
}

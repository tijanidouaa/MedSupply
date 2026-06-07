<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = User::where('role', 'supplier')
                        ->orWhere('role', 'fournisseur')
                        ->latest()
                        ->paginate(15);

        return view('admin.suppliers.index', compact('suppliers'));
    }

    public function show($id)
    {
        $supplier = User::findOrFail($id);
        return view('admin.suppliers.show', compact('supplier'));
    }

    public function edit($id)
    {
        $supplier = User::findOrFail($id);
        return view('admin.suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, $id)
    {
        $supplier = User::findOrFail($id);

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

        $supplier->update($request->only('name', 'email'));

        return redirect()->route('admin.suppliers.index')
                         ->with('success', 'Fournisseur mis à jour.');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();

        return redirect()->route('admin.suppliers.index')
                         ->with('success', 'Fournisseur supprimé.');
    }

    public function verify($id)
    {
        $supplier = User::findOrFail($id);
        $supplier->is_verified = true;
        $supplier->verified_at = now();
        $supplier->save();

        return redirect()->route('admin.suppliers.index')
                         ->with('success', 'Fournisseur vérifié avec succès.');
    }
}
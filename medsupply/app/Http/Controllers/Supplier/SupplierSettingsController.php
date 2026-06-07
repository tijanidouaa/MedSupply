<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SupplierSettingsController extends Controller
{
    public function index()
    {
        return view('supplier.settings', ['user' => Auth::user()]);
    }

    public function profile()
    {
        return view('supplier.profile', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);
        $user->update(['name' => $request->name, 'email' => $request->email]);
        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8|confirmed']);
            $user->update(['password' => Hash::make($request->password)]);
        }
        return back()->with('success', 'Paramètres mis à jour.');
    }
}
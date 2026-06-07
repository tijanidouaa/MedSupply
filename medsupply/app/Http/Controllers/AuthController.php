<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Supplier;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $role = Auth::user()->role;

            return match(true) {
                $role === 'admin'
                    => redirect('/admin/dashboard'),
                in_array($role, ['hospital_chief', 'hospital', 'hospital_manager'])
                    => redirect('/hospital/dashboard'),
                in_array($role, ['supplier', 'fournisseur'])
                    => redirect('/supplier/dashboard'),
                default
                    => redirect('/login')->withErrors(['email' => 'Rôle non reconnu.']),
            };
        }

        return back()
            ->withErrors(['email' => 'Email ou mot de passe incorrect.'])
            ->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $rules = [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role'     => 'required|in:hospital_chief,supplier',
        ];

        if ($request->role === 'supplier') {
            $rules['company_name'] = 'required|string|max:255';
            $rules['phone']        = 'required|string|max:20';
        }

        $validated = $request->validate($rules);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'],
        ]);

        // Si fournisseur → créer l'entrée dans la table suppliers
        if ($validated['role'] === 'supplier') {
            Supplier::create([
                'user_id'  => $user->id,
                'name'     => $request->company_name,
                'email'    => $validated['email'],
                'phone'    => $request->phone,
                'verified' => false,
                'rating'   => 0,
            ]);
        }

        Auth::login($user);

        return match($user->role) {
            'hospital_chief' => redirect('/hospital/dashboard'),
            'supplier'       => redirect('/supplier/dashboard'),
            default          => redirect('/login'),
        };
    }
}
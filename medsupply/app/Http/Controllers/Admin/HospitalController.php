<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hospital;
use Illuminate\Http\Request;

class HospitalController extends Controller
{
    public function index()
    {
        $hospitals = Hospital::paginate(15);
        return view('admin.hospitals.index', compact('hospitals'));
    }

    public function create()
    {
        return view('admin.hospitals.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'address'       => 'required|string',
            'city'          => 'required|string|max:100',
            'phone'         => 'required|string|max:30',
            'contact_email' => 'required|email|max:255',
            'is_active'     => 'boolean',
            'logo_path'     => 'nullable|string|max:500',
        ]);

        Hospital::create($validated);

        return redirect()->route('admin.hospitals.index')
                         ->with('success', 'Hôpital créé avec succès.');
    }

    public function show(Hospital $hospital)
    {
        return view('admin.hospitals.show', compact('hospital'));
    }

    public function edit(Hospital $hospital)
    {
        return view('admin.hospitals.edit', compact('hospital'));
    }

    public function update(Request $request, Hospital $hospital)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'address'       => 'required|string',
            'city'          => 'required|string|max:100',
            'phone'         => 'required|string|max:30',
            'contact_email' => 'required|email|max:255',
            'is_active'     => 'boolean',
            'logo_path'     => 'nullable|string|max:500',
        ]);

        $hospital->update($validated);

        return redirect()->route('admin.hospitals.index')
                         ->with('success', 'Hôpital modifié avec succès.');
    }

    public function destroy(Hospital $hospital)
    {
        $hospital->delete();

        return redirect()->route('admin.hospitals.index')
                         ->with('success', 'Hôpital supprimé.');
    }

    public function export()
    {
        $hospitals = Hospital::all();

        $csv = "ID,Nom,Adresse,Ville,Téléphone,Email,Statut,Créé le\n";
        foreach ($hospitals as $h) {
            $csv .= "{$h->id},{$h->name},{$h->address},{$h->city},{$h->phone},{$h->contact_email}," 
                  . ($h->is_active ? 'Actif' : 'Inactif') . ",{$h->created_at}\n";
        }

        return response($csv, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="hopitaux.csv"',
        ]);
    }
}
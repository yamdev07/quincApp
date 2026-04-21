<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CompanySettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function edit()
    {
        $tenant = Auth::user()->tenant;

        if (!$tenant) {
            abort(404, 'Aucune entreprise associée à votre compte.');
        }

        return view('company.settings', compact('tenant'));
    }

    public function update(Request $request)
    {
        $tenant = Auth::user()->tenant;

        if (!$tenant) {
            abort(404, 'Aucune entreprise associée à votre compte.');
        }

        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'email'        => 'nullable|email|max:255',
            'phone'        => 'nullable|string|max:50',
            'address'      => 'nullable|string|max:500',
            'ifu'          => 'nullable|string|max:100',
            'rccm'         => 'nullable|string|max:100',
            'tax_rate'     => 'nullable|numeric|min:0|max:100',
            'logo'         => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            if ($tenant->logo && Storage::disk('public')->exists($tenant->logo)) {
                Storage::disk('public')->delete($tenant->logo);
            }

            $path = $request->file('logo')->store('logos', 'public');
            $validated['logo'] = $path;
        }

        $tenant->update($validated);

        return redirect()->route('company.settings')->with('success', 'Paramètres de l\'entreprise mis à jour avec succès.');
    }

    public function deleteLogo()
    {
        $tenant = Auth::user()->tenant;

        if (!$tenant) {
            abort(404);
        }

        if ($tenant->logo && Storage::disk('public')->exists($tenant->logo)) {
            Storage::disk('public')->delete($tenant->logo);
        }

        $tenant->update(['logo' => null]);

        return redirect()->route('company.settings')->with('success', 'Logo supprimé avec succès.');
    }
}

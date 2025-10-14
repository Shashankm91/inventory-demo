<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    // apply auth middleware in routes; optionally protect with roles
    public function index(Request $request)
    {
        $q = $request->query('q');

        $suppliers = Supplier::when($q, function ($query, $q) {
                return $query->where('name', 'like', "%{$q}%")
                             ->orWhere('phone', 'like', "%{$q}%")
                             ->orWhere('email', 'like', "%{$q}%");
            })
            ->orderByDesc('created_at')
            ->get();
            

        return view('suppliers.index', compact('suppliers', 'q'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'nullable|email|max:255',
            'phone'          => 'required|nullable|string|max:30',
            'contact_person' => 'required|nullable|string|max:255',
            'address'        => 'required|nullable|string|max:2000',
            'notes'          => 'nullable|string|max:4000',
        ]);

        Supplier::create($validated);

        return redirect()->route('suppliers.index')->with('success', 'Supplier created successfully.');
    }

    public function show(Supplier $supplier)
    {
        return view('suppliers.show', compact('supplier'));
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'nullable|email|max:255',
            'phone'          => 'nullable|string|max:30',
            'contact_person' => 'nullable|string|max:255',
            'address'        => 'nullable|string|max:2000',
            'notes'          => 'nullable|string|max:4000',
        ]);

        $supplier->update($validated);

        return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully.');
    }

    public function destroy(Supplier $supplier)
    {
        // if you want to prevent deletion when products exist, check here
        // if ($supplier->products()->count()) { return back()->withErrors('...'); }

        $supplier->delete();

        return redirect()->route('suppliers.index')->with('success', 'Supplier deleted successfully.');
    }
}

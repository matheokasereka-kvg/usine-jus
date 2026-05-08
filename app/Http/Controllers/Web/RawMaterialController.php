<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\RawMaterial;
use App\Models\Supplier;
use Illuminate\Http\Request;

class RawMaterialController extends Controller
{
    public function index()
    {
        return view('raw_materials.index', [
            'materials' => RawMaterial::with('supplier')->latest()->paginate(10),
            'suppliers' => Supplier::orderBy('name')->get(),
        ]);
    }

    public function show(RawMaterial $rawMaterial)
    {
        return view('raw_materials.show', [
            'material' => $rawMaterial->load('supplier', 'productionDetails.production.product'),
        ]);
    }

    public function store(Request $request)
    {
        RawMaterial::create($request->validate([
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
            'name' => ['required', 'string', 'max:255'],
            'unit' => ['required', 'string', 'max:40'],
            'stock_quantity' => ['required', 'numeric', 'min:0'],
            'unit_cost' => ['required', 'numeric', 'min:0'],
            'alert_threshold' => ['required', 'numeric', 'min:0'],
        ]));

        return back()->with('success', 'Matiere premiere ajoutee.');
    }

    public function update(Request $request, RawMaterial $rawMaterial)
    {
        $rawMaterial->update($request->validate([
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
            'name' => ['required', 'string', 'max:255'],
            'unit' => ['required', 'string', 'max:40'],
            'stock_quantity' => ['required', 'numeric', 'min:0'],
            'unit_cost' => ['required', 'numeric', 'min:0'],
            'alert_threshold' => ['required', 'numeric', 'min:0'],
        ]));

        return back()->with('success', 'Matiere premiere modifiee.');
    }

    public function destroy(RawMaterial $rawMaterial)
    {
        $rawMaterial->delete();
        return back()->with('success', 'Matiere premiere supprimee.');
    }
}

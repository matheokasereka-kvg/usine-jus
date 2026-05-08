<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RawMaterial;
use Illuminate\Http\Request;

class RawMaterialController extends Controller
{
    public function index() { return RawMaterial::with('supplier')->latest()->paginate(20); }
    public function show(RawMaterial $rawMaterial) { return $rawMaterial->load('supplier'); }

    public function store(Request $request)
    {
        return RawMaterial::create($request->validate([
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
            'name' => ['required', 'string', 'max:255'],
            'unit' => ['required', 'string', 'max:40'],
            'stock_quantity' => ['nullable', 'numeric', 'min:0'],
            'unit_cost' => ['required', 'numeric', 'min:0'],
            'alert_threshold' => ['nullable', 'numeric', 'min:0'],
        ]));
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

        return $rawMaterial;
    }

    public function destroy(RawMaterial $rawMaterial)
    {
        $rawMaterial->delete();
        return response()->noContent();
    }
}

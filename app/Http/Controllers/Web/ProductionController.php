<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Product;
use App\Models\Production;
use App\Models\RawMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ProductionController extends Controller
{
    public function index()
    {
        return view('productions.index', [
            'productions' => Production::with('product', 'employee', 'details.rawMaterial')->latest()->paginate(10),
            'products' => Product::orderBy('name')->get(),
            'materials' => RawMaterial::orderBy('name')->get(),
            'employees' => Employee::orderBy('last_name')->get(),
        ]);
    }

    public function show(Production $production)
    {
        return view('productions.show', [
            'production' => $production->load('product', 'employee', 'details.rawMaterial'),
        ]);
    }

    public function store(Request $request)
    {
        $request->merge([
            'materials' => collect($request->input('materials', []))
                ->filter(fn ($line) => filled($line['raw_material_id'] ?? null) || filled($line['quantity_used'] ?? null))
                ->values()
                ->all(),
        ]);

        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'employee_id' => ['nullable', 'exists:employees,id'],
            'production_date' => ['required', 'date'],
            'quantity_produced' => ['required', 'numeric', 'min:0.01'],
            'notes' => ['nullable', 'string'],
            'materials' => ['required', 'array', 'min:1'],
            'materials.*.raw_material_id' => ['required', 'exists:raw_materials,id'],
            'materials.*.quantity_used' => ['required', 'numeric', 'min:0.01'],
        ]);

        DB::transaction(function () use ($data) {
            foreach ($data['materials'] as $line) {
                $material = RawMaterial::lockForUpdate()->find($line['raw_material_id']);
                if ($material->stock_quantity < $line['quantity_used']) {
                    throw ValidationException::withMessages([
                        'materials' => "Stock insuffisant pour {$material->name}.",
                    ]);
                }
            }

            $production = Production::create([
                'product_id' => $data['product_id'],
                'employee_id' => $data['employee_id'] ?? null,
                'production_date' => $data['production_date'],
                'quantity_produced' => $data['quantity_produced'],
                'status' => 'terminee',
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($data['materials'] as $line) {
                $material = RawMaterial::lockForUpdate()->find($line['raw_material_id']);
                $material->decrement('stock_quantity', $line['quantity_used']);
                $production->details()->create([
                    'raw_material_id' => $material->id,
                    'quantity_used' => $line['quantity_used'],
                    'unit_cost' => $material->unit_cost,
                ]);
            }

            Product::lockForUpdate()->find($data['product_id'])->increment('stock_quantity', $data['quantity_produced']);
        });

        return back()->with('success', 'Production enregistree et stocks mis a jour.');
    }

    public function destroy(Production $production)
    {
        DB::transaction(function () use ($production) {
            $production->load('details');
            Product::lockForUpdate()->find($production->product_id)->decrement('stock_quantity', $production->quantity_produced);
            foreach ($production->details as $detail) {
                RawMaterial::lockForUpdate()->find($detail->raw_material_id)->increment('stock_quantity', $detail->quantity_used);
            }
            $production->delete();
        });

        return back()->with('success', 'Production annulee et stocks restaures.');
    }
}

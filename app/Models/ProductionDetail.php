<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionDetail extends Model
{
    protected $fillable = ['production_id', 'raw_material_id', 'quantity_used', 'unit_cost'];

    public function production()
    {
        return $this->belongsTo(Production::class);
    }

    public function rawMaterial()
    {
        return $this->belongsTo(RawMaterial::class);
    }
}

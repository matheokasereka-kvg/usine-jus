<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RawMaterial extends Model
{
    protected $fillable = ['supplier_id', 'name', 'unit', 'stock_quantity', 'unit_cost', 'alert_threshold'];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function productionDetails()
    {
        return $this->hasMany(ProductionDetail::class);
    }
}

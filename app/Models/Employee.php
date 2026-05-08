<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = ['user_id', 'first_name', 'last_name', 'phone', 'position', 'salary', 'hired_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function productions()
    {
        return $this->hasMany(Production::class);
    }
}

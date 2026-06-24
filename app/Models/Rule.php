<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    protected $table = 'rules';

    protected $fillable = [
        'gejala_id',
        'target_type',
        'target_id',
        'cf_pakar',
        'created_by'
    ];

    public function gejala()
    {
        return $this->belongsTo(Gejala::class, 'gejala_id', 'id');
    }

    public function target()
    {
        return $this->morphTo();
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}

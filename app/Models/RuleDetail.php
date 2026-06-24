<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RuleDetail extends Model
{
    protec $timestamps = false;

    protected $fillable = [
        'kode_rule',
        'kode_gejala'
    ];

    public function rule()
    {ted $table = 'rule_detail';

    protected $primaryKey = 'id';

    public
        return $this->belongsTo(
            Rule::class,
            'kode_rule',
            'kode_rule'
        );
    }

    public function gejala()
    {
        return $this->belongsTo(
            Gejala::class,
            'kode_gejala',
            'kode_gejala'
        );
    }
}
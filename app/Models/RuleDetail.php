<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RuleDetail extends Model
{
    protected $table = 'rule_detail';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'kode_rule',
        'kode_gejala'
    ];

    public function rule()
    {
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
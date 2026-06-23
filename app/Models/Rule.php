<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    protected $table = 'rules';

    protected $primaryKey = 'kode_rule';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'kode_rule',
        'kode_diagnosa',
        'cf_rule'
    ];

    public function diagnosa()
    {
        return $this->belongsTo(
            Diagnosa::class,
            'kode_diagnosa',
            'kode_diagnosa'
        );
    }

    public function details()
    {
        return $this->hasMany(
            RuleDetail::class,
            'kode_rule',
            'kode_rule'
        );
    }
}



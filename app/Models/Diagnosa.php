<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Diagnosa extends Model
{
    protected $table = 'diagnosa';

    protected $primaryKey = 'kode_diagnosa';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'kode_diagnosa',
        'nama_diagnosa',
        'tipe'
    ];

    public function rules()
    {
        return $this->hasMany(
            Rule::class,
            'kode_diagnosa',
            'kode_diagnosa'
        );
    }
}
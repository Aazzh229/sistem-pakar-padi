<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hama extends Model
{
    protected $table = 'hama';

    protected $fillable = [
        'kode_hama',
        'nama_hama',
        'slug'
    ];

    public function rules()
    {
        return $this->morphMany(Rule::class, 'target');
    }
}

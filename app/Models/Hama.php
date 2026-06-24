<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hama extends Model
{
    protected $table = 'hama';

    protected $fillable = [
        'kode_hama',
        'nama_hama',
        'slug',
        'deskripsi',
        'created_by'
    ];

    public function rules()
    {
        return $this->morphMany(Rule::class, 'target');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}

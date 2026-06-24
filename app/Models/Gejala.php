<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gejala extends Model
{
    protected $table = 'gejala';

    protected $fillable = [
        'kode_gejala',
        'nama_gejala',
        'kategori',
        'deskripsi',
        'created_by'
    ];

    public function rules()
    {
        return $this->hasMany(Rule::class, 'gejala_id', 'id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
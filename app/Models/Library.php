<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Library extends Model
{
    protected $table = 'library';

    protected $fillable = [
        'jenis',
        'nama',
        'nama_latin',
        'deskripsi',
        'penyebab',
        'solusi',
        'pencegahan',
        'gambar',
        'created_by'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}

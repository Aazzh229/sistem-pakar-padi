<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiagnosisHistory extends Model
{
    protected $table = 'diagnosis_histories';

    protected $fillable = [
        'user_id',
        'hasil',
        'persentase'
    ];

    protected $casts = [
        'hasil' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}

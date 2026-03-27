<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogIt extends Model
{
    use HasFactory;

    protected $table = 'logs_it';
    protected $fillable = [
        'kategori',
        'item',
        'aktivitas',
        'status',
        'unit',
        'ttd',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}

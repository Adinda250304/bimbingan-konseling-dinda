<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KalenderBk extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Cast the dates
    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
        'is_available' => 'boolean',
    ];

    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }
}

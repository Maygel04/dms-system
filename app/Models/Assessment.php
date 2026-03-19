<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    protected $table = 'assessments';

    protected $fillable = [
        'application_id',   // ⭐ ADD THIS (IMPORTANT)
        'department',
        'amount',
        'remarks',
        'verified_on'
    ];

    public $timestamps = true; // since naa kay created_at & updated_at
}
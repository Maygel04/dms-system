<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Document;
use App\Models\User;

class Application extends Model
{
    protected $table = 'applications';

    protected $fillable = [
        'applicant_id'
    ];

    public function documents()
    {
        return $this->hasMany(Document::class, 'application_id');
    }

    // Applicant relationship
    public function user()
    {
        return $this->belongsTo(User::class, 'applicant_id');
    }
}
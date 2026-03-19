<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
  public function documents()
{
    return $this->hasMany(Document::class, 'application_id');
}
protected $table = 'documents';

    protected $fillable = [
        'application_id',
        'department',
        'file_name',
        'content_text',
        'viewed',
        'created_at',
        'content_text'
    ];
}
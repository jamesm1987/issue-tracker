<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    protected $fillable = [
        'key',
        'filename',
        'mime_type',
        'uploaded_by'
    ];

    public function uploadable()
    {
        return $this->morphTo();
    }
}

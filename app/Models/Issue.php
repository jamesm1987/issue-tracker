<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    protected $fillable = ['title', 'description'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}

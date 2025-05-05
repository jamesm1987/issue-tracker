<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Issue extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    protected $fillable = [
        'title', 
        'description', 
        'created_by',
        'created_by_name',
        'fix_by',
        'test_by'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function fixer()
    {
        return $this->belongsTo(User::class, 'fix_by');
    }

    public function tester()
    {
        return $this->belongsTo(User::class, 'test_by');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    // public function uploads()
    // {
    //     return $this->morphMany(Upload::class, 'uploadable');
    // }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Project extends Model implements Auditable
{
    use HasFactory; 
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'name',
        'created_by',
        'created_by_name',
    ];

    public function issues()
    {
        return $this->hasMany(Issue::class);
    }

    public function user()
    {
        return $this->belongsToMany(User::class, 'project_user');
    }

}

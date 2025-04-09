<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Ccomment extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['issue_id', 'body', 'created_by'];

    public function issue()
    {
        return $this->belongsTo(Issue::class);
    }
}

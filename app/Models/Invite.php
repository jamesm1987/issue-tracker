<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'invitations';

    protected $fillable = [
        'email',
        'token',
        'role_id',
        'invited_by',
        'expires_at',
        'accepted_at',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'token',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'accepted_at' => 'datetime',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidats extends Model
{
    use HasFactory;
    protected $fillable = [
        'fullName',
        'type',
        'pays',
        'election_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'pays',
        'remember_token',
    ];
}

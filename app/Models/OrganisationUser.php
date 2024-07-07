<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganisationUser extends Model
{
    use HasFactory;

    protected $table = 'organisation_user';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'organisation_id'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Organisation extends Model
{
    use HasFactory;

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
    protected $primaryKey = 'orgId';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'orgId', 'name', 'description',
    ];

    protected static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->orgId = Str::uuid();
        });
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'organisation_user', 'organisation_id', 'user_id');
    }

    public function getPublicColumns()
    {
        $columns = ['orgId', 'name', 'description'];
        return $this->only($columns);
    }

    public function AddUser(User $user){
        $this->users()->attach($user->userId);
    }
}

<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
// use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject; 
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
    protected $primaryKey = 'userId';
    public $incrementing = false;
    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->userId = Str::uuid();
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'userId',
        'firstName',
        'lastName',
        'email',
        'password',
        'phone'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function organisations()
    {
        return $this->belongsToMany(Organisation::class, 'organisation_user', 'user_id', 'organisation_id')->using(new class extends \Illuminate\Database\Eloquent\Relations\Pivot {
            use \Illuminate\Database\Eloquent\Concerns\HasUuids;
        });
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
 
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function createOrganisation(string $name = null, string $desc = null)
    {
        $organisation_name = $name ?: $this->firstName."'s Organisation";
        $organisation = Organisation::create([
            'name' => $organisation_name,
            'description' => $desc
        ]);
        $organisation->users()->attach((string)$this->userId);
        return $organisation;
    }

    public function getPublicColumns()
    {
        $columns = ['userId', 'firstName', 'lastName', 'email', 'phone'];
        return $this->only($columns);
    }

    public function organisationsPublicColumns(){
        $columns = ['orgId', 'name', 'description'];
        // return $this->organisations()->select($columns ?: ['orgId', 'name', 'description'])
                                    //   ->withPivot('user_id', 'organisation_id');
        // $allowedColumns = ['orgId', 'name', 'description'];

        // // Retrieve organisations with selected columns and pivot columns
        // $organisations = $this->organisations()->select($allowedColumns)->withPivot('user_id', 'organisation_id')->get();

        // // Transform the collection to remove pivot data
        // $organisations = $organisations->map(function ($organisation) use ($allowedColumns) {
        //     // Keep only the allowed columns from the organisation
        //     return $organisation->only($allowedColumns);
        // });

        // return $organisations;
        // $columns = ['orgId', 'name', 'description'];
        // $this->organisations->select($columns)->map(function ($organisation) use ($columns) {
        //     // Keep only the allowed columns from the organisation
        //     $filteredOrganisation = $organisation->select($columns);

        //     return $filteredOrganisation;
        // });
        return $this->organisations()->select($columns);
    }
}
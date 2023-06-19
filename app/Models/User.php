<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Spatie\Permission\Contracts\Role;

/**
 * Class User
 * @package App
 * @category model
 */
class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'phone',
        'address',
        'photo',
        'company_id',
        'locale',
        'date_of_birth',
        'gender',
        'blood_group',
        'status',
        'is_email_verified',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Has many relation with complains
     *
     * @return mixed
     */
    public function companies()
    {
        return $this->morphToMany(Company::class, 'user', 'user_companies', 'user_id', 'company_id');
    }

    public function getPhotoUrlAttribute()
    {
        if ($this->photo)
            return asset('storage/'.$this->photo);
        else
            return asset('img/placeholder.jpg');
    }

    public function scholarships()
    {
        return $this->hasMany(Scholarship::class);
    }

    public function scholarshipClasses()
    {
        return $this->hasMany(ScholarshipClass::class);
    }

    public function scholarshipYears()
    {
        return $this->hasMany(ScholarshipYears::class);
    }

    public function scholarshipVillages()
    {
        return $this->hasMany(ScholarshipVillage::class);
    }

    public function scholarshipSchools()
    {
        return $this->hasMany(ScholarshipSchool::class);
    }

    public function scholarshipBankDetails()
    {
        return $this->hasMany(ScholarshipBankDetail::class);
    }

    public function studentDetail()
    {
        return $this->belongsTo(StudentDetail::class);
    }

    public function scopeNotRole(Builder $query, $roles, $guard = null): Builder
    {
         if ($roles instanceof Collection) {
             $roles = $roles->all();
         }

         if (! is_array($roles)) {
             $roles = [$roles];
         }

         $roles = array_map(function ($role) use ($guard) {
             if ($role instanceof Role) {
                 return $role;
             }

             $method = is_numeric($role) ? 'findById' : 'findByName';
             $guard = $guard ?: $this->getDefaultGuardName();

             return $this->getRoleClass()->{$method}($role, $guard);
         }, $roles);

         return $query->whereHas('roles', function ($query) use ($roles) {
             $query->where(function ($query) use ($roles) {
                 foreach ($roles as $role) {
                     $query->where(config('permission.table_names.roles').'.id', '!=' , $role->id);
                 }
             });
         });
    }
}

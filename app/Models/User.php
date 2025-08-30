<?php

namespace App\Models;


use App\Traits\HasUuid;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasUuid;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $guard_name = 'sanctum';


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile',
        'current_tenant_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'profile' => 'array'
    ];

    public function tenants()
    {
        return $this->belongsToMany(Tenant::class, 'tenant_users')
            ->using(\App\Models\TenantUser::class)
            ->withPivot('role');
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function issues()
    {
        return $this->hasMany(Issue::class, 'assignee_id');
    }

    public function comments()
    {
        return $this->hasMany(IssueComment::class);
    }

    public function activities()
    {
        return $this->hasMany(IssueActivityLog::class, 'user_id');
    }

    public function refreshTokens()
    {
        return $this->hasMany(RefreshToken::class);
    }
}

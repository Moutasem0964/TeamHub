<?php

namespace App\Models;

use App\Traits\HasUuid;
use App\Traits\TenantScoped;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory, HasUuid;

    public $incrementing = false;
    protected $keyType = 'string';


    protected $fillable = ['name', 'settings', 'slug', 'plan'];

    protected $casts = [
        'settings' => 'array',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'tenant_users')
            ->using(\App\Models\TenantUser::class)
            ->withPivot('role');
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}

<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = ['name', 'settings'];

    protected $casts = [
        'settings' => 'array',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'tenant_users')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}

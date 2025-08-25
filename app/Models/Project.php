<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = ['tenant_id', 'name', 'description', 'status'];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function boards()
    {
        return $this->hasMany(Board::class);
    }

    public function sprints()
    {
        return $this->hasMany(Sprint::class);
    }

    public function issues()
    {
        return $this->hasMany(Issue::class);
    }

    public function webhooks()
    {
        return $this->hasMany(Webhook::class);
    }
}

<?php

namespace App\Models;

use App\Traits\HasUuid;
use App\Traits\TenantScoped;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = ['project_id', 'name', 'type'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function issues()
    {
        return $this->hasMany(Issue::class);
    }
}

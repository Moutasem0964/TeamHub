<?php

namespace App\Models;

use App\Traits\HasUuid;
use App\Traits\TenantScoped;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Webhook extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = ['project_id', 'url', 'event', 'status'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}

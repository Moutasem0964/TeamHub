<?php

namespace App\Models;

use App\Traits\HasUuid;
use App\Traits\TenantScoped;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IssueComment extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = ['issue_id', 'user_id', 'content'];

    public function issue()
    {
        return $this->belongsTo(Issue::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IssueAttachment extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = ['issue_id', 'file_path', 'file_type'];

    public function issue()
    {
        return $this->belongsTo(Issue::class);
    }
}

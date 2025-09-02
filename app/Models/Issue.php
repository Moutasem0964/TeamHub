<?php

namespace App\Models;

use App\Traits\HasUuid;
use App\Traits\TenantScoped;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'project_id',
        'board_id',
        'sprint_id',
        'title',
        'description',
        'status',
        'priority',
        'assignee_id',
        'reporter_id'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function board()
    {
        return $this->belongsTo(Board::class);
    }

    public function sprint()
    {
        return $this->belongsTo(Sprint::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function comments()
    {
        return $this->hasMany(IssueComment::class);
    }

    public function attachments()
    {
        return $this->hasMany(IssueAttachment::class);
    }

    public function labels()
    {
        return $this->belongsToMany(Label::class, 'issue_labels');
    }

    public function activities()
    {
        return $this->hasMany(IssueActivityLog::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class IssueLabel extends Pivot
{
    use HasFactory;

    protected $table = 'issue_labels';

    protected $fillable = ['issue_id', 'label_id'];
}

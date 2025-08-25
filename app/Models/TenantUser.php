<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class TenantUser extends Pivot
{
    use HasFactory;

    protected $table = 'tenant_users';

    protected $fillable = ['tenant_id', 'user_id', 'role'];
}

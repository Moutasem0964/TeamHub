<?php

namespace App\Traits;

use App\Models\Scopes\TenantScope;

trait TenantScoped
{
    public static function bootTenantScoped()
    {
        static::addGlobalScope(new TenantScope());

        static::creating(function ($model) {
            if ($tenantId = app('tenant_id')) {
                $model->tenant_id = $tenantId;
            }
        });
    }
}

<?php

namespace App\Models;

use App\Contracts\Permissionable;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole implements Permissionable
{
    public static function getPermissions(): array
    {
        return [
            'create',
            'view',
            'update',
            'delete',
            'assign',    // Permission khusus untuk assign role
            'sync',      // Permission untuk sync permissions ke role
            'replicate',
            'export',
        ];
    }
}

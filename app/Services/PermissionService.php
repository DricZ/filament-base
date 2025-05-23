<?php

namespace App\Services;

use App\Contracts\Permissionable;
use Spatie\Permission\Models\Permission;

class PermissionService
{
    /**
     * Sync semua permission untuk model yang terdaftar
     */
    public function syncAll(): void
    {
        $models = config('permission.models', []);

        foreach ($models as $modelClass) {
            if (!class_exists($modelClass)) {
                continue;
            }

            if (!in_array(Permissionable::class, class_implements($modelClass))) {
                continue;
            }

            $this->syncForModel($modelClass);
        }
    }

    /**
     * Sync permission untuk model spesifik
     */
    public function syncForModel(string $modelClass): void
    {
        $modelName = class_basename($modelClass);

        // Penanganan khusus untuk model Role
        if ($modelName === 'Role') {
            $modelName = 'Role'; // Force nama permission tetap 'Role'
            $permissions = $modelClass::getPermissions();
        } else {
            $permissions = $modelClass::getPermissions();
        }

        $modelName = class_basename($modelClass);
        $permissions = $modelClass::getPermissions();

        foreach (config('permission.guards', ['web', 'api']) as $guard) {
            foreach ($permissions as $action) {
                try {
                    Permission::firstOrCreate(
                        ['name' => "{$action} {$modelName}", 'guard_name' => $guard],
                        ['guard_name' => $guard]
                    );
                } catch (\Exception $e) {
                    logger()->error("Permission creation failed: " . $e->getMessage());
                }
            }
        }
    }
}

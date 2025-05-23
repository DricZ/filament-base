<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PermissionService;

class GeneratePermissions extends Command
{
    protected $signature = 'permissions:sync';
    protected $description = 'Sinkronisasi permission berdasarkan model';

    public function handle(PermissionService $service)
    {
        $this->info('Memulai sinkronisasi permission...');

        $service->syncAll();

        $this->info('Sinkronisasi permission selesai!');
        $this->info('Jumlah permission: '. \Spatie\Permission\Models\Permission::count());
    }
}

<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\ServiceProvider;
use App\Policies;
use Spatie\Permission\Models\Role as SpatieRole;
use App\Models\Role;
use Spatie\Permission\Models\Permission;
use Rupadana\ApiService\Models\Token;
use Illuminate\Support\Facades\Gate;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Gate::policy(Role::class, Policies\RolePolicy::class);
        Gate::policy(Permission::class, Policies\PermissionPolicy::class);

        Gate::before(function (User $user, string $ability) {
            return $user->isSuperAdmin() ? true: null;
        });
        // Gate::policy(Token::class, Policies\TokensPolicy::class);
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->bind(SpatieRole::class, Role::class);

        // Registrasi command
        if ($this->app->runningInConsole()) {
            $this->commands([
                \App\Console\Commands\GeneratePermissions::class,
            ]);
        }

        // Filament::serving(function () {
        //     Filament::registerNavigationGroups([
        //         NavigationGroup::make()
        //              ->label('Member'),
        //         NavigationGroup::make()
        //             ->label('Vendor'),
        //         NavigationGroup::make()
        //             ->label('Product'),
        //         NavigationGroup::make()
        //             ->label('Message'),
        //         NavigationGroup::make()
        //             ->label('Master'),
        //         NavigationGroup::make()
        //             ->label('User')
        //             ->collapsed(),
        //     ]);
        // });
    }
}

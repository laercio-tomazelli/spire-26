<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Exchange;
use App\Models\Order;
use App\Models\Part;
use App\Models\Partner;
use App\Models\ProductModel;
use App\Models\ServiceOrder;
use App\Models\User;
use App\Models\Warehouse;
use App\Policies\ExchangePolicy;
use App\Policies\OrderPolicy;
use App\Policies\PartnerPolicy;
use App\Policies\PartPolicy;
use App\Policies\ProductModelPolicy;
use App\Policies\ServiceOrderPolicy;
use App\Policies\UserPolicy;
use App\Policies\WarehousePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        ServiceOrder::class => ServiceOrderPolicy::class,
        Partner::class => PartnerPolicy::class,
        Order::class => OrderPolicy::class,
        Exchange::class => ExchangePolicy::class,
        User::class => UserPolicy::class,
        ProductModel::class => ProductModelPolicy::class,
        Part::class => PartPolicy::class,
        Warehouse::class => WarehousePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        $this->registerGates();
    }

    /**
     * Register global gates.
     */
    private function registerGates(): void
    {
        // Super admin bypass - Spire users with 'super-admin' role bypass all gates
        Gate::before(function (User $user, string $ability): ?bool {
            if ($user->hasRole('super-admin')) {
                return true;
            }

            return null;
        });

        // User type gates
        Gate::define('is-spire', fn (User $user): bool => $user->isSpire());

        Gate::define('is-spire-client', fn (User $user): bool => $user->isSpireClient());

        Gate::define('is-spire-user', fn (User $user): bool => $user->isSpireUser());

        Gate::define('is-partner', fn (User $user): bool => $user->isPartner());

        Gate::define('is-manufacturer', fn (User $user): bool => $user->isManufacturer());

        Gate::define('is-partner-admin', fn (User $user): bool => $user->isPartnerAdmin());

        // Module access gates (all authenticated users are "internal" now)
        Gate::define('access-dashboard', fn (User $user): bool => true);

        Gate::define('access-service-orders', fn (User $user): bool => $user->user_type->canManageServiceOrders());

        Gate::define('access-parts', fn (User $user): bool => $user->user_type->canManageParts());

        Gate::define('access-orders', fn (User $user): bool => true);

        Gate::define('access-exchanges', fn (User $user): bool => true);

        Gate::define('access-inventory', fn (User $user): bool => $user->isSpireUser() || $user->isPartner());

        Gate::define('access-reports', fn (User $user): bool => $user->hasPermission('reports.view'));

        Gate::define('access-financial', fn (User $user): bool => ($user->isSpireUser() || $user->isPartnerAdmin())
            && $user->hasPermission('financial.view'),
        );

        Gate::define('access-admin', fn (User $user): bool => $user->isSpire() && $user->hasPermission('admin.access'));

        Gate::define('access-settings', fn (User $user): bool => $user->isSpire()
            || $user->isPartnerAdmin()
            || ($user->isManufacturer() && $user->hasPermission('settings.view')),
        );

        // Specific action gates
        Gate::define('manage-users', fn (User $user): bool => $user->isSpire()
            || $user->isPartnerAdmin()
            || ($user->isManufacturer() && $user->hasPermission('users.manage')),
        );

        Gate::define('manage-roles', fn (User $user): bool => $user->isSpire() && $user->hasPermission('roles.manage'));

        Gate::define('manage-permissions', fn (User $user): bool => $user->isSpire() && $user->hasPermission('permissions.manage'));

        Gate::define('view-audit-log', fn (User $user): bool => $user->isSpire() && $user->hasPermission('audit.view'));

        Gate::define('export-data', fn (User $user): bool => $user->hasPermission('data.export'));

        Gate::define('import-data', fn (User $user): bool => $user->isSpire() && $user->hasPermission('data.import'));
    }
}

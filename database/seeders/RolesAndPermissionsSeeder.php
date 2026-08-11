<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Dashboard
            'dashboard.view',

            // Products
            'products.view',
            'products.create',
            'products.update',
            'products.delete',

            // Categories
            'categories.view',
            'categories.create',
            'categories.update',
            'categories.delete',

            // Orders
            'orders.view',
            'orders.create',
            'orders.update',
            'orders.delete',

            // Customers
            'customers.view',
            'customers.update',
            'customers.delete',

            // Coupons
            'coupons.view',
            'coupons.create',
            'coupons.update',
            'coupons.delete',

            // Shipping
            'shipping.view',
            'shipping.create',
            'shipping.update',
            'shipping.delete',

            // Reports
            'reports.view',

            // Settings
            'settings.view',
            'settings.update',

            // Users
            'users.view',
            'users.create',
            'users.update',
            'users.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        $superAdmin = Role::firstOrCreate([
            'name' => 'Super Admin',
            'guard_name' => 'web',
        ]);

        $admin = Role::firstOrCreate([
            'name' => 'Admin',
            'guard_name' => 'web',
        ]);

        $productManager = Role::firstOrCreate([
            'name' => 'Product Manager',
            'guard_name' => 'web',
        ]);

        $orderManager = Role::firstOrCreate([
            'name' => 'Order Manager',
            'guard_name' => 'web',
        ]);

        $customerSupport = Role::firstOrCreate([
            'name' => 'Customer Support',
            'guard_name' => 'web',
        ]);

        // Super Admin gets everything.
        $superAdmin->syncPermissions(Permission::all());

        // Admin
        $admin->syncPermissions([
            'dashboard.view',

            'products.view',
            'products.create',
            'products.update',
            'products.delete',

            'categories.view',
            'categories.create',
            'categories.update',
            'categories.delete',

            'orders.view',
            'orders.update',

            'customers.view',
            'customers.update',

            'coupons.view',
            'coupons.create',
            'coupons.update',
            'coupons.delete',

            'shipping.view',
            'shipping.create',
            'shipping.update',

            'reports.view',
        ]);

        // Product Manager
        $productManager->syncPermissions([
            'dashboard.view',

            'products.view',
            'products.create',
            'products.update',
            'products.delete',

            'categories.view',
            'categories.create',
            'categories.update',
            'categories.delete',
        ]);

        // Order Manager
        $orderManager->syncPermissions([
            'dashboard.view',

            'orders.view',
            'orders.update',

            'customers.view',
            'customers.update',

            'shipping.view',
            'shipping.update',
        ]);

        // Customer Support
        $customerSupport->syncPermissions([
            'dashboard.view',

            'orders.view',
            'orders.update',

            'customers.view',
            'customers.update',
        ]);
    }
}

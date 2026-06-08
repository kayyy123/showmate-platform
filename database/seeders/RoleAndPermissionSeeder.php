<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'view dashboard',
            'view stores', 'create stores', 'edit stores', 'delete stores',
            'view products', 'create products', 'edit products', 'delete products',
            'view categories', 'create categories', 'edit categories', 'delete categories',
            'view subscriptions', 'manage subscriptions',
            'view orders', 'manage orders',
            'view withdrawals', 'manage withdrawals',
            'view inclusive applications', 'manage inclusive applications',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->syncPermissions(Permission::all());

        $merchant = Role::firstOrCreate(['name' => 'merchant', 'guard_name' => 'web']);
        $merchant->syncPermissions([
            'view products', 'create products', 'edit products', 'delete products',
            'view orders',
            'view withdrawals',
            'view inclusive applications',
        ]);

        User::where('role', 'admin')->get()->each(fn($user) => $user->assignRole('admin'));
        User::where('role', 'merchant')->get()->each(fn($user) => $user->assignRole('merchant'));
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'merchant', 'guard_name' => 'web']);

        foreach (User::all() as $user) {
            if ($user->role === 'admin' && !$user->hasRole('admin')) {
                $user->assignRole('admin');
            } elseif ($user->role === 'merchant' && !$user->hasRole('merchant')) {
                $user->assignRole('merchant');
            }
        }
    }
}

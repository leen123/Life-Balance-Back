<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AdministrationRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'view-ads', 'add-ad', 'show-ad', 'edit-ad', 'delete-ad',
            'view-coupons', 'add-coupon', 'show-coupon', 'edit-coupon', 'delete-coupon',
            'view-coupon-owners', 'add-coupon-owner', 'show-coupon-owner', 'edit-coupon-owner', 'delete-coupon-owner',
            'view-users', 'add-user', 'show-user', 'edit-user', 'delete-user',
            'view-roles', 'add-role', 'show-role', 'edit-role', 'delete-role',
            'control-budget-points',
            'view-activity-log', 'show-activity-log', 'delete-activity-log', 'clean-activity-log',
            'manage-notifications', 'manage-reports', 'control-settings','edit-profile'
        ];

        $role = Role::create(['name' => 'administration','guard_name' => 'api']);
        $role->givePermissionTo($permissions);
    }
}

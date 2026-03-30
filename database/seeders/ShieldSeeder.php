<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use BezhanSalleh\FilamentShield\Support\Utils;
use Spatie\Permission\PermissionRegistrar;

class ShieldSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $rolesWithPermissions = '[{"name":"super_admin","guard_name":"web","permissions":["view_any_cash::flow","create_cash::flow","update_cash::flow","delete_any_cash::flow","view_category","view_any_category","create_category","update_category","delete_category","delete_any_category","restore_category","restore_any_category","force_delete_category","force_delete_any_category","view_any_inventory","create_inventory","update_inventory","delete_any_inventory","view_payment::method","view_any_payment::method","create_payment::method","update_payment::method","delete_payment::method","delete_any_payment::method","restore_payment::method","restore_any_payment::method","force_delete_payment::method","force_delete_any_payment::method","view_any_product","create_product","update_product","delete_product","delete_any_product","restore_product","restore_any_product","force_delete_product","force_delete_any_product","view_any_report","create_report","update_report","delete_any_report","view_role","view_any_role","create_role","update_role","delete_role","delete_any_role","view_any_setting","create_setting","update_setting","delete_any_setting","view_transaction","view_any_transaction","create_transaction","update_transaction","delete_transaction","delete_any_transaction","restore_transaction","restore_any_transaction","force_delete_transaction","force_delete_any_transaction","view_any_user","create_user","update_user","delete_any_user","toggle_active_user","_Dashboard","_PosPage"]},{"name":"kasir","guard_name":"web","permissions":["view_any_cash::flow","create_cash::flow","update_cash::flow","delete_any_cash::flow","view_category","view_any_category","create_category","update_category","delete_category","delete_any_category","restore_category","restore_any_category","force_delete_category","force_delete_any_category","view_any_inventory","create_inventory","update_inventory","delete_any_inventory","view_payment::method","view_any_payment::method","create_payment::method","update_payment::method","delete_payment::method","delete_any_payment::method","restore_payment::method","restore_any_payment::method","force_delete_payment::method","force_delete_any_payment::method","view_any_product","create_product","update_product","delete_product","delete_any_product","restore_product","restore_any_product","force_delete_product","force_delete_any_product","view_any_report","create_report","update_report","delete_any_report","view_role","view_any_role","create_role","update_role","delete_role","delete_any_role","view_any_setting","create_setting","update_setting","delete_any_setting","view_transaction","view_any_transaction","create_transaction","update_transaction","delete_transaction","delete_any_transaction","restore_transaction","restore_any_transaction","force_delete_transaction","force_delete_any_transaction","view_any_user","create_user","update_user","delete_any_user","toggle_active_user","_Dashboard","_PosPage"]}]';
        $directPermissions = '[]';

        static::makeRolesWithPermissions($rolesWithPermissions);
        static::makeDirectPermissions($directPermissions);

        $this->command->info('Shield Seeding Completed.');
    }

    protected static function makeRolesWithPermissions(string $rolesWithPermissions): void
    {
        if (! blank($rolePlusPermissions = json_decode($rolesWithPermissions, true))) {
            /** @var Model $roleModel */
            $roleModel = Utils::getRoleModel();
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($rolePlusPermissions as $rolePlusPermission) {
                $role = $roleModel::firstOrCreate([
                    'name' => $rolePlusPermission['name'],
                    'guard_name' => $rolePlusPermission['guard_name'],
                ]);

                if (! blank($rolePlusPermission['permissions'])) {
                    $permissionModels = collect($rolePlusPermission['permissions'])
                        ->map(fn ($permission) => $permissionModel::firstOrCreate([
                            'name' => $permission,
                            'guard_name' => $rolePlusPermission['guard_name'],
                        ]))
                        ->all();

                    $role->syncPermissions($permissionModels);
                }
            }
        }
    }

    public static function makeDirectPermissions(string $directPermissions): void
    {
        if (! blank($permissions = json_decode($directPermissions, true))) {
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($permissions as $permission) {
                if ($permissionModel::whereName($permission)->doesntExist()) {
                    $permissionModel::create([
                        'name' => $permission['name'],
                        'guard_name' => $permission['guard_name'],
                    ]);
                }
            }
        }
    }
}
